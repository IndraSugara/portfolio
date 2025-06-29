<?php
session_start();

// Check if logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../database/init_db.php';

// Mark message as read
if (isset($_GET['mark_read']) && is_numeric($_GET['mark_read'])) {
    $stmt = $pdo->prepare("UPDATE messages SET read_at = CURRENT_TIMESTAMP WHERE id = ?");
    $stmt->execute([$_GET['mark_read']]);
    header('Location: messages.php');
    exit;
}

// Delete message
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header('Location: messages.php');
    exit;
}

// Get all messages
try {
    $stmt = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC");
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM messages WHERE read_at IS NULL");
    $unread_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
} catch (PDOException $e) {
    $error = "Error loading messages: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Admin Dashboard</title>
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
        
        .messages-container {
            background: #fff;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .messages-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .messages-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
            margin: 0;
        }
        
        .unread-badge {
            background: #ff4444;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .message-item {
            background: #f9f9f9;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #ddd;
            transition: all 0.3s ease;
        }
        
        .message-item.unread {
            border-left-color: #b71c1c;
            background: #fff9f9;
        }
        
        .message-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .message-sender {
            font-weight: 700;
            color: #333;
            font-size: 1.1rem;
        }
        
        .message-date {
            color: #666;
            font-size: 0.9rem;
        }
        
        .message-subject {
            font-weight: 600;
            color: #b71c1c;
            margin-bottom: 0.75rem;
            font-size: 1rem;
        }
        
        .message-content {
            color: #555;
            line-height: 1.6;
            margin-bottom: 1rem;
        }
        
        .message-meta {
            display: flex;
            gap: 0.5rem;
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        
        .message-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-action {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .btn-read {
            background: #4CAF50;
            color: white;
        }
        
        .btn-read:hover {
            background: #45a049;
        }
        
        .btn-delete {
            background: #f44336;
            color: white;
        }
        
        .btn-delete:hover {
            background: #da190b;
        }
        
        .no-messages {
            text-align: center;
            padding: 3rem;
            color: #666;
        }
        
        .no-messages i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #ddd;
        }
        
        @media (max-width: 768px) {
            .message-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            
            .message-actions {
                flex-direction: column;
            }
            
            .admin-header .container {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <div class="container">
            <h1 class="admin-title">
                <i class='bx bx-message'></i> Messages
            </h1>
            <div class="admin-nav">
                <a href="index.php"><i class='bx bx-dashboard'></i> Dashboard</a>
                <a href="activities.php"><i class='bx bx-calendar-event'></i> Aktivitas</a>
                <a href="../index.php"><i class='bx bx-home'></i> Website</a>
                <a href="logout.php"><i class='bx bx-log-out'></i> Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="messages-container">
            <div class="messages-header">
                <h2 class="messages-title">
                    <i class='bx bx-inbox'></i> Inbox
                </h2>
                <?php if ($unread_count > 0): ?>
                    <span class="unread-badge"><?php echo $unread_count; ?> unread</span>
                <?php endif; ?>
            </div>

            <?php if (empty($messages)): ?>
                <div class="no-messages">
                    <i class='bx bx-inbox'></i>
                    <h3>No messages yet</h3>
                    <p>When visitors contact you through the website, their messages will appear here.</p>
                </div>
            <?php else: ?>
                <?php foreach ($messages as $message): ?>
                    <div class="message-item <?php echo $message['read_at'] ? '' : 'unread'; ?>">
                        <div class="message-header">
                            <div class="message-sender">
                                <i class='bx bx-user'></i> <?php echo htmlspecialchars($message['name']); ?>
                            </div>
                            <div class="message-date">
                                <i class='bx bx-time'></i> <?php echo date('d M Y, H:i', strtotime($message['created_at'])); ?>
                            </div>
                        </div>
                        
                        <div class="message-subject">
                            <i class='bx bx-envelope'></i> <?php echo htmlspecialchars($message['subject']); ?>
                        </div>
                        
                        <div class="message-meta">
                            <span><i class='bx bx-envelope'></i> <?php echo htmlspecialchars($message['email']); ?></span>
                            <?php if (!$message['read_at']): ?>
                                <span style="color: #b71c1c; font-weight: 600;"><i class='bx bx-bell'></i> Unread</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="message-content">
                            <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                        </div>
                        
                        <div class="message-actions">
                            <?php if (!$message['read_at']): ?>
                                <a href="?mark_read=<?php echo $message['id']; ?>" class="btn-action btn-read">
                                    <i class='bx bx-check'></i> Mark as Read
                                </a>
                            <?php endif; ?>
                            <a href="?delete=<?php echo $message['id']; ?>" class="btn-action btn-delete" 
                               onclick="return confirm('Are you sure you want to delete this message?')">
                                <i class='bx bx-trash'></i> Delete
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>