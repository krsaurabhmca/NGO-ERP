<?php

namespace App\Core;

class Mailer
{
    protected array $settings;
    protected string $lastError = '';

    public function __construct(array $settings = [])
    {
        $this->settings = $settings;
    }

    public function getLastError(): string
    {
        return $this->lastError;
    }

    public function send(string $to, string $subject, string $message, bool $isHtml = false): bool
    {
        $host = trim($this->settings['smtp_host'] ?? '');
        if (!empty($host)) {
            return $this->sendViaSmtp($to, $subject, $message, $isHtml);
        }
        return $this->sendViaMail($to, $subject, $message, $isHtml);
    }

    protected function sendViaMail(string $to, string $subject, string $message, bool $isHtml = false): bool
    {
        $defaultHost = $_SERVER['SERVER_NAME'] ?? 'localhost';
        if ($defaultHost === 'localhost' || $defaultHost === '127.0.0.1') {
            $defaultHost = 'ngo.com';
        }
        $fromName = str_replace(["\r", "\n", "\r\n"], '', $this->settings['smtp_from_name'] ?? ($this->settings['ngo_name'] ?? 'NGO'));
        $fromEmail = str_replace(["\r", "\n", "\r\n"], '', $this->settings['smtp_from_email'] ?? ($this->settings['ngo_email'] ?? "noreply@{$defaultHost}"));

        $headers = "From: {$fromName} <{$fromEmail}>\r\n";
        $headers .= "Reply-To: {$fromEmail}\r\n";
        $headers .= "X-Mailer: NGO Management System\r\n";

        if ($isHtml) {
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            if (stripos($message, '<html') === false) {
                $message = "<!DOCTYPE html><html><head><meta charset=\"UTF-8\"></head><body>{$message}</body></html>";
            }
        } else {
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        }

        $subject = str_replace(["\r", "\n", "\r\n"], '', $subject);

        $result = @mail($to, $subject, $message, $headers);
        if (!$result) {
            $this->lastError = 'PHP mail() function returned false. Check server mail configuration or configure SMTP.';
            error_log('[Mailer] ' . $this->lastError);
        }
        return $result;
    }

    protected function sendViaSmtp(string $to, string $subject, string $message, bool $isHtml = false): bool
    {
        $host = trim($this->settings['smtp_host'] ?? '');
        $port = intval($this->settings['smtp_port'] ?? 587);
        $user = trim($this->settings['smtp_user'] ?? '');
        $pass = $this->settings['smtp_pass'] ?? '';
        if (\App\Helpers\CryptoHelper::isEncrypted($pass)) {
            $decrypted = \App\Helpers\CryptoHelper::decrypt($pass);
            if ($decrypted !== null) {
                $pass = $decrypted;
            }
        }
        $encryption = strtolower(trim($this->settings['smtp_encryption'] ?? 'tls'));

        $defaultHost = $_SERVER['SERVER_NAME'] ?? 'localhost';
        if ($defaultHost === 'localhost' || $defaultHost === '127.0.0.1') {
            $defaultHost = 'ngo.com';
        }
        $fromName = str_replace(["\r", "\n", "\r\n"], '', $this->settings['smtp_from_name'] ?? ($this->settings['ngo_name'] ?? 'NGO'));
        $fromEmail = str_replace(["\r", "\n", "\r\n"], '', $this->settings['smtp_from_email'] ?? ($this->settings['ngo_email'] ?? $user));
        if (empty($fromEmail)) {
            $fromEmail = "noreply@{$defaultHost}";
        }

        $target = ($encryption === 'ssl' || $port === 465) ? "ssl://{$host}:{$port}" : "tcp://{$host}:{$port}";

        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);

        $socket = @stream_socket_client($target, $errno, $errstr, 15, STREAM_CLIENT_CONNECT, $context);
        if (!$socket) {
            $this->lastError = "SMTP connection to {$target} failed: [{$errno}] {$errstr}";
            error_log('[Mailer] ' . $this->lastError);
            return false;
        }

        stream_set_timeout($socket, 15);

        $readResponse = function() use ($socket): string {
            $data = '';
            while ($str = fgets($socket, 515)) {
                $data .= $str;
                if (strlen($str) >= 4 && substr($str, 3, 1) === ' ') {
                    break;
                }
            }
            return $data;
        };

        $sendCommand = function(string $cmd, array $expectedCodes, bool $isSensitive = false) use ($socket, $readResponse): bool {
            fputs($socket, $cmd . "\r\n");
            $response = $readResponse();
            $code = intval(substr($response, 0, 3));
            if (!in_array($code, $expectedCodes, true)) {
                $displayCmd = $isSensitive ? '***' : $cmd;
                $this->lastError = "SMTP Error for '{$displayCmd}': {$response}";
                error_log('[Mailer] ' . $this->lastError);
                return false;
            }
            return true;
        };

        $greeting = $readResponse();
        if (intval(substr($greeting, 0, 3)) !== 220) {
            $this->lastError = "SMTP server greeting error: {$greeting}";
            fclose($socket);
            return false;
        }

        $clientHost = $_SERVER['SERVER_NAME'] ?? 'localhost';
        if (!$sendCommand("EHLO {$clientHost}", [250])) {
            if (!$sendCommand("HELO {$clientHost}", [250])) {
                fclose($socket);
                return false;
            }
        }

        // Upgrade socket with STARTTLS if port 587 or TLS requested
        if (($encryption === 'tls' || $port === 587) && strpos($target, 'ssl://') !== 0) {
            if ($sendCommand("STARTTLS", [220])) {
                $crypto = @stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
                if (!$crypto) {
                    $this->lastError = "SMTP STARTTLS encryption handshake failed.";
                    error_log('[Mailer] ' . $this->lastError);
                    fclose($socket);
                    return false;
                }
                if (!$sendCommand("EHLO {$clientHost}", [250])) {
                    fclose($socket);
                    return false;
                }
            }
        }

        // Authenticate with AUTH LOGIN if username and password are provided
        if (!empty($user) && !empty($pass)) {
            if (!$sendCommand("AUTH LOGIN", [334])) {
                fclose($socket);
                return false;
            }
            if (!$sendCommand(base64_encode($user), [334], true)) {
                $this->lastError = "SMTP Username rejected by server.";
                fclose($socket);
                return false;
            }
            if (!$sendCommand(base64_encode($pass), [235], true)) {
                $this->lastError = "SMTP Authentication failed: Password was rejected by {$host}. Please verify your SMTP password.";
                fclose($socket);
                return false;
            }
        }

        if (!$sendCommand("MAIL FROM: <{$fromEmail}>", [250])) {
            fclose($socket);
            return false;
        }

        if (!$sendCommand("RCPT TO: <{$to}>", [250, 251])) {
            fclose($socket);
            return false;
        }

        if (!$sendCommand("DATA", [354])) {
            fclose($socket);
            return false;
        }

        // Build email headers and content
        $subject = str_replace(["\r", "\n", "\r\n"], '', $subject);
        $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
        $encodedFromName = '=?UTF-8?B?' . base64_encode($fromName) . '?=';

        $emailHeaders = [
            "Date: " . date('r'),
            "From: {$encodedFromName} <{$fromEmail}>",
            "Reply-To: <{$fromEmail}>",
            "To: <{$to}>",
            "Subject: {$encodedSubject}",
            "MIME-Version: 1.0",
            "Content-Type: " . ($isHtml ? "text/html; charset=UTF-8" : "text/plain; charset=UTF-8"),
            "Content-Transfer-Encoding: base64",
            "X-Mailer: NGO Management System"
        ];
        $formattedBody = $message;
        if ($isHtml && stripos($message, '<html') === false) {
            $formattedBody = "<!DOCTYPE html><html><head><meta charset=\"UTF-8\"></head><body>{$message}</body></html>";
        }

        $bodyPayload = implode("\r\n", $emailHeaders) . "\r\n\r\n" . chunk_split(base64_encode($formattedBody));

        fputs($socket, $bodyPayload . "\r\n.\r\n");
        $dataRes = $readResponse();
        if (intval(substr($dataRes, 0, 3)) !== 250) {
            $this->lastError = "SMTP message sending failed: {$dataRes}";
            error_log('[Mailer] ' . $this->lastError);
            fclose($socket);
            return false;
        }

        $sendCommand("QUIT", [221, 250]);
        fclose($socket);
        return true;
    }
}
