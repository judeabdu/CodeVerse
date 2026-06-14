<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "sql203.infinityfree.com"; 
$db_user = "if0_42132656";
$db_pass = "Iloveliz22";
$db_name = "if0_42132656_codeverse_db";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);

    // Check if user already registered to prevent duplicates
    $check = $conn->query("SELECT registration_date FROM course_registrations WHERE email = '$email' LIMIT 1");
    
    if($check->num_rows > 0) {
        // Already registered? Send them straight to their dashboard
        header("Location: dashboard.php?email=" . urlencode($email));
        exit();
    } else {
        // New user? Log their registration time
        $sql = "INSERT INTO course_registrations (username, email, course_title) VALUES ('$username', '$email', 'All Academy Access')";
        if ($conn->query($sql) === TRUE) {
            header("Location: dashboard.php?email=" . urlencode($email));
            exit();
        } else {
            $msg = "Error creating your trial account. Try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Start Your Free Trial - CodeVerse Academy</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:Segoe UI, Arial, sans-serif;background:#0b0f19;color:white;display:flex;justify-content:center;align-items:center;min-height:100vh;}
        .form-card{background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);padding:40px;border-radius:15px;width:100%;max-width:400px;text-align:center;}
        h2{background:linear-gradient(135deg,#ffffff,#00f2fe);-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:10px;}
        p{color:#94a3b8;font-size:14px;margin-bottom:25px;}
        input{width:100%;padding:12px;margin-bottom:20px;background:#161b26;border:1px solid rgba(255,255,255,0.1);border-radius:6px;color:white;font-size:16px;}
        input:focus{border-color:#00f2fe;outline:none;}
        .submit-btn{width:100%;padding:14px;background:linear-gradient(135deg,#007bff,#00f2fe);border:none;border-radius:6px;color:white;font-weight:bold;font-size:16px;cursor:pointer;transition:0.3s;}
        .submit-btn:hover{transform:translateY(-2px);box-shadow:0 5px 15px rgba(0,242,254,0.3);}
    </style>
</head>
<body>
    <div class="form-card">
        <h2>Create Free Account</h2>
        <p>Get unlimited access to all courses for the next 3 days.</p>
        <?php if($msg) echo "<p style='color:red;'>$msg</p>"; ?>
        <form action="register.php" method="POST">
            <input type="text" name="username" placeholder="Your Full Name" required>
            <input type="email" name="email" placeholder="Your Email Address" required>
            <button type="submit" class="submit-btn">Activate 3-Day Free Campus Access</button>
        </form>
    </div>
</body>
</html>