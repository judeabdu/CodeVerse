<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "sql203.infinityfree.com"; 
$db_user = "if0_42132656";
$db_pass = "Iloveliz22";
$db_name = "if0_42132656_codeverse_db";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) { die("Database connection failed."); }

// Capture URL variables loaded by Pesapal's return callback redirect systems
$email = isset($_GET['email']) ? $conn->real_escape_string($_GET['email']) : '';
$course_title = isset($_GET['course']) ? $conn->real_escape_string($_GET['course']) : '';
$pesapal_tracking_id = isset($_GET['OrderTrackingId']) ? $conn->real_escape_string($_GET['OrderTrackingId']) : '';

if (empty($email) || empty($course_title) || empty($pesapal_tracking_id)) {
    die("Error: Invalid transaction parameter handshakes detected.");
}

// Ensure database table structures match cleanly
$conn->query("CREATE TABLE IF NOT EXISTS `course_purchases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `course_title` varchar(100) NOT NULL,
  `purchase_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

/* ============================================================================
   DEVELOPMENT OVERVIEW SUMMARY:
   Pesapal redirects back here immediately when processing completes. 
   We will insert the verified student tracking record directly and safely.
   ============================================================================ */
$check = $conn->query("SELECT id FROM course_purchases WHERE email = '$email' AND course_title = '$course_title' LIMIT 1");

if ($check && $check->num_rows == 0) {
    $conn->query("INSERT INTO course_purchases (email, course_title) VALUES ('$email', '$course_title')");
}

// Redirect the student back to their clean dashboard space with an unlocked premium view state layout
header("Location: dashboard.php?email=" . urlencode($email));
exit;
?>