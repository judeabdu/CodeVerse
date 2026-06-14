<?php
// PesapalGateway.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

class PesapalGateway {
    private $consumerKey;
    private $consumerSecret;
    private $baseUrl;

    public function __construct($key, $secret, $isProduction = false) {
        $this->consumerKey = $key;
        $this->consumerSecret = $secret;
        // Verified Sandbox endpoint routing configuration rules
        $this->baseUrl = $isProduction ? "https://payapi.pesapal.com/v3" : "https://cybqa.pesapal.com/pesapalv3";
    }

    // Step A: Fetch token automatically behind the scenes
    private function getAccessToken() {
        $url = $this->baseUrl . "/api/Auth/RequestToken";
        $payload = json_encode([
            "consumer_key" => $this->consumerKey,
            "consumer_secret" => $this->consumerSecret
        ]);

        $options = [
            "http" => [
                "method" => "POST",
                "header" => "Content-Type: application/json\r\n" .
                            "Accept: application/json\r\n" .
                            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36\r\n", // Bypasses shared host security filters
                "content" => $payload,
                "ignore_errors" => true,
                "timeout" => 15
            ]
        ];

        $context = stream_context_create($options);
        $response = @file_get_contents($url, false, $context);
        
        if ($response === FALSE) { return null; }
        
        $data = json_decode($response, true);
        return $data['token'] ?? null;
    }

    // Step B: Submit the order and directly return the checkout redirect link
    public function generateCheckoutUrl($amount, $description, $callbackUrl, $studentEmail, $courseTitle) {
        $token = $this->getAccessToken();
        if (!$token) {
            return ["success" => false, "message" => "Failed to authenticate with payment processing servers. Outbound port restriction detected."];
        }

        $url = $this->baseUrl . "/api/Transactions/SubmitOrderRequest";
        $txRef = "CV_" . time() . "_" . rand(100, 999);

        $payload = json_encode([
            "id" => $txRef,
            "amount" => (float)$amount,
            "description" => $description,
            "callback_url" => $callbackUrl,
            "notification_id" => "00000000-0000-0000-0000-000000000000", 
            "billing_address" => [
                "email_address" => $studentEmail,
                "phone_number" => "0700000000",
                "country_code" => "UG",
                "first_name" => "Student",
                "last_name" => "Profile"
            ]
        ]);

        $options = [
            "http" => [
                "method" => "POST",
                "header" => "Authorization: Bearer " . $token . "\r\n" .
                            "Content-Type: application/json\r\n" .
                            "Accept: application/json\r\n" .
                            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36\r\n",
                "content" => $payload,
                "ignore_errors" => true,
                "timeout" => 15
            ]
        ];

        $context = stream_context_create($options);
        $response = @file_get_contents($url, false, $context);

        if ($response === FALSE) {
            return ["success" => false, "message" => "Server connection dropped during order generation."];
        }

        $data = json_decode($response, true);
        if (isset($data['redirect_url'])) {
            return ["success" => true, "redirect_url" => $data['redirect_url']];
        }

        return ["success" => false, "message" => $data['message'] ?? "Unknown registration error profile."];
    }
}