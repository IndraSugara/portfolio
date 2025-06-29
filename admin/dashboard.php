<?php
session_start();
// Check if logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../database/init_db.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            // Activities CRUD
            case 'add_activity':
                $stmt = $pdo->prepare("INSERT INTO activities (title, description, icon, link) VALUES (?, ?, ?, ?)");
                $stmt->execute([$_POST['title'], $_POST['description'], $_POST['icon'], $_POST['link']]);
                $success = "Aktivitas berhasil ditambahkan!";
                break;
                
            case 'edit_activity':
                $stmt = $pdo->prepare("UPDATE activities SET title = ?, description = ?, icon = ?, link = ? WHERE id = ?");
                $stmt->execute([$_POST['title'], $_POST['description'], $_POST['icon'], $_POST['link'], $_POST['id']]);
                $success = "Aktivitas berhasil diperbarui!";
                break;
                
            case 'delete_activity':
                if (isset($_POST['id']) && is_numeric($_POST['id'])) {
                    $stmt = $pdo->prepare("DELETE FROM activities WHERE id = ?");
                    $stmt->execute([$_POST['id']]);
                    $success = "Aktivitas berhasil dihapus!";
                }
                break;

            // Experience CRUD
            case 'add_experience':
                $stmt = $pdo->prepare("INSERT INTO experience (position, company, period, description, start_date, end_date) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$_POST['position'], $_POST['company'], $_POST['period'], $_POST['description'], $_POST['start_date'], $_POST['end_date']]);
                $success = "Pengalaman berhasil ditambahkan!";
                break;
                
            case 'edit_experience':
                $stmt = $pdo->prepare("UPDATE experience SET position = ?, company = ?, period = ?, description = ?, start_date = ?, end_date = ? WHERE id = ?");
                $stmt->execute([$_POST['position'], $_POST['company'], $_POST['period'], $_POST['description'], $_POST['start_date'], $_POST['end_date'], $_POST['id']]);
                $success = "Pengalaman berhasil diperbarui!";
                break;
                
            case 'delete_experience':
                if (isset($_POST['id']) && is_numeric($_POST['id'])) {
                    $stmt = $pdo->prepare("DELETE FROM experience WHERE id = ?");
                    $stmt->execute([$_POST['id']]);
                    $success = "Pengalaman berhasil dihapus!";
                }
                break;

            // Skills CRUD
            case 'add_skill':
                $stmt = $pdo->prepare("INSERT INTO skills (name, percentage, category) VALUES (?, ?, ?)");
                $stmt->execute([$_POST['name'], $_POST['percentage'], $_POST['category']]);
                $success = "Skill berhasil ditambahkan!";
                break;
                
            case 'edit_skill':
                $stmt = $pdo->prepare("UPDATE skills SET name = ?, percentage = ?, category = ? WHERE id = ?");
                $stmt->execute([$_POST['name'], $_POST['percentage'], $_POST['category'], $_POST['id']]);
                $success = "Skill berhasil diperbarui!";
                break;
                
            case 'delete_skill':
                $stmt = $pdo->prepare("DELETE FROM skills WHERE id = ?");
                $stmt->execute([$_POST['id']]);
                $success = "Skill berhasil dihapus!";
                break;

            // Messages CRUD
            case 'mark_read':
                $stmt = $pdo->prepare("UPDATE messages SET read_at = CURRENT_TIMESTAMP WHERE id = ?");
                $stmt->execute([$_POST['id']]);
                $success = "Pesan ditandai sudah dibaca!";
                break;
                
            case 'delete_message':
                $stmt = $pdo->prepare("DELETE FROM messages WHERE id = ?");
                $stmt->execute([$_POST['id']]);
                $success = "Pesan berhasil dihapus!";
                break;

            // Articles CRUD
            case 'add_article':
                $stmt = $pdo->prepare("INSERT INTO articles (title, content, excerpt, category) VALUES (?, ?, ?, ?)");
                $stmt->execute([$_POST['title'], $_POST['content'], $_POST['excerpt'], $_POST['category']]);
                $success = "Artikel berhasil ditambahkan!";
                break;
                
            case 'edit_article':
                $stmt = $pdo->prepare("UPDATE articles SET title = ?, content = ?, excerpt = ?, category = ? WHERE id = ?");
                $stmt->execute([$_POST['title'], $_POST['content'], $_POST['excerpt'], $_POST['category'], $_POST['id']]);
                $success = "Artikel berhasil diperbarui!";
                break;
                
            case 'delete_article':
                $stmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
                $stmt->execute([$_POST['id']]);
                $success = "Artikel berhasil dihapus!";
                break;

            // Portfolio CRUD
            case 'add_portfolio':
                $stmt = $pdo->prepare("INSERT INTO portfolio (title, description, image_url, category, project_url, github_url, technologies) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$_POST['title'], $_POST['description'], $_POST['image_url'], $_POST['category'], $_POST['project_url'], $_POST['github_url'], $_POST['technologies']]);
                $success = "Portfolio berhasil ditambahkan!";
                break;
                
            case 'edit_portfolio':
                $stmt = $pdo->prepare("UPDATE portfolio SET title = ?, description = ?, image_url = ?, category = ?, project_url = ?, github_url = ?, technologies = ? WHERE id = ?");
                $stmt->execute([$_POST['title'], $_POST['description'], $_POST['image_url'], $_POST['category'], $_POST['project_url'], $_POST['github_url'], $_POST['technologies'], $_POST['id']]);
                $success = "Portfolio berhasil diperbarui!";
                break;
                
            case 'delete_portfolio':
                $stmt = $pdo->prepare("DELETE FROM portfolio WHERE id = ?");
                $stmt->execute([$_POST['id']]);
                $success = "Portfolio berhasil dihapus!";
                break;
        }
    }
}

// Get all data
try {
    // Activities
    $stmt = $pdo->query("SELECT * FROM activities ORDER BY created_at DESC");
    $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Experience
    $stmt = $pdo->query("SELECT * FROM experience ORDER BY start_date DESC");
    $experiences = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Skills
    $stmt = $pdo->query("SELECT * FROM skills ORDER BY category, name");
    $skills = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Messages
    $stmt = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC");
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Articles
    $stmt = $pdo->query("SELECT * FROM articles ORDER BY created_at DESC");
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Portfolio
    $stmt = $pdo->query("SELECT * FROM portfolio ORDER BY created_at DESC");
    $portfolio_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Statistics
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM messages");
    $total_messages = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM messages WHERE read_at IS NULL");
    $unread_messages = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
} catch (PDOException $e) {
    $error = "Error loading data: " . $e->getMessage();
}

// Get item for editing
$edit_activity = null;
$edit_experience = null;
$edit_skill = null;
$edit_article = null;
$edit_portfolio = null;

if (isset($_GET['edit_activity']) && is_numeric($_GET['edit_activity'])) {
    $stmt = $pdo->prepare("SELECT * FROM activities WHERE id = ?");
    $stmt->execute([$_GET['edit_activity']]);
    $edit_activity = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (isset($_GET['edit_experience']) && is_numeric($_GET['edit_experience'])) {
    $stmt = $pdo->prepare("SELECT * FROM experience WHERE id = ?");
    $stmt->execute([$_GET['edit_experience']]);
    $edit_experience = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (isset($_GET['edit_skill']) && is_numeric($_GET['edit_skill'])) {
    $stmt = $pdo->prepare("SELECT * FROM skills WHERE id = ?");
    $stmt->execute([$_GET['edit_skill']]);
    $edit_skill = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (isset($_GET['edit_article']) && is_numeric($_GET['edit_article'])) {
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->execute([$_GET['edit_article']]);
    $edit_article = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (isset($_GET['edit_portfolio']) && is_numeric($_GET['edit_portfolio'])) {
    $stmt = $pdo->prepare("SELECT * FROM portfolio WHERE id = ?");
    $stmt->execute([$_GET['edit_portfolio']]);
    $edit_portfolio = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - All CRUD Operations</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Lato', sans-serif;
        }
        
        .admin-header {
            background: linear-gradient(135deg, #b71c1c 0%, #8b0000 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        
        .admin-header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .admin-title {
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            margin: 0;
        }
        
        .admin-nav {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        .admin-nav a {
            color: #fff;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            transition: all 0.3s ease;
            font-weight: 600;
        }
        
        .admin-nav a:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
        
        .dashboard-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 2rem;
            justify-content: center;
        }
        
        .tab-btn {
            padding: 0.75rem 1.5rem;
            background: #fff;
            color: #333;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .tab-btn.active {
            background: #b71c1c;
            color: white;
        }
        
        .tab-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .admin-container {
            background: #fff;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }
        
        .form-container {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #b71c1c;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }
        
        .btn-primary {
            background: #b71c1c;
            color: white;
        }
        
        .btn-primary:hover {
            background: #8b0000;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #545b62;
        }
        
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        
        .btn-danger:hover {
            background: #c82333;
        }
        
        .btn-success {
            background: #28a745;
            color: white;
        }
        
        .btn-success:hover {
            background: #218838;
        }
        
        .item {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            border-left: 4px solid #b71c1c;
        }
        
        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .item-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
        }
        
        .item-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: #fff;
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #b71c1c;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
        }
        
        @media (max-width: 768px) {
            .admin-header .container {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .item-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .item-actions {
                width: 100%;
                justify-content: flex-start;
            }
            
            .dashboard-tabs {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <div class="container">
            <h1 class="admin-title">
                <i class='bx bx-dashboard'></i> Admin Dashboard - All CRUD
            </h1>
            <div class="admin-nav">
                <a href="../index.php"><i class='bx bx-home'></i> Website</a>
                <a href="logout.php"><i class='bx bx-log-out'></i> Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <i class='bx bx-check-circle'></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <i class='bx bx-error-circle'></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Statistics Overview -->
        <div class="admin-container">
            <h3><i class='bx bx-bar-chart'></i> Statistik Overview</h3>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($activities); ?></div>
                    <div class="stat-label">Total Aktivitas</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($experiences); ?></div>
                    <div class="stat-label">Total Pengalaman</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($skills); ?></div>
                    <div class="stat-label">Total Skills</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $total_messages; ?></div>
                    <div class="stat-label">Total Messages</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $unread_messages; ?></div>
                    <div class="stat-label">Unread Messages</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($articles); ?></div>
                    <div class="stat-label">Total Articles</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($portfolio_items); ?></div>
                    <div class="stat-label">Total Portfolio</div>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="dashboard-tabs">
            <button class="tab-btn active" onclick="showTab('activities')">
                <i class='bx bx-calendar-event'></i> Aktivitas
            </button>
            <button class="tab-btn" onclick="showTab('experience')">
                <i class='bx bx-briefcase'></i> Pengalaman
            </button>
            <button class="tab-btn" onclick="showTab('skills')">
                <i class='bx bx-code-alt'></i> Skills
            </button>
            <button class="tab-btn" onclick="showTab('messages')">
                <i class='bx bx-message'></i> Messages
            </button>
            <button class="tab-btn" onclick="showTab('articles')">
                <i class='bx bx-book'></i> Articles
            </button>
            <button class="tab-btn" onclick="showTab('portfolio')">
                <i class='bx bx-briefcase'></i> Portfolio
            </button>
        </div>

        

        <!-- Activities Tab -->
        <div id="activities" class="tab-content active">
            <div class="admin-container">
                <h3><i class='bx bx-calendar-event'></i> Kelola Aktivitas</h3>
                
                <div class="form-container">
                    <h4><?php echo $edit_activity ? 'Edit Aktivitas' : 'Tambah Aktivitas Baru'; ?></h4>
                    
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="<?php echo $edit_activity ? 'edit_activity' : 'add_activity'; ?>">
                        <?php if ($edit_activity): ?>
                            <input type="hidden" name="id" value="<?php echo $edit_activity['id']; ?>">
                        <?php endif; ?>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="title">Judul Aktivitas</label>
                                <input type="text" id="title" name="title" required 
                                       value="<?php echo $edit_activity ? htmlspecialchars($edit_activity['title']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="icon">Icon (Boxicons class)</label>
                                <input type="text" id="icon" name="icon" required 
                                       value="<?php echo $edit_activity ? htmlspecialchars($edit_activity['icon']) : ''; ?>"
                                       placeholder="bx-code-alt">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea id="description" name="description" rows="3" required><?php echo $edit_activity ? htmlspecialchars($edit_activity['description']) : ''; ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="link">Link (opsional)</label>
                            <input type="text" id="link" name="link" 
                                   value="<?php echo $edit_activity ? htmlspecialchars($edit_activity['link']) : ''; ?>"
                                   placeholder="#contact">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class='bx bx-save'></i> <?php echo $edit_activity ? 'Update' : 'Tambah'; ?> Aktivitas
                        </button>
                        
                        <?php if ($edit_activity): ?>
                            <a href="dashboard.php#activities" class="btn btn-secondary">
                                <i class='bx bx-x'></i> Batal
                            </a>
                        <?php endif; ?>
                    </form>
                </div>

                <h4>Daftar Aktivitas</h4>
                
                <?php if (empty($activities)): ?>
                    <p style="text-align: center; color: #666; padding: 2rem;">Belum ada aktivitas.</p>
                <?php else: ?>
                    <?php foreach ($activities as $activity): ?>
                        <div class="item">
                            <div class="item-header">
                                <div class="item-title">
                                    <i class='bx <?php echo htmlspecialchars($activity['icon']); ?>'></i>
                                    <?php echo htmlspecialchars($activity['title']); ?>
                                </div>
                                <div class="item-actions">
                                    <a href="?edit_activity=<?php echo $activity['id']; ?>" class="btn btn-secondary">
                                        <i class='bx bx-edit'></i> Edit
                                    </a>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus aktivitas ini?')">
                                        <input type="hidden" name="action" value="delete_activity">
                                        <input type="hidden" name="id" value="<?php echo $activity['id']; ?>">
                                        <button type="submit" class="btn btn-danger">
                                            <i class='bx bx-trash'></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <p style="color: #666; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($activity['description']); ?></p>
                            <small style="color: #999;">
                                Dibuat: <?php echo date('d M Y, H:i', strtotime($activity['created_at'])); ?>
                            </small>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Experience Tab -->
        <div id="experience" class="tab-content">
            <div class="admin-container">
                <h3><i class='bx bx-briefcase'></i> Kelola Pengalaman</h3>
                
                <div class="form-container">
                    <h4><?php echo $edit_experience ? 'Edit Pengalaman' : 'Tambah Pengalaman Baru'; ?></h4>
                    
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="<?php echo $edit_experience ? 'edit_experience' : 'add_experience'; ?>">
                        <?php if ($edit_experience): ?>
                            <input type="hidden" name="id" value="<?php echo $edit_experience['id']; ?>">
                        <?php endif; ?>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="position">Posisi</label>
                                <input type="text" id="position" name="position" required 
                                       value="<?php echo $edit_experience ? htmlspecialchars($edit_experience['position']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="company">Perusahaan</label>
                                <input type="text" id="company" name="company" required 
                                       value="<?php echo $edit_experience ? htmlspecialchars($edit_experience['company']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="period">Periode</label>
                                <input type="text" id="period" name="period" required 
                                       value="<?php echo $edit_experience ? htmlspecialchars($edit_experience['period']) : ''; ?>"
                                       placeholder="2020 - 2022">
                            </div>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="start_date">Tanggal Mulai</label>
                                <input type="date" id="start_date" name="start_date" 
                                       value="<?php echo $edit_experience ? $edit_experience['start_date'] : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="end_date">Tanggal Selesai (kosongkan jika masih aktif)</label>
                                <input type="date" id="end_date" name="end_date" 
                                       value="<?php echo $edit_experience ? $edit_experience['end_date'] : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="exp_description">Deskripsi</label>
                            <textarea id="exp_description" name="description" rows="4" required><?php echo $edit_experience ? htmlspecialchars($edit_experience['description']) : ''; ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class='bx bx-save'></i> <?php echo $edit_experience ? 'Update' : 'Tambah'; ?> Pengalaman
                        </button>
                        
                        <?php if ($edit_experience): ?>
                            <a href="dashboard.php#experience" class="btn btn-secondary">
                                <i class='bx bx-x'></i> Batal
                            </a>
                        <?php endif; ?>
                    </form>
                </div>

                <h4>Daftar Pengalaman</h4>
                
                <?php if (empty($experiences)): ?>
                    <p style="text-align: center; color: #666; padding: 2rem;">Belum ada pengalaman.</p>
                <?php else: ?>
                    <?php foreach ($experiences as $experience): ?>
                        <div class="item">
                            <div class="item-header">
                                <div class="item-title">
                                    <?php echo htmlspecialchars($experience['position']); ?> - <?php echo htmlspecialchars($experience['company']); ?>
                                    <span style="color: #b71c1c; font-weight: normal;">
                                        (<?php echo $experience['period']; ?>)
                                    </span>
                                </div>
                                <div class="item-actions">
                                    <a href="?edit_experience=<?php echo $experience['id']; ?>" class="btn btn-secondary">
                                        <i class='bx bx-edit'></i> Edit
                                    </a>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus pengalaman ini?')">
                                        <input type="hidden" name="action" value="delete_experience">
                                        <input type="hidden" name="id" value="<?php echo $experience['id']; ?>">
                                        <button type="submit" class="btn btn-danger">
                                            <i class='bx bx-trash'></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <p style="color: #666; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($experience['description']); ?></p>
                            <small style="color: #999;">
                                Dibuat: <?php echo date('d M Y, H:i', strtotime($experience['created_at'])); ?>
                            </small>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Skills Tab -->
        <div id="skills" class="tab-content">
            <div class="admin-container">
                <h3><i class='bx bx-code-alt'></i> Kelola Skills</h3>
                
                <div class="form-container">
                    <h4><?php echo $edit_skill ? 'Edit Skill' : 'Tambah Skill Baru'; ?></h4>
                    
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="<?php echo $edit_skill ? 'edit_skill' : 'add_skill'; ?>">
                        <?php if ($edit_skill): ?>
                            <input type="hidden" name="id" value="<?php echo $edit_skill['id']; ?>">
                        <?php endif; ?>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="skill_name">Nama Skill</label>
                                <input type="text" id="skill_name" name="name" required 
                                       value="<?php echo $edit_skill ? htmlspecialchars($edit_skill['name']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="percentage">Persentase (0-100)</label>
                                <input type="number" id="percentage" name="percentage" min="0" max="100" required 
                                       value="<?php echo $edit_skill ? $edit_skill['percentage'] : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="category">Kategori</label>
                                <select id="category" name="category" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="Frontend" <?php echo ($edit_skill && $edit_skill['category'] == 'Frontend') ? 'selected' : ''; ?>>Frontend</option>
                                    <option value="Backend" <?php echo ($edit_skill && $edit_skill['category'] == 'Backend') ? 'selected' : ''; ?>>Backend</option>
                                    <option value="Database" <?php echo ($edit_skill && $edit_skill['category'] == 'Database') ? 'selected' : ''; ?>>Database</option>
                                    <option value="Tools" <?php echo ($edit_skill && $edit_skill['category'] == 'Tools') ? 'selected' : ''; ?>>Tools</option>
                                    <option value="Cloud" <?php echo ($edit_skill && $edit_skill['category'] == 'Cloud') ? 'selected' : ''; ?>>Cloud</option>
                                </select>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class='bx bx-save'></i> <?php echo $edit_skill ? 'Update' : 'Tambah'; ?> Skill
                        </button>
                        
                        <?php if ($edit_skill): ?>
                            <a href="dashboard.php#skills" class="btn btn-secondary">
                                <i class='bx bx-x'></i> Batal
                            </a>
                        <?php endif; ?>
                    </form>
                </div>

                <h4>Daftar Skills</h4>
                
                <?php if (empty($skills)): ?>
                    <p style="text-align: center; color: #666; padding: 2rem;">Belum ada skills.</p>
                <?php else: ?>
                    <?php foreach ($skills as $skill): ?>
                        <div class="item">
                            <div class="item-header">
                                <div class="item-title">
                                    <?php echo htmlspecialchars($skill['name']); ?>
                                    <span style="color: #b71c1c; font-weight: normal;">
                                        (<?php echo $skill['percentage']; ?>% - <?php echo $skill['category']; ?>)
                                    </span>
                                </div>
                                <div class="item-actions">
                                    <a href="?edit_skill=<?php echo $skill['id']; ?>" class="btn btn-secondary">
                                        <i class='bx bx-edit'></i> Edit
                                    </a>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus skill ini?')">
                                        <input type="hidden" name="action" value="delete_skill">
                                        <input type="hidden" name="id" value="<?php echo $skill['id']; ?>">
                                        <button type="submit" class="btn btn-danger">
                                            <i class='bx bx-trash'></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Messages Tab -->
        <div id="messages" class="tab-content">
            <div class="admin-container">
                <h3><i class='bx bx-message'></i> Messages</h3>
                
                <?php if (empty($messages)): ?>
                    <p style="text-align: center; color: #666; padding: 2rem;">Belum ada pesan.</p>
                <?php else: ?>
                    <?php foreach ($messages as $message): ?>
                        <div class="item <?php echo $message['read_at'] ? '' : 'unread'; ?>">
                            <div class="item-header">
                                <div class="item-title">
                                    <?php echo htmlspecialchars($message['name']); ?>
                                    <?php if (!$message['read_at']): ?>
                                        <span style="background: #ff4444; color: white; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.7rem; margin-left: 0.5rem;">NEW</span>
                                    <?php endif; ?>
                                </div>
                                <div class="item-actions">
                                    <?php if (!$message['read_at']): ?>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="mark_read">
                                            <input type="hidden" name="id" value="<?php echo $message['id']; ?>">
                                            <button type="submit" class="btn btn-success">
                                                <i class='bx bx-check'></i> Mark Read
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus pesan ini?')">
                                        <input type="hidden" name="action" value="delete_message">
                                        <input type="hidden" name="id" value="<?php echo $message['id']; ?>">
                                        <button type="submit" class="btn btn-danger">
                                            <i class='bx bx-trash'></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($message['email']); ?></p>
                            <p><strong>Subject:</strong> <?php echo htmlspecialchars($message['subject']); ?></p>
                            <p><strong>Message:</strong></p>
                            <p style="background: #f8f9fa; padding: 1rem; border-radius: 5px; color: #666;">
                                <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                            </p>
                            <small style="color: #999;">
                                Diterima: <?php echo date('d M Y, H:i', strtotime($message['created_at'])); ?>
                                <?php if ($message['read_at']): ?>
                                    | Dibaca: <?php echo date('d M Y, H:i', strtotime($message['read_at'])); ?>
                                <?php endif; ?>
                            </small>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Articles Tab -->
        <div id="articles" class="tab-content">
            <div class="admin-container">
                <h3><i class='bx bx-book'></i> Kelola Articles</h3>
                
                <div class="form-container">
                    <h4><?php echo $edit_article ? 'Edit Article' : 'Tambah Article Baru'; ?></h4>
                    
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="<?php echo $edit_article ? 'edit_article' : 'add_article'; ?>">
                        <?php if ($edit_article): ?>
                            <input type="hidden" name="id" value="<?php echo $edit_article['id']; ?>">
                        <?php endif; ?>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="article_title">Judul Article</label>
                                <input type="text" id="article_title" name="title" required 
                                       value="<?php echo $edit_article ? htmlspecialchars($edit_article['title']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="article_category">Kategori</label>
                                <input type="text" id="article_category" name="category" required 
                                       value="<?php echo $edit_article ? htmlspecialchars($edit_article['category']) : ''; ?>"
                                       placeholder="Web Development">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="excerpt">Excerpt</label>
                            <textarea id="excerpt" name="excerpt" rows="2" required><?php echo $edit_article ? htmlspecialchars($edit_article['excerpt']) : ''; ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea id="content" name="content" rows="8" required><?php echo $edit_article ? htmlspecialchars($edit_article['content']) : ''; ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class='bx bx-save'></i> <?php echo $edit_article ? 'Update' : 'Tambah'; ?> Article
                        </button>
                        
                        <?php if ($edit_article): ?>
                            <a href="dashboard.php#articles" class="btn btn-secondary">
                                <i class='bx bx-x'></i> Batal
                            </a>
                        <?php endif; ?>
                    </form>
                </div>

                <h4>Daftar Articles</h4>
                
                <?php if (empty($articles)): ?>
                    <p style="text-align: center; color: #666; padding: 2rem;">Belum ada articles.</p>
                <?php else: ?>
                    <?php foreach ($articles as $article): ?>
                        <div class="item">
                            <div class="item-header">
                                <div class="item-title">
                                    <?php echo htmlspecialchars($article['title']); ?>
                                    <span style="color: #b71c1c; font-weight: normal;">
                                        (<?php echo $article['category']; ?>)
                                    </span>
                                </div>
                                <div class="item-actions">
                                    <a href="?edit_article=<?php echo $article['id']; ?>" class="btn btn-secondary">
                                        <i class='bx bx-edit'></i> Edit
                                    </a>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus article ini?')">
                                        <input type="hidden" name="action" value="delete_article">
                                        <input type="hidden" name="id" value="<?php echo $article['id']; ?>">
                                        <button type="submit" class="btn btn-danger">
                                            <i class='bx bx-trash'></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <p style="color: #666; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($article['excerpt']); ?></p>
                            <small style="color: #999;">
                                Dibuat: <?php echo date('d M Y, H:i', strtotime($article['created_at'])); ?>
                            </small>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Portfolio Tab -->
        <div id="portfolio" class="tab-content">
            <div class="admin-container">
                <h3><i class='bx bx-briefcase'></i> Kelola Portfolio</h3>
                
                <div class="form-container">
                    <h4><?php echo $edit_portfolio ? 'Edit Portfolio' : 'Tambah Portfolio Baru'; ?></h4>
                    
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="<?php echo $edit_portfolio ? 'edit_portfolio' : 'add_portfolio'; ?>">
                        <?php if ($edit_portfolio): ?>
                            <input type="hidden" name="id" value="<?php echo $edit_portfolio['id']; ?>">
                        <?php endif; ?>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="portfolio_title">Judul Portfolio</label>
                                <input type="text" id="portfolio_title" name="title" required 
                                       value="<?php echo $edit_portfolio ? htmlspecialchars($edit_portfolio['title']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="portfolio_category">Kategori</label>
                                <select id="portfolio_category" name="category" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="Web Development" <?php echo ($edit_portfolio && $edit_portfolio['category'] == 'Web Development') ? 'selected' : ''; ?>>Web Development</option>
                                    <option value="Mobile App" <?php echo ($edit_portfolio && $edit_portfolio['category'] == 'Mobile App') ? 'selected' : ''; ?>>Mobile App</option>
                                    <option value="UI/UX Design" <?php echo ($edit_portfolio && $edit_portfolio['category'] == 'UI/UX Design') ? 'selected' : ''; ?>>UI/UX Design</option>
                                    <option value="System Integration" <?php echo ($edit_portfolio && $edit_portfolio['category'] == 'System Integration') ? 'selected' : ''; ?>>System Integration</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="image_url">Image URL</label>
                                <input type="text" id="image_url" name="image_url" 
                                       value="<?php echo $edit_portfolio ? htmlspecialchars($edit_portfolio['image_url']) : ''; ?>"
                                       placeholder="img/work1.jpg">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="portfolio_description">Deskripsi</label>
                            <textarea id="portfolio_description" name="description" rows="3" required><?php echo $edit_portfolio ? htmlspecialchars($edit_portfolio['description']) : ''; ?></textarea>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="project_url">Project URL (opsional)</label>
                                <input type="url" id="project_url" name="project_url" 
                                       value="<?php echo $edit_portfolio ? htmlspecialchars($edit_portfolio['project_url']) : ''; ?>"
                                       placeholder="https://example.com">
                            </div>
                            
                            <div class="form-group">
                                <label for="github_url">GitHub URL (opsional)</label>
                                <input type="url" id="github_url" name="github_url" 
                                       value="<?php echo $edit_portfolio ? htmlspecialchars($edit_portfolio['github_url']) : ''; ?>"
                                       placeholder="https://github.com/user/repo">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="technologies">Technologies</label>
                            <input type="text" id="technologies" name="technologies" 
                                   value="<?php echo $edit_portfolio ? htmlspecialchars($edit_portfolio['technologies']) : ''; ?>"
                                   placeholder="React.js, Node.js, MongoDB">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class='bx bx-save'></i> <?php echo $edit_portfolio ? 'Update' : 'Tambah'; ?> Portfolio
                        </button>
                        
                        <?php if ($edit_portfolio): ?>
                            <a href="dashboard.php#portofolio" class="btn btn-secondary">
                                <i class='bx bx-x'></i> Batal
                            </a>
                        <?php endif; ?>
                    </form>
                </div>

                <h4>Daftar Portfolio</h4>
                
                <?php if (empty($portfolio_items)): ?>
                    <p style="text-align: center; color: #666; padding: 2rem;">Belum ada portfolio.</p>
                <?php else: ?>
                    <?php foreach ($portfolio_items as $item): ?>
                        <div class="item">
                            <div class="item-header">
                                <div class="item-title">
                                    <?php echo htmlspecialchars($item['title']); ?>
                                    <span style="color: #b71c1c; font-weight: normal;">
                                        (<?php echo $item['category']; ?>)
                                    </span>
                                </div>
                                <div class="item-actions">
                                    <a href="?edit_portfolio=<?php echo $item['id']; ?>" class="btn btn-secondary">
                                        <i class='bx bx-edit'></i> Edit
                                    </a>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus portfolio ini?')">
                                        <input type="hidden" name="action" value="delete_portfolio">
                                        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" class="btn btn-danger">
                                            <i class='bx bx-trash'></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <p style="color: #666; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($item['description']); ?></p>
                            <p style="color: #999; font-size: 0.9rem; margin-bottom: 0.5rem;">
                                <strong>Technologies:</strong> <?php echo htmlspecialchars($item['technologies']); ?>
                            </p>
                            <small style="color: #999;">
                                Dibuat: <?php echo date('d M Y, H:i', strtotime($item['created_at'])); ?>
                            </small>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tab contents
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => {
                content.classList.remove('active');
            });
            
            // Remove active class from all tab buttons
            const tabBtns = document.querySelectorAll('.tab-btn');
            tabBtns.forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab content
            document.getElementById(tabName).classList.add('active');
            
            // Add active class to clicked button
            event.target.classList.add('active');
            
            // Update URL hash without reloading
            window.location.hash = tabName;
        }
        
        // Load tab from URL hash on page load
        document.addEventListener('DOMContentLoaded', function() {
            const hash = window.location.hash.substring(1);
            if (hash && document.getElementById(hash)) {
                showTab(hash);
            }
        });
    </script>
</body>
</html>
