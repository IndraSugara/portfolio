<?php
try {
    // Baca konfigurasi dari environment variable
    $host = getenv('DB_HOST');
    $port = getenv('DB_PORT');
    $db   = getenv('DB_NAME');
    $user = getenv('DB_USER');
    $pass = getenv('DB_PASS');

    // Buat koneksi PDO
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // Drop and recreate profile table to fix structure
    $pdo->exec("DROP TABLE IF EXISTS profile");
    $pdo->exec("CREATE TABLE profile (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        title VARCHAR(255) NOT NULL,
        bio TEXT NOT NULL,
        image_url VARCHAR(255),
        email VARCHAR(255),
        phone VARCHAR(50),
        location VARCHAR(255),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Create skills table
    $pdo->exec("CREATE TABLE IF NOT EXISTS skills (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        percentage INT NOT NULL,
        category VARCHAR(100) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Create experience table
    $pdo->exec("CREATE TABLE IF NOT EXISTS experience (
        id INT AUTO_INCREMENT PRIMARY KEY,
        position VARCHAR(255) NOT NULL,
        company VARCHAR(255) NOT NULL,
        period VARCHAR(100) NOT NULL,
        description TEXT NOT NULL,
        start_date DATE,
        end_date DATE,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Create education table
    $pdo->exec("CREATE TABLE IF NOT EXISTS education (
        id INT AUTO_INCREMENT PRIMARY KEY,
        degree VARCHAR(255) NOT NULL,
        institution VARCHAR(255) NOT NULL,
        period VARCHAR(100) NOT NULL,
        description TEXT NOT NULL,
        start_date DATE,
        end_date DATE,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Create articles table
    $pdo->exec("CREATE TABLE IF NOT EXISTS articles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        excerpt TEXT,
        category VARCHAR(100) NOT NULL,
        image_url VARCHAR(255),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");

    // Create messages table
    $pdo->exec("CREATE TABLE IF NOT EXISTS messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        subject VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        read_at DATETIME
    )");

    // Create users table for admin
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Create activities table
    $pdo->exec("CREATE TABLE IF NOT EXISTS activities (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        icon VARCHAR(100),
        link VARCHAR(255),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Create portfolio table
    $pdo->exec("CREATE TABLE IF NOT EXISTS portfolio (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        image_url VARCHAR(255),
        category VARCHAR(100) NOT NULL,
        project_url VARCHAR(255),
        github_url VARCHAR(255),
        technologies TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // === INSERT DATA LOGIC ===

    // Profile
    $stmt = $pdo->query("SELECT COUNT(*) FROM profile");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO profile (name, title, bio, image_url, email, phone, location) VALUES 
            ('Indra Sugara', 'AI Enthusiast', 
            'Seorang developer passionate yang berfokus pada inovasi digital dan solusi teknologi modern, saat ini Berfokus dalam pembelajaran web full-stack, AI, dan software developing.', 
            'img/profile.png', 'sughara78@gmail.com', '+62 822-5930-6737', 'Karyamusa, Riau, Indonesia')");
    }

    // Skills
    $stmt = $pdo->query("SELECT COUNT(*) FROM skills");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO skills (name, percentage, category) VALUES 
            ('HTML/CSS', 95, 'Frontend'),
            ('JavaScript', 90, 'Frontend'),
            ('React.js', 85, 'Frontend'),
            ('Vue.js', 80, 'Frontend'),
            ('PHP', 88, 'Backend'),
            ('Node.js', 82, 'Backend'),
            ('Python', 85, 'Backend'),
            ('MySQL', 87, 'Database'),
            ('PostgreSQL', 83, 'Database'),
            ('Git/GitHub', 92, 'Tools'),
            ('Docker', 78, 'Tools'),
            ('AWS', 75, 'Cloud')");
    }

    // Experience
    $stmt = $pdo->query("SELECT COUNT(*) FROM experience");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO experience (position, company, period, description, start_date, end_date) VALUES 
            ('Senior Full Stack Developer', 'Tech Innovation Corp', '2022 - Sekarang', 
            'Memimpin tim pengembangan aplikasi web dan mobile...', '2022-01-01', NULL),
            ('Full Stack Developer', 'Digital Solutions Ltd', '2020 - 2022', 
            'Mengembangkan aplikasi e-commerce dan sistem manajemen inventory...', '2020-03-01', '2022-12-31'),
            ('Frontend Developer', 'Creative Agency', '2019 - 2020', 
            'Fokus pada pengembangan interface user yang responsive dan interaktif...', '2019-06-01', '2020-02-28')");
    }

    // Education
    $stmt = $pdo->query("SELECT COUNT(*) FROM education");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO education (degree, institution, period, description, start_date, end_date) VALUES 
            ('S1 Teknik Informatika', 'Universitas Maritim Raja Ali Haji', '2023 - Sekarang', 
            'Jurusan Teknik Informatika dengan fokus...', '2023-08-01', '2027-07-31'),
            ('SMA IPS', 'SMA Negeri 1 Teluk Belengkong', '2020 - 2023', 
            'Sekolah Menengah Atas jurusan IPS...', '2020-07-01', '2023-06-30')");
    }

    // Articles
    $stmt = $pdo->query("SELECT COUNT(*) FROM articles");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO articles (title, content, excerpt, category, image_url) VALUES 
            ('Panduan Lengkap React.js untuk Pemula', '<h2>Pengenalan React.js</h2>...', 'Pelajari dasar-dasar React.js...', 'Web Development', 'img/work1.jpg'),
            ('Tips Optimasi Performance Website', '<h2>Mengapa Performance Penting?</h2>...', 'Teknik-teknik praktis untuk meningkatkan kecepatan loading...', 'Performance', 'img/work2.jpg'),
            ('Membangun API dengan Node.js dan Express', '<h2>Setup Project</h2>...', 'Tutorial step-by-step membangun RESTful API...', 'Backend', 'img/work3.jpg')");
    }

    // Users (admin)
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    if ($stmt->fetchColumn() == 0) {
        $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute(['admin', $hashedPassword]);
    }

    // Activities
    $stmt = $pdo->query("SELECT COUNT(*) FROM activities");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO activities (title, description, icon, link) VALUES 
            ('Konsultasi Web Development', 'Layanan konsultasi untuk pengembangan website dan aplikasi web modern', 'bx-code-alt', '#contact'),
            ('Mobile App Development', 'Pengembangan aplikasi mobile Android dan iOS...', 'bx-mobile-alt', '#contact'),
            ('System Integration', 'Integrasi sistem dan API untuk efisiensi bisnis...', 'bx-link-alt', '#contact'),
            ('Technical Training', 'Pelatihan teknis programming dan development...', 'bx-book-reader', '#contact')");
    }

    // Portfolio
    $stmt = $pdo->query("SELECT COUNT(*) FROM portfolio");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO portfolio (title, description, image_url, category, project_url, github_url, technologies) VALUES 
            ('E-Commerce Platform', 'Platform e-commerce modern...', 'img/work1.jpg', 'Web Development', '#', '#', 'React.js, Node.js, MongoDB'),
            ('Mobile Banking App', 'Aplikasi mobile banking dengan security tinggi...', 'img/work2.jpg', 'Mobile App', '#', '#', 'React Native, Firebase, Node.js'),
            ('Corporate Website', 'Website corporate dengan CMS custom...', 'img/work3.jpg', 'UI/UX Design', '#', '#', 'PHP, MySQL, jQuery, Bootstrap'),
            ('Task Management System', 'Sistem manajemen tugas dengan kolaborasi real-time...', 'img/work4.jpg', 'Web Development', '#', '#', 'Vue.js, Laravel, PostgreSQL'),
            ('Inventory Management', 'Sistem manajemen inventori untuk retail...', 'img/work5.jpg', 'System Integration', '#', '#', 'Python, Django, REST API')");
    }

} catch(PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
}
?>
