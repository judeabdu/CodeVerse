<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "sql203.infinityfree.com"; 
$db_user = "if0_42132656";
$db_pass = "Iloveliz22";
$db_name = "if0_42132656_codeverse_db";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) { die("Database connection failed."); }

$email = "abdushakuluhssenkindu@gmail.com";

// 1. Clear premium purchases for this email
$sql1 = "DELETE FROM course_purchases WHERE email = '$email'";
$conn->query($sql1);

// 2. Clear trial registrations for this email
$sql2 = "DELETE FROM course_registrations WHERE email = '$email'";
$conn->query($sql2);

echo "<h3>Success! Test rows cleared for $email.</h3>";
echo "<p><a href='dashboard.php?email=" . urlencode($email) . "'>Return to Dashboard</a></p>";

$conn->close();
?>