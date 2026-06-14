<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "sql203.infinityfree.com"; 
$db_user = "if0_42132656";
$db_pass = "Iloveliz22";
$db_name = "if0_42132656_codeverse_db";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) { die("Database connection failed."); }

$email = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : "";
$course_title = isset($_GET['course']) ? htmlspecialchars($_GET['course']) : "";

if(empty($email) || empty($course_title)) {
    die("Access Denied. Missing parameters. Return to the dashboard.");
}

// Security Gate Verification Check
$three_days_in_seconds = 259200; 
$current_time = time();
$is_authorized = false;

// 🚀 MASTER DEVELOPER BYPASS GATE
// Only excludes your test email. All other standard users must still have an active trial or pay!
if (trim(strtolower($email)) === 'abdushakulhussenkindu@gmail.com') {
    $is_authorized = true;
} else {
    // Check 1: Is premium paid? (For normal users)
    $paid_sql = "SELECT id FROM course_purchases WHERE email = '" . $conn->real_escape_string($email) . "' AND course_title = '" . $conn->real_escape_string($course_title) . "' LIMIT 1";
    $paid_result = $conn->query($paid_sql);

    if($paid_result && $paid_result->num_rows > 0) {
        $is_authorized = true;
    } else {
        // Check 2: Is active trial running? (For normal users)
        $trial_sql = "SELECT registration_date FROM course_registrations WHERE email = '" . $conn->real_escape_string($email) . "' AND course_title = '" . $conn->real_escape_string($course_title) . "' ORDER BY id DESC LIMIT 1";
        $trial_result = $conn->query($trial_sql);
        
        if($trial_result && $trial_result->num_rows > 0) {
            $row = $trial_result->fetch_assoc();
            $reg_time = strtotime($row['registration_date']);
            if (($current_time - $reg_time) <= $three_days_in_seconds) {
                $is_authorized = true;
            }
        }
    }
}

if(!$is_authorized) {
    header("Location: dashboard.php?email=" . urlencode($email) . "&status=expired");
    exit;
}

// Fetch all curriculum units for this specific course track
$lessons_sql = "SELECT * FROM course_lessons WHERE course_title = '" . $conn->real_escape_string($course_title) . "' ORDER BY chapter_num ASC, lesson_order ASC";
$lessons_result = $conn->query($lessons_sql);

$lessons = [];
if($lessons_result && $lessons_result->num_rows > 0) {
    while($row = $lessons_result->fetch_assoc()) {
        $lessons[] = $row;
    }
}

// Figure out what active lesson should render first
$selected_id = isset($_GET['lesson_id']) ? (int)$_GET['lesson_id'] : 0;
$active_lesson = null;

if(count($lessons) > 0) {
    $active_lesson = $lessons[0]; // default to first item
    foreach($lessons as $l) {
        if($l['id'] == $selected_id) {
            $active_lesson = $l;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $course_title; ?> - CodeVerse Classroom</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'Segoe UI', sans-serif; background:#0b0f19; color:white; height:100vh; display:flex; flex-direction:column; overflow:hidden;}

/* Upper Ribbon Info Header */
.top-navbar{height:70px; background:#111827; border-bottom:1px solid rgba(255,255,255,0.06); display:flex; justify-content:space-between; align-items:center; padding:0 30px; z-index:10;}
.nav-back{color:#00f2fe; text-decoration:none; font-weight:600; font-size:14px; display:flex; align-items:center; gap:8px;}
.nav-back:hover{text-decoration:underline;}

/* Main Screen Workspace Splits */
.workspace{display:flex; flex-grow:1; height:calc(100vh - 70px); position:relative;}

/* Left Sidebar Navigation Menu */
.sidebar{width:340px; background:#111827; border-right:1px solid rgba(255,255,255,0.06); display:flex; flex-direction:column; height:100%; overflow-y:auto;}
.sidebar-header{padding:20px; font-weight:bold; font-size:15px; border-bottom:1px solid rgba(255,255,255,0.04); color:#94a3b8;}
.lesson-item{padding:16px 20px; border-bottom:1px solid rgba(255,255,255,0.02); cursor:pointer; display:flex; flex-direction:column; gap:4px; text-decoration:none; color:inherit; transition:0.2s;}
.lesson-item:hover{background:rgba(255,255,255,0.02);}
.lesson-item.active{background:rgba(0,242,254,0.06); border-left:4px solid #00f2fe;}
.lesson-item h4{font-size:14px; color:#f1f5f9;}
.lesson-item span{font-size:12px; color:#64748b;}

/* Right Primary Content Window Frame */
.content-viewer{flex-grow:1; height:100%; overflow-y:auto; padding:40px; display:flex; flex-direction:column; gap:30px;}
.video-wrapper{position:relative; width:100%; max-width:850px; aspect-ratio:16/9; background:#000; border-radius:12px; overflow:hidden; border:1px solid rgba(255,255,255,0.06);}
.video-wrapper iframe{position:absolute; top:0; left:0; width:100%; height:100%; border:0;}
.doc-card{background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.05); border-radius:12px; padding:35px; max-width:850px; line-height:1.7; color:#cbd5e1;}
.doc-card h2{color:white; margin-bottom:20px; font-size:24px;}
.empty-state{margin:auto; text-align:center; color:#64748b; font-size:15px;}
</style>
</head>
<body>

<div class="top-navbar">
    <div>
        <h3 style="font-size:1.2rem; font-weight:700;"><?php echo htmlspecialchars($course_title); ?></h3>
        <p style="font-size:12px; color:#64748b;">Student Session: <?php echo $email; ?></p>
    </div>
    <a href="dashboard.php?email=<?php echo urlencode($email); ?>" class="nav-back">← Back to Dashboard</a>
</div>

<div class="workspace">
    <div class="sidebar">
        <div class="sidebar-header">COURSE SYLLABUS LESSONS</div>
        <?php if(count($lessons) > 0): ?>
            <?php foreach($lessons as $index => $lesson): 
                $isActive = ($lesson['id'] == $active_lesson['id']);
            ?>
                <a href="study.php?email=<?php echo urlencode($email); ?>&course=<?php echo urlencode($course_title); ?>&lesson_id=<?php echo $lesson['id']; ?>" 
                   class="lesson-item <?php echo $isActive ? 'active' : ''; ?>">
                    <h4><?php echo htmlspecialchars($lesson['lesson_title']); ?></h4>
                    <span>Chapter <?php echo $lesson['chapter_num']; ?> • Sequence <?php echo $lesson['lesson_order']; ?></span>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="padding:20px; color:#64748b; font-size:13px;">No curriculum items compiled inside this track yet.</p>
        <?php endif; ?>
    </div>

    <div class="content-viewer">
        <?php if($active_lesson): ?>
            <?php if(!empty($active_lesson['video_url'])): ?>
                <div class="video-wrapper">
                    <iframe src="<?php echo htmlspecialchars($active_lesson['video_url']); ?>" allowfullscreen></iframe>
                </div>
            <?php endif; ?>
            
            <div class="doc-card">
                <h2><?php echo htmlspecialchars($active_lesson['lesson_title']); ?></h2>
                <div>
                    <?php echo nl2br($active_lesson['written_content'] ?? 'No textual reading logs matched for this training block module entry.'); ?>
                </div>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <p>Select an accessible lesson sequence row block from the sidebar playlist matrix to begin layout calculations.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>