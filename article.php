<?php
// Database connection parameters
$servername = "localhost";
$username = "root";  // Default for XAMPP
$password = "";      // Default for XAMPP, but use your password if set
$dbname = "campus_chronicles";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if article ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("No article ID provided.");
}

$articleId = intval($_GET['id']);

// Fetch the specific article
$sql = "SELECT * FROM articles WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $articleId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Article not found.");
}

$article = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['title']); ?> - The Varsity Tales</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .page-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        .sidebar {
            width: 25%;
            background-color: transparent;
            min-width: 100px;
        }

        .article-container {
            width: 50%;
            max-width: 800px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .article-header {
            width: 100%;
            text-align: left;
            margin-bottom: 30px;
        }

        .article-header h1 {
            font-size: 2.5rem;
            margin-bottom: 15px;
            word-wrap: break-word;
        }

        .article-media {
            width: 100%;
            max-height: 500px;
            margin-bottom: 30px;
            display: flex;
            justify-content: center;
            overflow: hidden;
        }

        .article-media img, 
        .article-media video {
            max-width: 100%;
            max-height: 500px;
            object-fit: contain;
        }

        .article-text {
            width: 100%;
            line-height: 1.8;
            text-align: justify;
            word-wrap: break-word;
        }

        .article-text p {
            margin-bottom: 20px;
        }

        @media (max-width: 1024px) {
            .page-wrapper {
                flex-direction: column;
            }

            .sidebar {
                display: none;
            }

            .article-container {
                width: 100%;
                padding: 10px;
            }

            .article-header h1 {
                font-size: 2rem;
            }
        }

        /* Mobile Menu Styles */
        .mobile-menu {
            display: none;
            color: var(--light-text);
            font-size: 1.5rem;
            cursor: pointer;
            position: absolute;
            top: 20px;
            right: 20px;
        }

        @media (max-width: 768px) {
            .mobile-menu {
                display: block;
            }

            .nav-links {
                display: none;
                position: fixed;
                top: 70px;
                left: 0;
                width: 100%;
                background: var(--nav-bg);
                flex-direction: column;
                padding: 20px;
                z-index: 1000;
            }

            .nav-links.active {
                display: flex;
            }
        }

        /* Theme Switcher Styles */
        .theme-switcher {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
        }

        .theme-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.5rem;
            opacity: 0.7;
            transition: opacity 0.3s ease;
        }

        .theme-btn:hover {
            opacity: 1;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-content">
            <div class="logo">The Varsity Tales</div>
            <div class="nav-links">
                <a href="index.php" class="active">Home</a>
                <a href="#latest">Latest</a>
                <a href="#categories">Categories</a>
            </div>
            <div class="mobile-menu">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </nav>

    <div class="page-wrapper">
        <div class="sidebar"></div>
        
        <div class="article-container">
            <div class="article-header">
                <h1><?php echo htmlspecialchars($article['title']); ?></h1>
                <span class="category-tag"><?php echo htmlspecialchars($article['category']); ?></span>
            </div>

            <?php 
            // Display media if available
            $mediaPath = "uploads/" . $article["media"];
            if (file_exists($mediaPath)) {
                $fileExtension = pathinfo($mediaPath, PATHINFO_EXTENSION);
                if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                    echo "<div class='article-media'><img src='" . htmlspecialchars($mediaPath) . "' alt='Article Image'></div>";
                } elseif (in_array($fileExtension, ['mp4', 'webm', 'ogg'])) {
                    echo "<div class='article-media'><video controls><source src='" . htmlspecialchars($mediaPath) . "' type='video/" . $fileExtension . "'>Your browser does not support the video tag.</video></div>";
                }
            }
            ?>

            <div class="article-text">
                <?php 
                // Split content into paragraphs
                $paragraphs = explode("\n", $article['content']);
                foreach ($paragraphs as $paragraph) {
                    if (trim($paragraph) !== '') {
                        echo "<p>" . htmlspecialchars($paragraph) . "</p>";
                    }
                }
                ?>
            </div>
        </div>
        
        <div class="sidebar"></div>
    </div>

    <div class="theme-switcher">
        <button class="theme-btn light" data-theme="light">
            <i class="fas fa-sun"></i>
        </button>
        <button class="theme-btn dark" data-theme="dark">
            <i class="fas fa-moon"></i>
        </button>
        <button class="theme-btn cyberpunk" data-theme="cyberpunk">
            <i class="fas fa-robot"></i>
        </button>
    </div>

    <footer class="footer">
        <div class="footer-content">
            <p>© 2024 The Varsity Tales. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Mobile Menu Toggle
        const mobileMenu = document.querySelector('.mobile-menu');
        const navLinks = document.querySelector('.nav-links');

        mobileMenu.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            mobileMenu.querySelector('i').classList.toggle('fa-bars');
            mobileMenu.querySelector('i').classList.toggle('fa-times');
        });

        // Theme Switcher
        document.querySelectorAll('.theme-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const theme = btn.dataset.theme;
                document.documentElement.setAttribute('data-theme', theme);
                localStorage.setItem('theme', theme);
            });
        });

        // Load saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>
</body>
</html>