<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

$host = "sql203.infinityfree.com"; 
$db_user = "if0_42132656";
$db_pass = "Iloveliz22";
$db_name = "if0_42132656_codeverse_db";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) { 
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

// Get the raw JSON post data
$data = json_encode($_POST); // fallback check
$input = json_decode(file_get_contents('php://input'), true);

$email = isset($input['email']) ? $conn->real_escape_string($input['email']) : '';
$course_title = isset($input['course_title']) ? $conn->real_escape_string($input['course_title']) : '';

if (empty($email) || empty($course_title)) {
    echo json_encode(['success' => false, 'message' => 'Missing student details or course title.']);
    exit;
}

// Double check if they already activated this trial to prevent duplicate entries
$check_sql = "SELECT id FROM course_registrations WHERE email = '$email' AND course_title = '$course_title' LIMIT 1";
$check_result = $conn->query($check_sql);

if ($check_result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Trial already activated for this track.']);
    exit;
}

// Insert the row into MySQL to start the 3-day timer countdown
$sql = "INSERT INTO course_registrations (username, email, course_title, registration_date) VALUES ('Student', '$email', '$course_title', NOW())";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true, 'message' => 'Your 3-day trial has officially started!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
}
?>