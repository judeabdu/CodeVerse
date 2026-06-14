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

if(empty($email)) {
    die("Access Denied. Please register or log in to view course content.");
}

// 🚀 STAGE 1: FORCE CLEAN URL STATUS AT BACKEND CORE
// This strips out any 'expired' url flags before the HTML or JavaScript states can read it!
$clean_email = trim(strtolower($email));
if ($clean_email === 'abdushakulhussenkindu@gmail.com' || $clean_email === 'abdushakuluhssenkindu@gmail.com') {
    if (isset($_GET['status'])) {
        unset($_GET['status']); 
        $_GET['status'] = ''; 
    }
}

$three_days_in_seconds = 259200; 
$current_time = time();

// Clean tracking helper function with developer bypass
function getCourseStatus($conn, $email, $course_title, $three_days_in_seconds, $current_time) {
    // 🚀 STAGE 2: BACKEND LOGIC MASTER BYPASS
    // Forces every course row block to parse as paid for your administrative test email variants
    $check_email = trim(strtolower($email));
    if ($check_email === 'abdushakulhussenkindu@gmail.com' || $check_email === 'abdushakuluhssenkindu@gmail.com') {
        return ['status' => 'paid', 'hours' => 0];
    }

    $course_title = $conn->real_escape_string($course_title);
    
    // Check if they already bought the course permanently
    $paid_sql = "SELECT id FROM course_purchases WHERE email = '$email' AND course_title = '$course_title' LIMIT 1";
    $paid_result = $conn->query($paid_sql);
    if($paid_result && $paid_result->num_rows > 0) {
        return ['status' => 'paid', 'hours' => 0];
    }

    // Check trial logs if not paid
    $sql = "SELECT registration_date FROM course_registrations WHERE email = '$email' AND course_title = '$course_title' ORDER BY id DESC LIMIT 1";
    $result = $conn->query($sql);
    
    if($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $reg_time = strtotime($row['registration_date']);
        $seconds_passed = $current_time - $reg_time;
        
        if ($seconds_passed > $three_days_in_seconds) {
            return ['status' => 'expired', 'hours' => 0];
        } else {
            return ['status' => 'active', 'hours' => ceil(($three_days_in_seconds - $seconds_passed) / 3600)];
        }
    }
    return ['status' => 'not_started', 'hours' => 72];
}

// FIXED: Hardcoded catalog mapped exactly to your courses.php structure to avoid empty database table crashes
$courses_array = [
    [
        "title" => "HTML5 & CSS3 Masterclass",
        "desc" => "Build gorgeous, responsive, and modern user interfaces from scratch."
    ],
    [
        "title" => "Advanced JavaScript (ES6+)",
        "desc" => "Master modern JavaScript, APIs, DOM manipulation, and asynchronous programming."
    ],
    [
        "title" => "Full-Stack PHP & MySQL",
        "desc" => "Build complete web applications, dashboards, databases, and secure APIs."
    ],
    [
        "title" => "Python Systems & Automation",
        "desc" => "Learn automation, algorithms, backend services, and software development."
    ],
    [
        "title" => "AI & Machine Learning",
        "desc" => "Build intelligent systems with machine learning and modern AI tools."
    ],
    [
        "title" => "React & Next.js",
        "desc" => "Create modern production-grade web applications with React and Next.js."
    ],
    [
        "title" => "Node.js & Express",
        "desc" => "Develop scalable backend applications using JavaScript on the server."
    ],
    [
        "title" => "Rust Programming",
        "desc" => "Learn memory-safe, high-performance systems programming."
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Academy Workspace - CodeVerse</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:Segoe UI, Arial, sans-serif;background:#0b0f19;color:white;min-height:100vh;padding:40px 20px;}
.container{max-width:1100px;margin:auto;}

.header{margin-bottom:40px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:20px;}
.badge{padding:6px 14px;border-radius:20px;font-size:13px;font-weight:bold;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);color:#94a3b8;}

/* Catalog Grid Layout */
.content-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:25px;margin-bottom:50px;}
.content-card{background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);border-radius:12px;padding:25px;display:flex;flex-direction:column;justify-content:space-between;min-height:410px;transition:0.3s;position:relative;cursor:pointer;}
.content-card:hover{border-color:rgba(0,242,254,0.3);}
.content-card h3{color:#00f2fe;margin-bottom:5px;}

/* Status Spans */
.course-meta-span{font-size:12px;font-weight:bold;padding:3px 8px;border-radius:4px;display:inline-block;margin-bottom:10px;}
.span-paid{background:rgba(34,197,94,0.2);color:#4ade80;}
.span-active{background:rgba(20,184,166,0.15);color:#2dd4bf;}
.span-expired{background:rgba(239,68,68,0.15);color:#f87171;}
.span-notstarted{background:rgba(245,158,11,0.15);color:#fbbf24;}

/* Chapter Preview Panel */
.preview-pane{margin-top:15px;flex-grow:1;border-top:1px solid rgba(255,255,255,0.05);padding-top:15px;}
.preview-title{font-size:12px;text-transform:uppercase;color:#64748b;font-weight:bold;letter-spacing:1px;margin-bottom:8px;}
.lesson-list{list-style:none;}
.lesson-list li{padding:6px 0;font-size:14px;color:#94a3b8;}

/* Study Call-To-Action Link */
.study-link{display:block;width:100%;text-align:center;padding:12px;background:rgba(34,197,94,0.1);color:#2dd4bf;border:1px solid rgba(34,197,94,0.2);border-radius:6px;text-decoration:none;font-weight:bold;font-size:14px;margin-top:15px;transition:0.2s;}
.study-link:hover{background:#22c55e;color:white;}

/* Action States inside Cards */
.trial-gate-overlay{margin-top:20px;background:rgba(255,255,255,0.02);border:1px dashed rgba(255,255,255,0.1);border-radius:8px;padding:25px;text-align:center;flex-grow:1;display:flex;flex-direction:column;justify-content:center;align-items:center;}
.start-trial-btn{background:#fbbf24;color:#0b0f19;border:none;padding:11px 22px;border-radius:6px;font-weight:700;font-size:13px;cursor:pointer;margin-top:12px;transition:0.2s;}
.start-trial-btn:hover{transform:scale(1.03);background:#f59e0b;}
.lock-message{color:#ef4444;font-weight:bold;margin-top:15px;font-size:13px;text-align:center;width:100%;display:block;padding:10px;background:rgba(239,68,68,0.05);border-radius:6px;}
.card-locked{opacity:0.6;}

/* Global Invoice Container */
.billing-section{background:linear-gradient(135deg, rgba(255,255,255,0.03) 0%, rgba(255,255,255,0.01) 100%);border:1px solid rgba(255,255,255,0.08);border-radius:16px;padding:35px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:30px;box-shadow:0 20px 40px rgba(0,0,0,0.3);position:relative;}
.billing-section::before{content:'';position:absolute;top:0;left:0;width:4px;height:100%;background:linear-gradient(to bottom, #00f2fe, #007bff);}
.billing-info h2{font-size:1.6rem;margin-bottom:8px;}
.billing-info p{color:#94a3b8;font-size:14px;max-width:550px;line-height:1.5;}
.price-display{font-size:2.2rem;font-weight:800;line-height:1;}
.price-display span{font-size:1rem;color:#94a3b8;}
.currency-subtext{color:#64748b;font-size:12px;margin:4px 0 15px 0;}

/* HIGHLY FUNCTIONAL INDEPENDENT BUTTONS */
.subscribe-btn {
    display: inline-block;
    padding: 14px 35px;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 700;
    font-size: 15px;
    border: none;
    cursor: pointer;
    text-align: center;
    transition: transform 0.2s, filter 0.2s;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}
.subscribe-btn:hover {
    transform: translateY(-2px);
    filter: brightness(1.15);
}
.subscribe-btn:active {
    transform: translateY(1px);
}
</style>
</head>
<body>

<div class="container">
    
    <div class="header">
        <div>
            <h1 style="font-size:2.3rem; background:linear-gradient(135deg,#ffffff,#00f2fe); -webkit-background-clip:text; -webkit-text-fill-color:transparent;">CodeVerse Program Dashboard</h1>
            <p style="color:#94a3b8; margin-top:5px;">Track your free active periods, review syllabus content or settle your plan invoices.</p>
        </div>
        <div class="badge">Account: <span id="userEmail"><?php echo $email; ?></span></div>
    </div>

    <div class="content-grid">
        <?php 
        if(count($courses_array) > 0):
            $first_course_name = "";
            $first_course_status = "";

            foreach($courses_array as $index => $course):
                $title = $course['title'];
                $status_info = getCourseStatus($conn, $email, $title, $three_days_in_seconds, $current_time);
                $status = $status_info['status'];
                $hours = $status_info['hours'];

                if($index === 0) {
                    $first_course_name = $title;
                    $first_course_status = $status;
                }
        ?>
            <div class="content-card <?php echo ($status == 'expired') ? 'card-locked' : ''; ?>" onclick="selectCourse('<?php echo addslashes($title); ?>', '14.00', '52,000', '<?php echo $status; ?>')">
                <div>
                    <?php if($status == 'paid'): ?>
                        <span class="course-meta-span span-paid">✅ Premium Unlocked</span>
                    <?php elseif($status == 'active'): ?>
                        <span class="course-meta-span span-active">⏳ Trial: <?php echo $hours; ?>h left</span>
                    <?php elseif($status == 'expired'): ?>
                        <span class="course-meta-span span-expired">🔴 Trial Expired</span>
                    <?php else: ?>
                        <span class="course-meta-span span-notstarted">⭐ Trial Available</span>
                    <?php endif; ?>
                    
                    <h3><?php echo htmlspecialchars($title); ?></h3>
                    <p style="color:#94a3b8; font-size:13px;"><?php echo htmlspecialchars($course['desc'] ?? 'Full stack specialized study curriculum.'); ?></p>
                </div>

                <?php if($status == 'not_started'): ?>
                    <div class="trial-gate-overlay">
                        <p style="font-size:13px; color:#cbd5e1;">Ready to explore this syllabus?</p>
                        <button class="start-trial-btn" onclick="triggerTrialActivation(event, '<?php echo addslashes($title); ?>')">Start My Trial Now</button>
                    </div>
                <?php else: ?>
                    <div class="preview-pane">
                        <div class="preview-title">Syllabus Structure</div>
                        <ul class="lesson-list">
                            <li>• Chapter 1 Foundations Overview</li>
                            <li>• Chapter 2 Architecture Implementation</li>
                            <li>• Chapter 3 Development Capstones</li>
                        </ul>
                        <?php if($status == 'expired'): ?>
                            <span class="lock-message">🔒 Trial Expired. Content Locked.</span>
                        <?php else: ?>
                            <a href="study.php?email=<?php echo urlencode($email); ?>&course=<?php echo urlencode($title); ?>" 
                               class="study-link" 
                               onclick="event.stopPropagation(); window.location.href=this.href; return false;">
                               View Course Contents →
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php 
            endforeach;
        else:
        ?>
            <p style="color:#94a3b8;">No courses added to the system database yet.</p>
        <?php endif; ?>
    </div>

    <div class="billing-section" id="billingPanel">
        <div class="billing-info">
            <h2 id="billingTitle">Select a Course Track to Review Invoicing</h2>
            <p id="billingDesc">Click directly on any item in your catalog above. The workspace will update this gateway configuration panel automatically for instant checkout setup.</p>
        </div>
        <div class="billing-action" id="billingActionBox" style="display: none;">
            <div class="price-display">$<span id="usdPrice">14.00</span> <span>USD</span></div>
            <div class="currency-subtext">Approx. <span id="ugxPrice">52,000</span> UGX per single track</div>
            <button class="subscribe-btn" id="subBtn" onclick="launchLivePayment()">Purchase Course Access</button>
        </div>
    </div>

</div>

<script>
var currentSelectedCourse = "";

function triggerTrialActivation(event, courseTitle) {
    event.stopPropagation(); 
    const email = document.getElementById('userEmail').innerText;

    if(!confirm("Would you like to start your 3-day free trial for '" + courseTitle + "' now?")) {
        return;
    }

    fetch('start_trial.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email: email, course_title: courseTitle })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if(data.success) {
            window.location.reload(); 
        }
    })
    .catch(err => {
        console.error(err);
        alert("An error occurred starting the trial tracker.");
    });
}

function selectCourse(name, usd, ugx, status) {
    currentSelectedCourse = name;
    document.getElementById('billingActionBox').style.display = 'block';
    document.getElementById('billingTitle').innerText = name;
    document.getElementById('usdPrice').innerText = usd;
    document.getElementById('ugxPrice').innerText = ugx;
    
    const subBtn = document.getElementById('subBtn');
    const desc = document.getElementById('billingDesc');
    
    if(status === 'paid') {
        desc.innerText = "Awesome! You have full permanent lifetime access to this track. Keep executing the lessons above.";
        document.getElementById('billingActionBox').style.display = 'none';
    } else if(status === 'expired') {
        desc.innerText = "Your 3-day trial session for " + name + " has run out. Pay $14.00 USD below via Mobile Money or Card to permanently remove limits.";
        subBtn.innerText = "Pay $14 via Mobile Money / Card";
        subBtn.style.background = "linear-gradient(135deg, #ef4444 0%, #991b1b 100%)";
    } else if(status === 'active') {
        desc.innerText = "You have an active trial session running on this curriculum. Secure permanent ownership access anytime for just $14.00 USD.";
        subBtn.innerText = "Upgrade to Premium for $14";
        subBtn.style.background = "linear-gradient(135deg, #22c55e 0%, #15803d 100%)";
    } else {
        desc.innerText = "You have not started your free trial for this track yet! Start the 3-day trial using the button inside the card, or complete payment directly below.";
        subBtn.innerText = "Buy Track Access Direct";
        subBtn.style.background = "linear-gradient(135deg, #007bff 0%, #00f2fe 100%)";
    }
}

function launchLivePayment() {
    const studentEmail = document.getElementById('userEmail').innerText;
    
    if(!currentSelectedCourse) {
        alert("Please tap a course card first!");
        return;
    }

    if(confirm("Redirecting you to secure payment checkout gateway for: " + currentSelectedCourse + " ($14.00 USD / 52,000 UGX)?")) {
        window.location.href = "initiate_payment.php?email=" + encodeURIComponent(studentEmail) + "&course=" + encodeURIComponent(currentSelectedCourse);
    }
}

window.onload = function() {
    // 🚀 STAGE 3: FRONTEND ADDRESS BAR PARSER CLEANER
    const userEmailText = document.getElementById('userEmail').innerText.trim().toLowerCase();
    if (userEmailText === 'abdushakulhussenkindu@gmail.com' || userEmailText === 'abdushakuluhssenkindu@gmail.com') {
        const url = new URL(window.location.href);
        if (url.searchParams.has('status')) {
            url.searchParams.delete('status');
            window.history.replaceState({}, document.title, url.toString());
        }
    }

    <?php if(count($courses_array) > 0): ?>
        selectCourse('<?php echo addslashes($first_course_name); ?>', '14.00', '52,000', '<?php echo $first_course_status; ?>');
    <?php endif; ?>
};
</script>

</body>
</html>