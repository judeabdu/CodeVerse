<?php
// initiate_payment.php
require_once 'PesapalGateway.php';

// Get parameters from URL redirection string safely
$email = isset($_GET['email']) ? $_GET['email'] : '';
$course = isset($_GET['course']) ? $_GET['course'] : '';

if (empty($email) || empty($course)) {
    die("Error: Missing execution parameters.");
}

// FIXED: Your actual sandbox details from your Pesapal notification email
$consumer_key = "qoqgSL4l6tJYVK4jEnTHcLNd4M80LvEm";
$consumer_secret = "r+Aaj0wjToeAONJuaAvoUssfQMw=";

// Fire up the helper engine
$pesapal = new PesapalGateway($consumer_key, $consumer_secret, false);

$callback_url = "http://" . $_SERVER['HTTP_HOST'] . "/verify_payment.php?email=" . urlencode($email) . "&course=" . urlencode($course);

$response = $pesapal->generateCheckoutUrl(
    14.00, 
    "CodeVerse Academy - Premium: " . $course, 
    $callback_url, 
    $email, 
    $course
);

if ($response['success']) {
    // Send them straight to mobile money window
    header("Location: " . $response['redirect_url']);
    exit;
} else {
    // Return structured clean JSON if called natively
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}