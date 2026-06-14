<?php
$title = "CodeVerse Academy";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #0b0f19; /* Deep space dark background */
            color: #ffffff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow-hidden;
            position: relative;
        }

        /* Subtle background glow effects for that premium tech feel */
        body::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(0, 123, 255, 0.15);
            border-radius: 50%;
            top: 15%;
            left: 20%;
            filter: blur(100px);
            z-index: 1;
        }

        body::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(0, 242, 254, 0.15);
            border-radius: 50%;
            bottom: 15%;
            right: 20%;
            filter: blur(100px);
            z-index: 1;
        }

        /* Hero Container */
        .hero-container {
            max-width: 800px;
            padding: 40px 20px;
            text-align: center;
            z-index: 2; /* Sits above the background glows */
        }

        /* Small modern badge */
        .badge {
            display: inline-block;
            padding: 6px 16px;
            background: rgba(0, 123, 255, 0.1);
            border: 1px solid rgba(0, 123, 255, 0.3);
            color: #00f2fe;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 25px;
        }

        /* Iconic Headline */
        h1 {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -1px;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #ffffff 30%, #00f2fe 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Sleek Subtitle */
        p {
            font-size: 1.25rem;
            color: #94a3b8; /* Soft muted silver/gray */
            max-width: 600px;
            margin: 0 auto 40px auto;
            line-height: 1.6;
        }

        /* Super Attractive CTA Button */
        .btn-cta {
            display: inline-block;
            text-decoration: none;
            padding: 16px 36px;
            font-size: 1.1rem;
            font-weight: 600;
            color: #ffffff;
            background: linear-gradient(135deg, #007bff 0%, #00f2fe 100%);
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 242, 254, 0.3);
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
        }

        /* Interactive Hover Effects */
        .btn-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0, 242, 254, 0.5);
            background: linear-gradient(135deg, #00f2fe 0%, #007bff 100%);
        }

        .btn-cta:active {
            transform: translateY(-1px);
        }

        /* Responsive design for mobile screens */
        @media (max-width: 768px) {
            h1 {
                font-size: 2.5rem;
            }
            p {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>

    <div class="hero-container">
        <div class="badge">Next-Gen Tech Education</div>
        
        <h1>Welcome to <br>CodeVerse Academy</h1>

        <p>Master the ultimate coding skills. Go from absolute beginner to high-income tech professional with world-class step-by-step guidance.</p>

        <a href="courses.php" class="btn-cta">Explore Courses &rarr;</a>
    </div>

</body>
</html>