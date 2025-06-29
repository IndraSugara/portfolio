
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
            case 'add':
                $stmt = $pdo->prepare("INSERT INTO activities (title, description, icon, link) VALUES (?, ?, ?, ?)");
                $stmt->execute([$_POST['title'], $_POST['description'], $_POST['icon'], $_POST['link']]);
                $success = "Aktivitas berhasil ditambahkan!";
                break;
                
            case 'edit':
                $stmt = $pdo->prepare("UPDATE activities SET title = ?, description = ?, icon = ?, link = ? WHERE id = ?");
                $stmt->execute([$_POST['title'], $_POST['description'], $_POST['icon'], $_POST['link'], $_POST['id']]);
                $success = "Aktivitas berhasil diperbarui!";
                break;
                
            case 'delete':
                $stmt = $pdo->prepare("DELETE FROM activities WHERE id = ?");
                $stmt->execute([$_POST['id']]);
                $success = "Aktivitas berhasil dihapus!";
                break;
        }
    }
}

// Get all activities
try {
    $stmt = $pdo->query("SELECT * FROM activities ORDER BY created_at DESC");
    $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error loading activities: " . $e->getMessage();
}

// Get activity for editing
$edit_activity = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM activities WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_activity = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Aktivitas - Admin Dashboard</title>
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
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
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
        
        .activity-item {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            border-left: 4px solid #b71c1c;
        }
        
        .activity-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .activity-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
        }
        
        .activity-actions {
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
        
        .icon-preview {
            font-size: 1.5rem;
            color: #b71c1c;
            margin-right: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .admin-header .container {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .activity-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .activity-actions {
                width: 100%;
                justify-content: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <div class="container">
            <h1 class="admin-title">
                <i class='bx bx-calendar-event'></i> Kelola Aktivitas
            </h1>
            <div class="admin-nav">
                <a href="index.php"><i class='bx bx-dashboard'></i> Dashboard</a>
                <a href="messages.php"><i class='bx bx-message'></i> Messages</a>
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

        <div class="admin-container">
            <div class="form-container">
                <h3><?php echo $edit_activity ? 'Edit Aktivitas' : 'Tambah Aktivitas Baru'; ?></h3>
                
                <form method="POST" action="">
                    <input type="hidden" name="action" value="<?php echo $edit_activity ? 'edit' : 'add'; ?>">
                    <?php if ($edit_activity): ?>
                        <input type="hidden" name="id" value="<?php echo $edit_activity['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="title">Judul Aktivitas</label>
                        <input type="text" id="title" name="title" required 
                               value="<?php echo $edit_activity ? htmlspecialchars($edit_activity['title']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea id="description" name="description" rows="3" required><?php echo $edit_activity ? htmlspecialchars($edit_activity['description']) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="icon">Icon (Boxicons class, contoh: bx-code-alt)</label>
                        <input type="text" id="icon" name="icon" required 
                               value="<?php echo $edit_activity ? htmlspecialchars($edit_activity['icon']) : ''; ?>"
                               placeholder="bx-code-alt">
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
                        <a href="activities.php" class="btn btn-secondary">
                            <i class='bx bx-x'></i> Batal
                        </a>
                    <?php endif; ?>
                </form>
            </div>

            <h3>Daftar Aktivitas</h3>
            
            <?php if (empty($activities)): ?>
                <p style="text-align: center; color: #666; padding: 2rem;">Belum ada aktivitas.</p>
            <?php else: ?>
                <?php foreach ($activities as $activity): ?>
                    <div class="activity-item">
                        <div class="activity-header">
                            <div class="activity-title">
                                <i class='bx <?php echo htmlspecialchars($activity['icon']); ?> icon-preview'></i>
                                <?php echo htmlspecialchars($activity['title']); ?>
                            </div>
                            <div class="activity-actions">
                                <a href="?edit=<?php echo $activity['id']; ?>" class="btn btn-secondary">
                                    <i class='bx bx-edit'></i> Edit
                                </a>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus aktivitas ini?')">
                                    <input type="hidden" name="action" value="delete">
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
</body>
</html>
