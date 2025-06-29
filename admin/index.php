<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Redirect to new dashboard
header('Location: dashboard.php');
exit;

// Get dashboard statistics
try {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM messages");
    $total_messages = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM messages WHERE read_at IS NULL");
    $unread_messages = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Get recent messages
    $stmt = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC LIMIT 5");
    $recent_messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $error = "Error loading dashboard data: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .dashboard-card {
            background: #fff;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        
        .dashboard-card h3 {
            color: #333;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }
        
        .dashboard-card .number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #b71c1c;
            margin-bottom: 0.5rem;
        }
        
        .dashboard-card .label {
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .dashboard-card .icon {
            font-size: 3rem;
            color: #b71c1c;
            opacity: 0.1;
            float: right;
            margin-top: -2rem;
        }
        
        .recent-messages {
            background: #fff;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .recent-messages h3 {
            color: #333;
            margin-bottom: 1.5rem;
            font-size: 1.3rem;
        }
        
        .message-item {
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
            transition: background 0.3s ease;
        }
        
        .message-item:hover {
            background: #f8f9fa;
        }
        
        .message-item:last-child {
            border-bottom: none;
        }
        
        .message-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.25rem;
        }
        
        .message-subject {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }
        
        .message-date {
            color: #999;
            font-size: 0.8rem;
        }
        
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .action-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem;
            background: #fff;
            color: #333;
            text-decoration: none;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            font-weight: 600;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            color: #b71c1c;
        }
        
        .action-btn i {
            font-size: 1.2rem;
        }
        
        @media (max-width: 768px) {
            .admin-header .container {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <div class="container">
            <h1 class="admin-title">
                <i class='bx bx-dashboard'></i> Admin Dashboard
            </h1>
            <div class="admin-nav">
                <a href="messages.php"><i class='bx bx-message'></i> Messages</a>
                <a href="../index.php"><i class='bx bx-home'></i> Website</a>
                <a href="logout.php"><i class='bx bx-log-out'></i> Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <div class="number"><?php echo $total_messages; ?></div>
                <div class="label">Total Messages</div>
                <i class='bx bx-message icon'></i>
            </div>
            
            <div class="dashboard-card">
                <div class="number"><?php echo $unread_messages; ?></div>
                <div class="label">Unread Messages</div>
                <i class='bx bx-bell icon'></i>
            </div>
            
            <div class="dashboard-card">
                <div class="number"><?php echo date('d'); ?></div>
                <div class="label">Today</div>
                <i class='bx bx-calendar icon'></i>
            </div>
            
            <div class="dashboard-card">
                <div class="number"><?php echo date('H:i'); ?></div>
                <div class="label">Current Time</div>
                <i class='bx bx-time icon'></i>
            </div>
        </div>

        <div class="recent-messages">
            <h3><i class='bx bx-envelope'></i> Recent Messages</h3>
            
            <?php if (empty($recent_messages)): ?>
                <p style="color: #666; text-align: center; padding: 2rem;">No messages yet.</p>
            <?php else: ?>
                <?php foreach ($recent_messages as $message): ?>
                    <div class="message-item">
                        <div class="message-name"><?php echo htmlspecialchars($message['name']); ?></div>
                        <div class="message-subject"><?php echo htmlspecialchars($message['subject']); ?></div>
                        <div class="message-date"><?php echo date('M j, Y - H:i', strtotime($message['created_at'])); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="quick-actions">
            <a href="messages.php" class="action-btn">
                <i class='bx bx-message-dots'></i>
                <span>View All Messages</span>
            </a>
            <a href="activities.php" class="action-btn">
                <i class='bx bx-calendar-event'></i>
                <span>Kelola Aktivitas</span>
            </a>
            <a href="../contact.php" class="action-btn">
                <i class='bx bx-envelope'></i>
                <span>Contact Form</span>
            </a>
            <a href="../index.php" class="action-btn">
                <i class='bx bx-home'></i>
                <span>Visit Website</span>
            </a>
            <a href="logout.php" class="action-btn">
                <i class='bx bx-log-out'></i>
                <span>Logout</span>
            </a>
        </div>
    </div>
</body>
</html>
