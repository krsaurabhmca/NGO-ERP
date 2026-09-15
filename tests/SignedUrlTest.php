<?php

/**
 * Test suite for Signed URL functionality
 * Run this file directly: php tests/SignedUrlTest.php
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/config.php';

// Autoloader for core classes
spl_autoload_register(function ($class) {
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $file = __DIR__ . '/../' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Load SignedUrlHelper directly
require_once __DIR__ . '/../app/helpers/SignedUrlHelper.php';

class SignedUrlTest
{
    private $passed = 0;
    private $failed = 0;

    public function run()
    {
        echo "=== Running Signed URL Tests ===\n\n";

        $this->testBasicGeneration();
        $this->testValidation();
        $this->testExpiration();
        $this->testTampering();
        $this->testPurposeValidation();
        $this->testHelperFunctions();

        echo "\n=== Test Results ===\n";
        echo "Passed: {$this->passed}\n";
        echo "Failed: {$this->failed}\n";
        
        return $this->failed === 0;
    }

    private function assert($condition, $message)
    {
        if ($condition) {
            $this->passed++;
            echo "✓ {$message}\n";
        } else {
            $this->failed++;
            echo "✗ {$message}\n";
        }
    }

    private function testBasicGeneration()
    {
        echo "Test: Basic URL Generation\n";
        
        $url = SignedUrlHelper::generate('/donate/receipt/test-uuid');
        
        $this->assert(
            !empty($url),
            "Should generate a URL"
        );
        
        $parsed = parse_url($url);
        parse_str($parsed['query'] ?? '', $params);
        
        $this->assert(
            isset($params['expires']),
            "URL should contain expires parameter"
        );
        
        $this->assert(
            isset($params['signature']),
            "URL should contain signature parameter"
        );
        
        $this->assert(
            strlen($params['signature']) === 64,
            "Signature should be 64 characters (SHA256 hex)"
        );
        
        echo "\n";
    }

    private function testValidation()
    {
        echo "Test: URL Validation\n";
        
        // Generate a valid URL
        $url = SignedUrlHelper::generate('/donate/receipt/test-uuid', 3600);
        
        $this->assert(
            SignedUrlHelper::verify($url),
            "Should validate a freshly generated URL"
        );
        
        echo "\n";
    }

    private function testExpiration()
    {
        echo "Test: URL Expiration\n";
        
        // Generate a URL that expires in 1 second
        $url = SignedUrlHelper::generate('/donate/receipt/test-uuid', 1);
        
        $this->assert(
            SignedUrlHelper::verify($url),
            "Should be valid immediately after generation"
        );
        
        // Wait for expiration
        sleep(2);
        
        $this->assert(
            !SignedUrlHelper::verify($url),
            "Should be invalid after expiration"
        );
        
        echo "\n";
    }

    private function testTampering()
    {
        echo "Test: Tampering Detection\n";
        
        $url = SignedUrlHelper::generate('/donate/receipt/test-uuid');
        
        // Try to change the path
        $tamperedUrl = str_replace('test-uuid', 'different-uuid', $url);
        
        $this->assert(
            !SignedUrlHelper::verify($tamperedUrl),
            "Should reject URL with tampered path"
        );
        
        // Try to extend expiration
        $parsed = parse_url($url);
        parse_str($parsed['query'], $params);
        $params['expires'] = time() + 86400; // Add a day
        $newQuery = http_build_query($params);
        $tamperedUrl = $parsed['path'] . '?' . $newQuery;
        
        $this->assert(
            !SignedUrlHelper::verify($tamperedUrl),
            "Should reject URL with tampered expiration"
        );
        
        // Try to use wrong signature
        $parsed = parse_url($url);
        parse_str($parsed['query'], $params);
        $params['signature'] = hash('sha256', 'wrong-signature');
        $newQuery = http_build_query($params);
        $tamperedUrl = $parsed['path'] . '?' . $newQuery;
        
        $this->assert(
            !SignedUrlHelper::verify($tamperedUrl),
            "Should reject URL with wrong signature"
        );
        
        echo "\n";
    }

    private function testPurposeValidation()
    {
        echo "Test: Purpose-based Validation\n";
        
        $url = SignedUrlHelper::generate('/admin/download/file.pdf', 3600, 'download');
        
        $this->assert(
            SignedUrlHelper::verify($url, 'download'),
            "Should validate URL with correct purpose"
        );
        
        $this->assert(
            !SignedUrlHelper::verify($url, 'receipt'),
            "Should reject URL with wrong purpose"
        );
        
        echo "\n";
    }

    private function testHelperFunctions()
    {
        echo "Test: Helper Functions\n";
        
        // Test generate_signed_url()
        $url = generate_signed_url('/test/path', 3600);
        
        $this->assert(
            !empty($url),
            "generate_signed_url() should work"
        );
        
        // Test verify_signed_url()
        $this->assert(
            verify_signed_url($url),
            "verify_signed_url() should validate generated URL"
        );
        
        // Test extract_signed_url_path()
        $path = \App\Helpers\SignedUrlHelper::extractPathFromUrl($url);
        
        $this->assert(
            $path === '/test/path',
            "extract_signed_url_path() should extract original path"
        );
        
        echo "\n";
    }
}

// Run tests
$test = new SignedUrlTest();
$success = $test->run();
exit($success ? 0 : 1);
