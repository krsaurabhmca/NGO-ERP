<?php
$payload = '{
    "data": {
        "order": {
            "order_id": "DON_1790869794_ae7d8094",
            "order_amount": 1,
            "order_currency": "INR",
            "order_tags": null,
            "order_note": null
        },
        "payment": {
            "cf_payment_id": "6631258047",
            "payment_status": "SUCCESS",
            "payment_amount": 1,
            "payment_currency": "INR",
            "payment_message": "00::TRANSACTION HAS BEEN APPROVED",
            "payment_time": "2026-10-01T21:19:59+05:30",
            "bank_reference": "035792284605",
            "auth_id": null,
            "payment_method": {
                "upi": {
                    "channel": "qrcode",
                    "upi_id": "kanhaiyalalsinghk95@ybl",
                    "upi_payer_ifsc": null,
                    "upi_payer_account_number": null,
                    "upi_instrument": "UPI",
                    "upi_instrument_number": null
                }
            },
            "payment_group": "upi",
            "international_payment": null,
            "payment_surcharge": {
                "payment_surcharge_service_charge": 0,
                "payment_surcharge_service_tax": 0
            }
        },
        "customer_details": {
            "customer_name": "KUMAR SAURABH",
            "customer_id": "CUST_1790869794",
            "customer_email": "myofferplant@gmail.com",
            "customer_phone": "9431426600"
        },
        "payment_gateway_details": {
            "gateway_name": "CASHFREE",
            "gateway_order_id": null,
            "gateway_payment_id": null,
            "gateway_status_code": null,
            "gateway_order_reference_id": null,
            "gateway_settlement": "CASHFREE",
            "gateway_reference_name": null
        },
        "payment_offers": null,
        "terminal_details": null
    },
    "event_time": "2026-10-01T21:20:07+05:30",
    "type": "PAYMENT_SUCCESS_WEBHOOK"
}';

$ch = curl_init('http://ngo.test/webhook/cashfree'); // using ngo.test assuming laragon standard, or fallback to localhost
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'x-idempotency-key: +Xk6Cihk8RwCK/yfumGlkmF+63XbKacjWdF0B+JLQBM=',
    'x-webhook-signature: oqKUiZQPw4eBk+lWWBjOBZGLrWmtUDTfPEuQ5fkI5x0=',
    'x-webhook-timestamp: 1790869807144',
    'x-webhook-version: 2026-01-01'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP CODE: " . $httpCode . "\n";
echo "RESPONSE: " . $response . "\n";
