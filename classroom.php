<?php
// classroom.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$host = "sql203.infinityfree.com"; 
$db_user = "if0_42132656";
$db_pass = "Iloveliz22";
$db_name = "if0_42132656_codeverse_db";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

$course = isset($_GET['course']) ? htmlspecialchars($_GET['course']) : "";
$email = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : "";

if(empty($course) || empty($email)) {
    die("Access Denied. Please register first.");
}

// Fetch registration time
$sql = "SELECT registration_date FROM course_registrations WHERE email = '$email' AND course_title = '$course' ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $reg_time = strtotime($row['registration_date']);
    $current_time = time();
    
    // Calculate difference in seconds (3 days = 3 * 24 * 60 * 60 = 259,200 seconds)
    $seconds_passed = $current_time - $reg_time;
    $three_days_in_seconds = 259200; 

    if ($seconds_passed > $three_days_in_seconds) {
        $trial_expired = true;
    } else {
        $trial_expired = false;
        // Calculate remaining hours for a premium user feel
        $seconds_left = $three_days_in_seconds - $seconds_passed;
        $hours_left = ceil($seconds_left / 3600);
    }
} else {
    die("No active trial found for this account.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $course; ?> Portal</title>
    <style>
        body { font-family: Segoe UI, Arial, sans-serif; background: #0b0f19; color: white; padding: 50px 20px; text-align: center; }
        .panel { max-width: 600px; margin: auto; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); padding: 40px; border-radius: 15px; }
        .pay-box { background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; border-radius: 10px; padding: 30px; margin-top: 20px; }
        .pay-btn { display: inline-block; padding: 14px 30px; background: #ef4444; color: white; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 20px; transition: 0.3s; }
        .pay-btn:hover { background: #dc2626; box-shadow: 0 5px 15px rgba(239, 68, 68, 0.4); }
        .badge { background: rgba(20, 184, 166, 0.2); color: #2dd4bf; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
    </style>
</head>
<body>

<div class="panel">
    <?php if($trial_expired): ?>
        <div class="pay-box">
            <h2 style="color: #f87171;">⏳ Your 3-Day Free Trial Has Expired</h2>
            <p style="color: #94a3b8; margin-top: 10px;">To unlock full, lifetime access to the <strong><?php echo $course; ?></strong> dynamic curriculum, source files, and developer community, settle your one-time tuition payment.</p>
            <div style="font-size: 32px; font-weight: 800; margin: 20px 0; color: white;">$100.00 USD</div>
            <a href="#" class="pay-btn">Complete Tuition Payment</a>
        </div>
    <?php else: ?>
        <span class="badge">🔥 Active Trial: <?php echo $hours_left; ?> Hours Remaining</span>
        <h1 style="margin-top: 15px;"><?php echo $course; ?> Classroom</h1>
        <p style="color: #94a3b8; margin-bottom: 40px;">Welcome to your premium dashboard. Dive into your lessons below.</p>
        
        <div style="text-align: left; background: rgba(255,255,255,0.02); padding: 20px; border-radius: 8px; border-left: 4px solid #00f2fe;">
            <h3>Module 1: Environmental Setup & Syntax Architecture</h3>
            <p style="font-size: 14px; margin: 5px 0 0 0;">Status: Ready to stream</p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>