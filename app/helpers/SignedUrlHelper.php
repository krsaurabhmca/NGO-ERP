<?php

namespace App\Helpers;

use PDO;

/**
 * SignedUrlHelper - Generate and verify cryptographically signed URLs with expiration
 * 
 * This class provides secure, time-limited URL generation for sensitive resources like
 * receipts, certificates, and downloads. URLs are signed with HMAC-SHA256 and include
 * expiration timestamps to prevent unauthorized long-term access.
 * 
 * Security Features:
 * - HMAC-SHA256 signature using application secret key
 * - Configurable expiration times
 * - Timing-safe signature comparison
 * - Protection against URL tampering
 * 
 * Usage Example:
 * 
 *   // Generate signed URL (expires in 1 hour)
 *   $url = SignedUrlHelper::generate('donate/receipt/abc123', 3600);
 *   
 *   // Verify signed URL from request
 *   if (SignedUrlHelper::verify()) {
 *       // URL is valid and not expired
 *   }
 */
class SignedUrlHelper
{
    /**
     * Default expiration time in seconds (1 hour)
     */
    const DEFAULT_EXPIRATION = 3600;

    /**
     * Secret key for signing URLs
     * Should be stored in environment config or database
     * 
     * @return string
     */
    private static function getSecretKey()
    {
        // Try to get from environment or config
        if (defined('APP_KEY')) {
            return APP_KEY;
        }

        // Fallback: generate from session secret or database config
        // In production, this should be a persistent secret stored securely
        $db = \App\Core\Database::getInstance();
        $stmt = $db->prepare("SELECT config_value FROM system_config WHERE config_key = ? LIMIT 1");
        $stmt->execute(['signed_url_secret']);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            return $result['config_value'];
        }

        // If no secret exists, generate and store one (first-time setup)
        $secret = bin2hex(random_bytes(32));
        $stmt = $db->prepare("INSERT INTO system_config (config_key, config_value) VALUES (?, ?)");
        $stmt->execute(['signed_url_secret', $secret]);
        
        return $secret;
    }

    /**
     * Generate a signed URL with expiration
     * 
     * @param string $path The relative path (e.g., 'donate/receipt/uuid')
     * @param int $expiresIn Time in seconds until expiration (default: 3600)
     * @param array $params Additional query parameters to include
     * @return string The complete signed URL
     */
    public static function generate($path, $expiresIn = self::DEFAULT_EXPIRATION, $params = [])
    {
        $path = ltrim($path, '/');
        $expires = time() + $expiresIn;
        
        // Build query parameters
        $queryParams = array_merge($params, [
            'expires' => $expires
        ]);
        
        // Create signature payload: path + expires + params
        $payload = $path . '|' . $expires;
        if (!empty($params)) {
            ksort($params); // Sort for consistent signatures
            $payload .= '|' . http_build_query($params);
        }
        
        // Generate HMAC signature
        $signature = hash_hmac('sha256', $payload, self::getSecretKey());
        $queryParams['signature'] = $signature;
        
        // Build full URL
        return url($path . '?' . http_build_query($queryParams));
    }

    /**
     * Verify a signed URL from the current request
     * 
     * @param string|null $requestUri Optional URI to verify (defaults to current request)
     * @return array|false Returns ['valid' => true, 'path' => string] on success, or error array on failure
     */
    public static function verify($requestUri = null)
    {
        // Parse current request if not provided
        if ($requestUri === null) {
            $requestUri = $_SERVER['REQUEST_URI'] ?? '';
        }

        $urlParts = parse_url($requestUri);
        $path = ltrim($urlParts['path'] ?? '', '/');
        
        // Remove /public/ prefix if present (adjusted for rewrite rules)
        $path = preg_replace('#^public/#', '', $path);
        
        parse_str($urlParts['query'] ?? '', $queryParams);
        
        // Extract required parameters, falling back to $_GET for environments where REQUEST_URI strips query strings
        $signature = $queryParams['signature'] ?? $_GET['signature'] ?? '';
        $expires = $queryParams['expires'] ?? $_GET['expires'] ?? '';
        
        if (empty($signature) || empty($expires)) {
            return [
                'valid' => false,
                'error' => 'missing_parameters',
                'message' => 'Signed URL parameters are missing'
            ];
        }

        // Remove signature from params for verification
        unset($queryParams['signature']);
        unset($queryParams['expires']);

        // Check expiration
        if (time() > (int)$expires) {
            return [
                'valid' => false,
                'error' => 'expired',
                'message' => 'This link has expired. Please request a new one.'
            ];
        }

        // Rebuild payload
        $payload = $path . '|' . $expires;
        if (!empty($queryParams)) {
            ksort($queryParams);
            $payload .= '|' . http_build_query($queryParams);
        }

        // Verify signature using timing-safe comparison
        $expectedSignature = hash_hmac('sha256', $payload, self::getSecretKey());
        
        if (!hash_equals($expectedSignature, $signature)) {
            return [
                'valid' => false,
                'error' => 'invalid_signature',
                'message' => 'The link signature is invalid. The URL may have been tampered with.'
            ];
        }

        // Success
        return [
            'valid' => true,
            'path' => $path,
            'expires_at' => (int)$expires,
            'params' => $queryParams
        ];
    }

    /**
     * Verify signed URL and redirect with error if invalid
     * Use this in controllers for automatic validation with user-friendly error handling
     * 
     * @param string|null $redirectUrl URL to redirect to on error (default: home)
     * @return array Valid URL data if successful (doesn't return on failure - redirects instead)
     */
    public static function verifyOrFail($redirectUrl = null)
    {
        $result = self::verify();
        
        if (!$result['valid']) {
            $_SESSION['error'] = $result['message'] ?? 'Invalid or expired link';
            $redirect = $redirectUrl ?? url('/');
            header('Location: ' . $redirect);
            exit;
        }
        
        return $result;
    }

    /**
     * Generate a signed URL for a receipt by UUID
     * 
     * @param string $uuid Receipt UUID
     * @param int $expiresIn Expiration time in seconds (default: 24 hours)
     * @return string Signed URL
     */
    public static function generateReceiptUrl($uuid, $expiresIn = 86400)
    {
        return self::generate("donate/receipt/{$uuid}", $expiresIn);
    }

    /**
     * Generate a signed URL for a certificate download
     * 
     * @param int $certificateId Certificate ID
     * @param int $expiresIn Expiration time in seconds (default: 1 hour)
     * @return string Signed URL
     */
    public static function generateCertificateUrl($certificateId, $expiresIn = 3600)
    {
        return self::generate("admin/careers/download-certificate/{$certificateId}", $expiresIn);
    }

    /**
     * Generate a signed URL for admin receipt download
     * 
     * @param int $donationId Donation ID
     * @param int $expiresIn Expiration time in seconds (default: 1 hour)
     * @return string Signed URL
     */
    public static function generateAdminReceiptUrl($donationId, $expiresIn = 3600)
    {
        return self::generate("admin/finance/donations/receipt/{$donationId}", $expiresIn);
    }

    /**
     * Check if a URL is expired without full verification
     * Useful for quick checks before expensive operations
     * 
     * @param string|null $requestUri Optional URI to check
     * @return bool True if expired
     */
    public static function isExpired($requestUri = null)
    {
        if ($requestUri === null) {
            $requestUri = $_SERVER['REQUEST_URI'] ?? '';
        }

        $urlParts = parse_url($requestUri);
        parse_str($urlParts['query'] ?? '', $queryParams);
        
        $expires = $queryParams['expires'] ?? 0;
        return time() > (int)$expires;
    }

    /**
     * Get remaining time in seconds before URL expires
     * 
     * @param string|null $requestUri Optional URI to check
     * @return int Seconds remaining (0 if expired or invalid)
     */
    public static function getTimeRemaining($requestUri = null)
    {
        if ($requestUri === null) {
            $requestUri = $_SERVER['REQUEST_URI'] ?? '';
        }

        $urlParts = parse_url($requestUri);
        parse_str($urlParts['query'] ?? '', $queryParams);
        
        $expires = $queryParams['expires'] ?? 0;
        $remaining = (int)$expires - time();
        
        return max(0, $remaining);
    }

    /**
     * Extract the original path from a signed URL
     * 
     * @param string $url The full signed URL
     * @return string|null The extracted path or null if not parseable
     */
    public static function extractPathFromUrl($url)
    {
        $parts = parse_url($url);
        return $parts['path'] ?? null;
    }
}
