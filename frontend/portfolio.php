<?php
session_start();
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Portfólió - SmartCodeGen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/portfolio.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <header class="site-header">
        <div class="header-content">
            <a href="main.php" class="logo">SmartCodeGen</a>
            <nav class="main-nav">
                <ul class="nav-list">
                    <li><a href="main.php">Kezdőlap</a></li>
                    <li><a href="index.php">Chat (Kódgenerálás)</a></li>
                    <li><a href="how-it-works.php">Hogyan működik?</a></li>
                    <li><a href="contact.php">Kapcsolat</a></li>
                    <li><a href="profile.php">Profil</a></li>
                    <?php if (isset($_SESSION['jwt'])): ?>
                        <li><a href="logout.php">Kijelentkezés</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Belépés</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
            <button id="theme-toggle" class="theme-toggle" aria-label="Téma váltása"></button>
            <button class="menu-toggle" aria-label="Menü megnyitása">☰</button>
        </div>
    </header>

    <div class="content-container">
        <h1 class="main-heading">Projektek és Szakdolgozat</h1>
        <p class="intro-text">
            Itt bemutatom a legfontosabb projekteimet és a SmartCodeGen-nel kapcsolatos fejlesztéseket.
        </p>

        <div class="portfolio-grid">
            <div class="project-card">
                <img src="img/smartcodegen.png" alt="SmartCodeGen projekt" class="project-image">
                <div class="card-content">
                    <h3>SmartCodeGen AI asszisztens</h3>
                    <p>
                        A fő projektem, egy intelligens kódgeneráló eszköz, amely mesterséges intelligencia segítségével képes kódot, funkciókat és magyarázatokat készíteni.
                    </p>
                    <div class="project-tags">
                        <span class="tag">PHP</span>
                        <span class="tag">JavaScript</span>
                        <span class="tag">CSS</span>
                        <span class="tag">AI</span>
                    </div>
                    <a href="index.php" class="project-link">
                        Megtekintés <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="project-card">
                <img src="img/project2.png" alt="Második projekt" class="project-image">
                <div class="card-content">
                    <h3>Webáruház sablon</h3>
                    <p>
                        Egy reszponzív webáruház sablon, amit HTML, CSS és JavaScript segítségével készítettem el. Modern dizájn, kosár funkcióval.
                    </p>
                    <div class="project-tags">
                        <span class="tag">HTML</span>
                        <span class="tag">CSS</span>
                        <span class="tag">JavaScript</span>
                    </div>
                    <a href="#" class="project-link">
                        Megtekintés <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            </div>

        <h2 class="main-heading section-heading">Készségek és Technológiák</h2>
        <div class="skills-grid">
            <span class="skill-badge">HTML5</span>
            <span class="skill-badge">CSS3</span>
            <span class="skill-badge">JavaScript (ES6+)</span>
            <span class="skill-badge">PHP</span>
            <span class="skill-badge">MySQL</span>
            <span class="skill-badge">Node.js</span>
            <span class="skill-badge">Git & GitHub</span>
            <span class="skill-badge">UI/UX Design</span>
        </div>
    </div>

    <footer class="site-footer">
        <div class="footer-content">
            <div class="footer-section about">
                <h3>SmartCodeGen</h3>
                <p>Intelligens kódtámogató eszköz, amely segít gyorsabban és hatékonyabban fejleszteni.</p>
                <div class="social-links">
                    <a href="https://www.facebook.com/romuald.kajdon" class="social-icon">F</a>
                    <a href="#" class="social-icon">T</a>
                    <a href="https://www.linkedin.com/in/kajdon-romuald-115193351" class="social-icon">L</a>
                    <a href="mailto:kajdon.romuald@gmail.com" class="social-icon">G</a>
                </div>
            </div>
            <div class="footer-section links">
                <h3>Gyorslinkek</h3>
                <ul>
                    <li><a href="index.php">Kódgenerálás</a></li>
                    <li><a href="how-it-works.php">Hogyan működik?</a></li>
                    <li><a href="portfolio.php">Portfólió</a></li>
                    <li><a href="privacy.php">Adatvédelmi Nyilatkozat</a></li>
                    <li><a href="terms.php">Felhasználási Feltételek</a></li>
                </ul>
            </div>
            <div class="footer-section contact">
                <h3>Kapcsolat</h3>
                <p>Email: kajdon.romuald@gmail.com</p>
                <p>Cím: Budapest, Magyarország</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date("Y"); ?> SmartCodeGen. Minden jog fenntartva.</p>
        </div>
    </footer>

    <script src="js/navbar.js"></script>
    <script src="js/theme.js"></script>
    <script src="js/portfolio.js"></script>
</body>
</html>