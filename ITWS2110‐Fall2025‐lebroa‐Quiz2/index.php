<?php
session_start();
require 'config.php';

if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['userId'];
$nickName = $_SESSION['nickName'];
$stmt = $conn->prepare("
    SELECT DISTINCT p.projectId, p.name, p.description, p.createdAt
    FROM projects p
    JOIN projectMembership pm ON p.projectId = pm.projectId
    WHERE pm.memberId = ?
    ORDER BY p.createdAt DESC
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$userProjects = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Project Manager</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }
        nav {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px 40px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        nav h1 {
            font-size: 24px;
        }
        nav .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        nav a {
            color: white;
            text-decoration: none;
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 16px;
            border-radius: 5px;
            transition: background 0.3s;
        }
        nav a:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .welcome {
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .welcome h2 {
            color: #333;
            margin-bottom: 10px;
        }
        .welcome p {
            color: #666;
        }
        .actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        .action-btn {
            background: white;
            border: 2px solid #667eea;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            color: #667eea;
            font-weight: bold;
            display: block;
        }
        .action-btn:hover {
            background: #667eea;
            color: white;
            transform: translateY(-5px);
        }
        .projects-section h3 {
            color: #333;
            margin: 30px 0 20px 0;
            font-size: 22px;
        }
        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .project-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }
        .project-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }
        .project-card h4 {
            color: #333;
            margin-bottom: 10px;
            font-size: 18px;
        }
        .project-card p {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
            line-height: 1.5;
        }
        .project-card small {
            color: #999;
            display: block;
        }
        .no-projects {
            text-align: center;
            padding: 40px;
            color: #999;
            background: white;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <nav>
        <h1>Project Manager</h1>
        <div class="user-info">
            <span>Welcome, <?php echo htmlspecialchars($nickName); ?></span>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="welcome">
            <h2>Dashboard</h2>
            <p>Manage your projects and collaborate with team members.</p>
        </div>

        <div class="actions">
            <a href="projects.php" class="action-btn">+ Add Project</a>
        </div>

        <div class="projects-section">
            <h3>Your Projects</h3>
            <?php if ($userProjects->num_rows > 0): ?>
                <div class="projects-grid">
                    <?php while ($project = $userProjects->fetch_assoc()): ?>
                        <div class="project-card">
                            <h4><?php echo htmlspecialchars($project['name']); ?></h4>
                            <p><?php echo htmlspecialchars($project['description']); ?></p>
                            <small>Created: <?php echo date('M d, Y', strtotime($project['createdAt'])); ?></small>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="no-projects">
                    <p>You are not a member of any projects yet.</p>
                    <p><a href="projects.php" style="color: #667eea; text-decoration: none; font-weight: bold;">Create your first project</a></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>