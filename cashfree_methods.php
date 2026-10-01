    public function createCashfreeOrder()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            json_response(['status' => 'error', 'message' => 'Invalid request']);
            exit;
        }

        $mode = $this->globalSettings['cashfree_mode'] ?? 'test';
        if ($mode === 'live') {
            $appId = $this->globalSettings['cashfree_live_app_id'] ?? '';
            $secretKey = $this->globalSettings['cashfree_live_secret_key'] ?? '';
            $url = 'https://api.cashfree.com/pg/orders';
        } else {
            $appId = $this->globalSettings['cashfree_test_app_id'] ?? '';
            $secretKey = $this->globalSettings['cashfree_test_secret_key'] ?? '';
            $url = 'https://sandbox.cashfree.com/pg/orders';
        }

        if (empty($appId) || empty($secretKey)) {
            json_response(['status' => 'error', 'message' => 'Cashfree not configured.']);
            exit;
        }

        $amount = filter_var($_POST['amount'] ?? 0, FILTER_VALIDATE_FLOAT);
        if ($amount === false || $amount < 1) {
            json_response(['status' => 'error', 'message' => 'Invalid donation amount.']);
            exit;
        }
        if (defined('MAX_AMOUNT') && $amount > MAX_AMOUNT) {
            json_response(['status' => 'error', 'message' => 'Amount exceeds maximum allowed value.']);
            exit;
        }
        $orderId = 'DON_' . time() . '_' . bin2hex(random_bytes(4));

        $phone = preg_replace('/[^0-9]/', '', $_POST['donor_phone'] ?? '');
        $email = trim($_POST['donor_email'] ?? 'test@example.com');
        $name = trim($_POST['donor_name'] ?? 'Donor');
        if (empty($phone)) $phone = '9999999999';
        if (empty($email)) $email = 'test@example.com';

        $postData = [
            'order_id' => $orderId,
            'order_amount' => $amount,
            'order_currency' => 'INR',
            'customer_details' => [
                'customer_id' => 'CUST_' . time(),
                'customer_name' => $name,
                'customer_email' => $email,
                'customer_phone' => $phone
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'x-client-id: ' . $appId,
            'x-client-secret: ' . $secretKey,
            'x-api-version: 2023-08-01'
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($curlError) {
            json_response(['status' => 'error', 'message' => 'Payment service unavailable.']);
            exit;
        }

        if ($httpCode === 200 || $httpCode === 201) {
            $order = json_decode($response, true);
            
            $pan = strtoupper(trim($_POST['donor_pan'] ?? ''));
            $claim80g = !empty($_POST['claim_80g']);

            $pendingData = [
                'donor_name' => trim($_POST['donor_name'] ?? ''),
                'donor_email' => trim($_POST['donor_email'] ?? ''),
                'donor_phone' => preg_replace('/[^0-9]/', '', $_POST['donor_phone'] ?? ''),
                'donor_address' => trim($_POST['donor_address'] ?? ''),
                'donor_city' => trim($_POST['donor_city'] ?? ''),
                'donor_state' => trim($_POST['donor_state'] ?? ''),
                'donor_pincode' => preg_replace('/[^0-9]/', '', $_POST['donor_pincode'] ?? ''),
                'donor_pan' => $claim80g ? $pan : '',
                'amount' => $amount,
                'payment_method' => 'cashfree',
                'transaction_id' => $orderId,
                'member_id' => !empty($_POST['member_id']) ? (int)$_POST['member_id'] : null,
                'is_recurring' => !empty($_POST['is_recurring']) ? 1 : 0,
                'status' => 'pending'
            ];

            $donationModel = new Donation();
            $donationId = $donationModel->create($pendingData);

            if ($donationId) {
                $_SESSION['cashfree_donation_id'] = $donationId;
                $_SESSION['cashfree_order_amount'] = $amount;
                $_SESSION['cashfree_order_id'] = $orderId;
                
                json_response([
                    'status' => 'success',
                    'payment_session_id' => $order['payment_session_id'],
                    'order_id' => $orderId
                ]);
            } else {
                json_response(['status' => 'error', 'message' => 'Failed to initialize donation record.']);
            }
        } else {
            $err = json_decode($response, true);
            json_response(['status' => 'error', 'message' => $err['message'] ?? 'Failed to create Cashfree order.']);
        }
        exit;
    }

    public function verifyCashfreePayment()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            json_response(['status' => 'error', 'message' => 'Invalid request']);
            exit;
        }

        $mode = $this->globalSettings['cashfree_mode'] ?? 'test';
        if ($mode === 'live') {
            $appId = $this->globalSettings['cashfree_live_app_id'] ?? '';
            $secretKey = $this->globalSettings['cashfree_live_secret_key'] ?? '';
            $url = 'https://api.cashfree.com/pg/orders/';
        } else {
            $appId = $this->globalSettings['cashfree_test_app_id'] ?? '';
            $secretKey = $this->globalSettings['cashfree_test_secret_key'] ?? '';
            $url = 'https://sandbox.cashfree.com/pg/orders/';
        }

        $orderId = $_POST['cashfree_order_id'] ?? '';
        $expectedOrderId = $_SESSION['cashfree_order_id'] ?? null;

        if (empty($orderId) || $orderId !== $expectedOrderId) {
            json_response(['status' => 'error', 'message' => 'Invalid payment data or session expired.']);
            exit;
        }

        $ch = curl_init($url . $orderId);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'x-client-id: ' . $appId,
            'x-client-secret: ' . $secretKey,
            'x-api-version: 2023-08-01'
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            $orderData = json_decode($response, true);
            if (($orderData['order_status'] ?? '') === 'PAID') {
                $donationId = $_SESSION['cashfree_donation_id'] ?? null;
                if ($donationId) {
                    $donationModel = new Donation();
                    $donation = $donationModel->find($donationId);
                    if ($donation && $donation->status === 'pending') {
                        $donationModel->updateStatus($donationId, 'completed');
                        unset($_SESSION['cashfree_donation_id'], $_SESSION['cashfree_order_amount'], $_SESSION['cashfree_order_id']);
                        json_response([
                            'status' => 'success',
                            'message' => 'Payment verified successfully.',
                            'receipt_url' => url('donate/receipt/' . $donation->uuid)
                        ]);
                        exit;
                    }
                }
            }
        }
        json_response(['status' => 'error', 'message' => 'Payment verification failed.']);
        exit;
    }

    public function markCashfreeFailed()
    {
        $donationId = $_SESSION['cashfree_donation_id'] ?? null;
        if ($donationId) {
            $donationModel = new Donation();
            $donation = $donationModel->find($donationId);
            if ($donation && $donation->status === 'pending') {
                $donationModel->updateStatus($donationId, 'failed');
            }
        }
        unset($_SESSION['cashfree_donation_id'], $_SESSION['cashfree_order_amount'], $_SESSION['cashfree_order_id']);
        json_response(['status' => 'success']);
        exit;
    }
}
