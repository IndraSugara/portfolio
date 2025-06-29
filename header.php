<?php
session_start();
// Determine current page for active nav link
$page = basename($_SERVER['SCRIPT_NAME'], '.php');
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Revolutionary Portfolio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon" />
  </head>
  <body>
    <audio id="bg-music" src="assets/musics/bgmusic.mp3" loop controls></audio>

    <header class="header">
      <nav class="navbar container">
        <a href="index.php" class="logo">Portfolio</a>
        <input type="checkbox" id="menuToggle" class="menu-toggle" />
        <label for="menuToggle" class="menu-icon">
          <i class="bx bx-menu"></i>
          <i class="bx bx-x"></i>
        </label>
        <ul class="nav-menu">
          <li><a href="index.php" class="nav-link <?php echo $page=='index'?'active':''; ?>">Home</a></li>
          <li><a href="articles.php" class="nav-link <?php echo $page=='articles'?'active':''; ?>">Articles</a></li>
          <li><a href="contact.php" class="nav-link <?php echo $page=='contact'?'active':''; ?>">Contact</a></li>
          <li><a href="admin/login.php" class="nav-link <?php echo $page=='admin'||$page=='login'?'active':''; ?>">Admin</a></li>
        </ul>
      </nav>
    </header>
    <main>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/audio-player.js"></script>
  </body>
</html>