<?php
session_start();

// Check if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit;
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../database/init_db.php';
    
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    try {
        $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $user['id'];
            header('Location: index.php');
            exit;
        } else {
            $error = "Username atau password salah";
        }
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #b71c1c 0%, #8b0000 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 30% 40%, rgba(255, 235, 59, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.05) 0%, transparent 50%),
                        radial-gradient(circle at 40% 80%, rgba(255, 235, 59, 0.08) 0%, transparent 50%);
            z-index: -1;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            z-index: 1;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 3rem 2.5rem;
            border-radius: 20px;
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.25),
                0 0 0 1px rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 235, 59, 0.3);
            transform: translateY(0);
            transition: all 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 
                0 35px 70px rgba(0, 0, 0, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.2);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .login-header h1 {
            color: #b71c1c;
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .login-header p {
            color: #666;
            font-size: 1rem;
            opacity: 0.8;
        }

        .login-form .form-group {
            margin-bottom: 1.8rem;
            position: relative;
        }

        .login-form label {
            display: block;
            margin-bottom: 0.6rem;
            color: #333;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .login-form input {
            width: 100%;
            padding: 1rem 1.2rem;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.9);
            color: #333;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .login-form input:focus {
            outline: none;
            border-color: #b71c1c;
            background: #fff;
            box-shadow: 
                inset 0 2px 4px rgba(0, 0, 0, 0.05),
                0 0 0 4px rgba(183, 28, 28, 0.1);
            transform: translateY(-2px);
        }

        .login-form button {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(145deg, #b71c1c, #8b0000);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
        }

        .login-form button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .login-form button:hover::before {
            left: 100%;
        }

        .login-form button:hover {
            background: linear-gradient(145deg, #8b0000, #b71c1c);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(183, 28, 28, 0.4);
        }

        .login-form button:active {
            transform: translateY(-1px);
        }

        .error-message {
            background: linear-gradient(135deg, #ffebee, #ffcdd2);
            color: #c62828;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            text-align: center;
            border: 1px solid #ffcdd2;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(198, 40, 40, 0.15);
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }
            
            .login-header h1 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>Admin Login</h1>
                <p>Silakan login untuk mengakses dashboard admin</p>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form class="login-form" method="POST" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>