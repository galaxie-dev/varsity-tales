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

// SQL query to fetch all articles
$sql = "SELECT id, title, category, media, content FROM articles";
$result = $conn->query($sql);

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Varsity Tales</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="nav-content">
            <div class="logo">The Varsity Tales</div>
            <div class="nav-links">
                <a href="#" class="active">Home</a>
                <a href="#latest">Latest</a>
                <a href="#categories">Categories</a>
                <!-- <a href="/admin.html" class="admin-link">Admin</a> -->
            </div>
            <div class="mobile-menu">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </nav>

    <main>
        <section class="hero">
            <div class="slider" x-data="{start: true, end: false}">
                <div class="slider__content" x-ref="slider" x-on:scroll.debounce="$refs.slider.scrollLeft == 0 ? start = true : start = false; Math.abs(($refs.slider.scrollWidth - $refs.slider.offsetWidth) - $refs.slider.scrollLeft) < 5 ? end = true : end = false;">
                    <!-- News items will be dynamically inserted here -->
                </div>
                <div class="slider__nav">
                    <button class="slider__nav__button" x-on:click="$refs.slider.scrollBy({left: $refs.slider.offsetWidth * -1, behavior: 'smooth'});" x-bind:class="start ? '' : 'slider__nav__button--active'">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="slider__nav__button" x-on:click="$refs.slider.scrollBy({left: $refs.slider.offsetWidth, behavior: 'smooth'});" x-bind:class="end ? '' : 'slider__nav__button--active'">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </section>

        <section class="filters">
            <div class="filter-container">
                <select id="sortSelect" class="filter-select">
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                </select>
                <select id="categorySelect" class="filter-select">
                    <option value="">All Categories</option>
                    <option value="campus">Campus News</option>
                    <option value="sports">Sports</option>
                    <option value="events">Events</option>
                    <option value="academic">Academic</option>
                    <option value="other">Other</option>
                </select>
                <input type="text" id="searchInput" class="search-input" placeholder="Search by keyword...">
            </div>
        </section>

        <!-- news posting section -->
        <section id="newsContainer" class="news-container">
            <!-- <h1>Published Articles</h1> -->
            <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $excerpt = substr(strip_tags($row["content"]), 0, 150) . '...';
                            $mediaPath = "uploads/" . $row["media"];
                            $hasMedia = file_exists($mediaPath) && in_array(pathinfo($mediaPath, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']);
                            
                            echo "<div class='card'>";
                            echo "<div class='card__corner'></div>";
                            
                            echo "<div class='card__img' " . ($hasMedia ? "style='background-image: url(\"" . htmlspecialchars($mediaPath) . "\");'" : "") . ">";
                            echo "<span class='card__span'>" . htmlspecialchars($row["category"]) . "</span>";
                            echo "</div>";
                            
                            echo "<div class='card-int'>";
                            echo "<p class='card-int__title'>" . htmlspecialchars($row["title"]) . "</p>";
                            echo "<p class='excerpt'>" . htmlspecialchars($excerpt) . "</p>";
                            echo "<a href='article.php?id=" . htmlspecialchars($row["id"]) . "' class='card-int__button'>Show More</a>";
                            echo "</div>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>0 results</p>";
                    }
                    ?>
                    
        </section>
    </main>

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
            <div class="footer-section">
                <h3>Campus Chronicles</h3>
                <p>Your trusted source for campus news and updates.</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="#latest">Latest News</a></li>
                    <li><a href="#categories">Categories</a></li>
                    <li><a href="#about">About Us</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Connect With Us</h3>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2024 Campus Chronicles. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.2/cdn.js"></script>
    <script src="js/main.js"></script>
    <script>
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

        // Mobile Menu Toggle
        const mobileMenu = document.querySelector('.mobile-menu');
        const navLinks = document.querySelector('.nav-links');

        mobileMenu.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            mobileMenu.querySelector('i').classList.toggle('fa-bars');
            mobileMenu.querySelector('i').classList.toggle('fa-times');
        });
    </script>
</body>
</html>