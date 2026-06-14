<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$courses = [
[
"title" => "HTML5 & CSS3 Masterclass",
"desc" => "Build gorgeous, responsive, and modern user interfaces from scratch.",
"tag" => "Frontend",
"level" => "Beginner"
],
[
"title" => "Advanced JavaScript (ES6+)",
"desc" => "Master modern JavaScript, APIs, DOM manipulation, and asynchronous programming.",
"tag" => "Frontend",
"level" => "Intermediate"
],
[
"title" => "Full-Stack PHP & MySQL",
"desc" => "Build complete web applications, dashboards, databases, and secure APIs.",
"tag" => "Backend",
"level" => "Professional"
],
[
"title" => "Python Systems & Automation",
"desc" => "Learn automation, algorithms, backend services, and software development.",
"tag" => "Data",
"level" => "Beginner"
],
[
"title" => "AI & Machine Learning",
"desc" => "Build intelligent systems with machine learning and modern AI tools.",
"tag" => "AI",
"level" => "Advanced"
],
[
"title" => "React & Next.js",
"desc" => "Create modern production-grade web applications with React and Next.js.",
"tag" => "Frontend",
"level" => "Advanced"
],
[
"title" => "Node.js & Express",
"desc" => "Develop scalable backend applications using JavaScript on the server.",
"tag" => "Backend",
"level" => "Intermediate"
],
[
"title" => "Rust Programming",
"desc" => "Learn memory-safe, high-performance systems programming.",
"tag" => "Systems",
"level" => "Professional"
]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Courses - CodeVerse Academy</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:Segoe UI, Arial, sans-serif;background:#0b0f19;color:white;min-height:100vh;padding:50px 20px;}
.container{max-width:1200px;margin:auto;}
.back-home{color:#94a3b8;text-decoration:none;display:inline-block;margin-bottom:20px;font-weight:600;}
.back-home:hover{color:#00f2fe;}
.header{text-align:center;margin-bottom:50px;}
.header h1{font-size:3rem;margin-bottom:10px;background:linear-gradient(135deg,#ffffff,#00f2fe);-webkit-background-clip:text;-webkit-text-fill-color:transparent;}
.header p{color:#94a3b8;}
.courses-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:25px;}
.course-card{background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:15px;padding:25px;transition:0.3s;}
.course-card:hover{transform:translateY(-6px);border-color:#00f2fe;box-shadow:0 10px 30px rgba(0,242,254,0.15);}
.card-meta{display:flex;justify-content:space-between;margin-bottom:15px;}
.tag{padding:5px 10px;border-radius:6px;font-size:12px;font-weight:bold;}
.tag-frontend{background:rgba(59,130,246,.2);color:#60a5fa;}
.tag-backend{background:rgba(168,85,247,.2);color:#c084fc;}
.tag-data{background:rgba(234,179,8,.2);color:#facc15;}
.tag-ai{background:rgba(20,184,166,.2);color:#2dd4bf;}
.tag-systems{background:rgba(239,68,68,.2);color:#f87171;}
.level{color:#94a3b8;font-size:12px;}
.course-card h3{margin-bottom:12px;line-height:1.4;}
.course-card p{color:#94a3b8;line-height:1.6;margin-bottom:20px;}
.learn-btn{display:inline-block;color:#00f2fe;text-decoration:none;font-weight:600;}
.learn-btn:hover{color:white;}
</style>
</head>
<body>
<div class="container">
<a href="index.php" class="back-home">← Back to Home</a>
<div class="header">
<h1>Available Courses</h1>
<p>Master the most in-demand technologies and build real-world projects.</p>
</div>
<div class="courses-grid">
<?php foreach($courses as $course): ?>
<?php
$tagClass = "tag-data";
if($course['tag'] == "Frontend"){ $tagClass = "tag-frontend"; }
elseif($course['tag'] == "Backend"){ $tagClass = "tag-backend"; }
elseif($course['tag'] == "AI"){ $tagClass = "tag-ai"; }
elseif($course['tag'] == "Systems"){ $tagClass = "tag-systems"; }
?>
<div class="course-card">
<div class="card-meta">
<span class="tag <?php echo $tagClass; ?>"><?php echo $course['tag']; ?></span>
<span class="level"><?php echo $course['level']; ?></span>
</div>
<h3><?php echo $course['title']; ?></h3>
<p><?php echo $course['desc']; ?></p>
<a href="register.php?course=<?php echo urlencode($course['title']); ?>" class="learn-btn">Learn More →</a>
</div>
<?php endforeach; ?>
</div>
</div>
</body>
</html>