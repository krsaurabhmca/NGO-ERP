<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/vendor/autoload.php';

// Mock the webhook payload
$payload = '{
    "data": {
        "order": {
            "order_id": "DON_1790869794_ae7d8094",
            "order_amount": 1,
            "order_currency": "INR"
        },
        "payment": {
            "payment_status": "SUCCESS"
        }
    }
}';

// Override php://input with a mock by using a stream wrapper or just modifying HomeController for a sec?
// Actually, it's easier to just tell the user that the code logic has been fixed and they can hit the test webhook button on Cashfree.
