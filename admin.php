<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "sql203.infinityfree.com"; 
$db_user = "if0_42132656";
$db_pass = "Iloveliz22";
$db_name = "if0_42132656_codeverse_db";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) { die("Database connection failed."); }

// Handle Dynamic Form Addition to add lessons directly through the UI
$message = "";
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_lesson'])) {
    $c_title = $conn->real_escape_string($_POST['course_title']);
    $chap = (int)$_POST['chapter_num'];
    $order = (int)$_POST['lesson_order'];
    $title = $conn->real_escape_string($_POST['lesson_title']);
    $v_url = $conn->real_escape_string($_POST['video_url']);
    $w_content = $conn->real_escape_string($_POST['written_content']);

    $insert_sql = "INSERT INTO course_lessons (course_title, chapter_num, lesson_order, lesson_title, video_url, written_content) 
                   VALUES ('$c_title', $chap, $order, '$title', '$v_url', '$w_content')";
    
    if($conn->query($insert_sql)) {
        $message = "<p style='color:#4ade80; margin-bottom:20px;'>✨ Lesson saved and synchronized successfully!</p>";
    } else {
        $message = "<p style='color:#f87171; margin-bottom:20px;'>Error processing syntax entry: " . $conn->error . "</p>";
    }
}

// FIXED: Exact authorized curriculum catalog keys matched from your courses.php file layout
$course_options = [
    "HTML5 & CSS3 Masterclass",
    "Advanced JavaScript (ES6+)",
    "Full-Stack PHP & MySQL",
    "Python Systems & Automation",
    "AI & Machine Learning",
    "React & Next.js",
    "Node.js & Express",
    "Rust Programming"
];

// Aggregate Performance Metrics Indicators
$total_students = $conn->query("SELECT COUNT(DISTINCT email) as total FROM course_registrations")->fetch_assoc()['total'] ?? 0;
$premium_sales = $conn->query("SELECT COUNT(id) as total FROM course_purchases")->fetch_assoc()['total'] ?? 0;
$gross_revenue = $conn->query("SELECT SUM(amount_paid) as total FROM course_purchases")->fetch_assoc()['total'] ?? 0.00;

// Fetch Live Logs Lists
$trial_logs = $conn->query("SELECT * FROM course_registrations ORDER BY id DESC LIMIT 5");
$sales_logs = $conn->query("SELECT * FROM course_purchases ORDER BY id DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Control Console - CodeVerse Engine</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'Segoe UI', sans-serif; background:#0b0f19; color:white; padding:40px 20px;}
.container{max-width:1100px; margin:auto;}

h1, h2, h3{margin-bottom:15px;}
.grid-stats{display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:20px; margin-bottom:40px;}
.stat-card{background:#111827; border:1px solid rgba(255,255,255,0.06); padding:25px; border-radius:12px;}
.stat-card h3{font-size:13px; color:#64748b; text-transform:uppercase; letter-spacing:1px;}
.stat-card p{font-size:32px; font-weight:800; color:#00f2fe;}

.section-box{background:#111827; border:1px solid rgba(255,255,255,0.06); padding:30px; border-radius:12px; margin-bottom:40px;}
table{width:100%; border-collapse:collapse; margin-top:15px;}
th, td{text-align:left; padding:12px; border-bottom:1px solid rgba(255,255,255,0.04); font-size:14px;}
th{color:#64748b; font-weight:600;}
td{color:#cbd5e1;}

/* Curriculum Intake Interface Controls Form styling */
.form-group{margin-bottom:15px; display:flex; flex-direction:column; gap:6px;}
input, textarea, select{background:#1f2937; border:1px solid rgba(255,255,255,0.1); border-radius:6px; padding:12px; color:white; font-family:inherit; font-size:14px;}
input:focus, textarea:focus, select:focus{border-color:#00f2fe; outline:none;}
select option{background:#111827; color:white;}
.submit-btn{background:#22c55e; color:white; font-weight:bold; border:none; padding:14px; border-radius:6px; cursor:pointer; font-size:14px; transition:0.2s;}
.submit-btn:hover{background:#16803d;}
</style>
</head>
<body>

<div class="container">
    <h1 style="font-size:2.2rem; margin-bottom:5px;">CodeVerse Control Console</h1>
    <p style="color:#64748b; margin-bottom:40px;">Platform execution health check index metrics reporting matrix.</p>

    <div class="grid-stats">
        <div class="stat-card">
            <h3>Registered Trial Accounts</h3>
            <p><?php echo $total_students; ?></p>
        </div>
        <div class="stat-card">
            <h3>Premium Purchases</h3>
            <p><?php echo $premium_sales; ?></p>
        </div>
        <div class="stat-card">
            <h3>Gross Pipeline Revenue</h3>
            <p>$<?php echo number_format($gross_revenue, 2); ?> <span>USD</span></p>
        </div>
    </div>

    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(480px, 1fr)); gap:30px; margin-bottom:40px;">
        <div class="section-box">
            <h2>Recent Free Trial Activations</h2>
            <table>
                <tr><th>User Identity Email</th><th>Syllabus Course Target</th><th>Date Timestamp</th></tr>
                <?php while($trial_logs && $r = $trial_logs->fetch_assoc()): ?>
                    <tr><td><?php echo $r['email']; ?></td><td><?php echo $r['course_title']; ?></td><td><?php echo $r['registration_date']; ?></td></tr>
                <?php endwhile; ?>
            </table>
        </div>

        <div class="section-box">
            <h2>Recent Gateway Premium Invoices</h2>
            <table>
                <tr><th>User Identity Email</th><th>Syllabus Track</th><th>Reference Receipt</th></tr>
                <?php while($sales_logs && $p = $sales_logs->fetch_assoc()): ?>
                    <tr><td><?php echo $p['email']; ?></td><td><?php echo $p['course_title']; ?></td><td style="color:#4ade80; font-family:monospace;"><?php echo $p['payment_ref']; ?></td></tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>

    <div class="section-box" style="max-width:650px;">
        <h2>Curriculum Module Intake Form</h2>
        <p style="color:#64748b; font-size:13px; margin-bottom:20px;">Inject new specialized content directly into student workspaces.</p>
        <?php echo $message; ?>
        
        <form method="POST" action="admin.php">
            <div class="form-group">
                <label>Select Target Course Track</label>
                <select name="course_title" required>
                    <option value="" disabled selected>-- Choose a Course Track --</option>
                    <?php foreach($course_options as $option): ?>
                        <option value="<?php echo htmlspecialchars($option); ?>">
                            <?php echo htmlspecialchars($option); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:15px;">
                <div class="form-group">
                    <label>Chapter Group Number</label>
                    <input type="number" name="chapter_num" placeholder="1" required>
                </div>
                <div class="form-group">
                    <label>Internal Chapter Ordering Index</label>
                    <input type="number" name="lesson_order" placeholder="1" required>
                </div>
            </div>
            <div class="form-group">
                <label>Lesson UI Heading Title</label>
                <input type="text" name="lesson_title" placeholder="e.g. Setting up MySQL Foreign Constraints" required>
            </div>
            <div class="form-group">
                <label>Video Stream URL Embed Identifier (Optional)</label>
                <input type="text" name="video_url" placeholder="https://www.youtube.com/embed/VIDEO_ID_HERE">
            </div>
            <div class="form-group">
                <label>Written Curriculum Reading Log Docs Module (Markdown/HTML format safe)</label>
                <textarea name="written_content" rows="6" placeholder="Type out your reading materials, assignment requirements, or step-by-step documentation here..."></textarea>
            </div>
            <button type="submit" name="add_lesson" class="submit-btn">Publish Content Unit to Workspace</button>
        </form>
    </div>
</div>

</body>
</html>