<?php

namespace App\Helpers;

class CryptoHelper
{
    private const METHOD = 'aes-256-gcm';
    private const PREFIX = '$enc$:';
    private const SENSITIVE_KEYS = [
        'smtp_pass',
        'razorpay_key_secret',
        'razorpay_test_key_secret',
        'razorpay_live_key_secret',
        'phonepe_salt_key',
    ];

    private static function getKey(): string
    {
        if (!defined('APP_KEY') || empty(APP_KEY)) {
            $envFile = __DIR__ . '/../../.env';
            if (!file_exists($envFile)) {
                $envFile = __DIR__ . '/../../../.env';
            }
            if (file_exists($envFile)) {
                $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    if (strpos(trim($line), '#') === 0) continue;
                    if (strpos($line, 'APP_KEY=') === 0) {
                        $parts = explode('=', $line, 2);
                        $key = trim($parts[1] ?? '');
                        if (!empty($key)) {
                            define('APP_KEY', $key);
                        }
                        break;
                    }
                }
            }
        }
        if (!defined('APP_KEY') || empty(APP_KEY)) {
            throw new \RuntimeException('APP_KEY is not defined. Set APP_KEY in .env file.');
        }
        return hex2bin(APP_KEY);
    }

    public static function encrypt(string $plaintext): string
    {
        $key = self::getKey();
        $nonce = random_bytes(12);
        $ciphertext = openssl_encrypt($plaintext, self::METHOD, $key, OPENSSL_RAW_DATA, $nonce, $tag);
        if ($ciphertext === false) {
            throw new \RuntimeException('Encryption failed: ' . openssl_error_string());
        }
        return self::PREFIX . base64_encode($nonce . $tag . $ciphertext);
    }

    public static function decrypt(string $ciphertext): ?string
    {
        if (!self::isEncrypted($ciphertext)) {
            return null;
        }
        $payload = base64_decode(substr($ciphertext, strlen(self::PREFIX)), true);
        if ($payload === false || strlen($payload) < 28) {
            return null;
        }
        $nonce = substr($payload, 0, 12);
        $tag = substr($payload, 12, 16);
        $data = substr($payload, 28);
        $key = self::getKey();
        $plaintext = openssl_decrypt($data, self::METHOD, $key, OPENSSL_RAW_DATA, $nonce, $tag);
        return $plaintext !== false ? $plaintext : null;
    }

    public static function isEncrypted(string $value): bool
    {
        return strpos($value, self::PREFIX) === 0;
    }

    public static function isSensitiveKey(string $key): bool
    {
        return in_array($key, self::SENSITIVE_KEYS, true);
    }

    public static function getSensitiveKeys(): array
    {
        return self::SENSITIVE_KEYS;
    }
}
