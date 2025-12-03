<?php
session_start();
require 'config.php';

$error = '';
$showRegister = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nickName = trim($_POST['nickName'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($nickName) || empty($password)) {
        $error = 'Please enter both nickname and password.';
    } 
    
    else {
        $stmt = $conn->prepare("SELECT userId, passwordHash, passwordSalt FROM users WHERE nickName = ?");
        $stmt->bind_param("s", $nickName);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $storedHash = $row['passwordHash'];
            $salt = $row['passwordSalt'];
            $hashedPassword = hash('sha256', $password . $salt);
            
            if ($hashedPassword === $storedHash) {
                $_SESSION['userId'] = $row['userId'];
                $_SESSION['nickName'] = $nickName;
                header('Location: index.php');
                exit();
            } 
            
            else {
                $error = 'Invalid password. Please try again.';
            }
        } 
        
        else {
            $showRegister = true;
            $error = 'User not found. Please register below.';
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Project Manager</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: bold;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.1);
        }
        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
        }
        button:hover {
            transform: translateY(-2px);
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
        .register-section {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #eee;
        }
        .register-section h2 {
            font-size: 20px;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Project Manager</h1>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="nickName">Nickname:</label>
                <input type="text" id="nickName" name="nickName" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        
        <?php if ($showRegister): ?>
            <div class="register-section">
                <h2>Create New Account</h2>
                <p style="text-align: center; color: #666; margin-bottom: 20px;">
                    <a href="register.php" style="color: #667eea; text-decoration: none; font-weight: bold;">Click here to register</a>
                </p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>