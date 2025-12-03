<?php
session_start();
require 'config.php';

if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['userId'];
$error = '';
$success = false;
$newProjectId = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projectName = trim($_POST['projectName'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $members = $_POST['members'] ?? [];
    
    if (empty($projectName) || empty($description)) {
        $error = 'Project name and description are required.';
    } 
    
    elseif (count($members) < 3) {
        $error = 'A project must have at least 3 members.';
    } 
    
    else {
        $stmt = $conn->prepare("SELECT projectId FROM projects WHERE name = ?");
        $stmt->bind_param("s", $projectName);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = 'A project with this name already exists. Please choose a different name.';
        } 
        
        else {
            $stmt = $conn->prepare("INSERT INTO projects (name, description) VALUES (?, ?)");
            $stmt->bind_param("ss", $projectName, $description);
            
            if ($stmt->execute()) {
                $newProjectId = $conn->insert_id;
                $stmt = $conn->prepare("INSERT INTO projectMembership (projectId, memberId) VALUES (?, ?)");
                $allMembersAdded = true;
                
                foreach ($members as $memberId) {
                    $memberId = (int)$memberId;
                    $stmt->bind_param("ii", $newProjectId, $memberId);
                    if (!$stmt->execute()) {
                        $allMembersAdded = false;
                        break;
                    }
                }
                
                if ($allMembersAdded) {
                    $success = true;
                } 
                
                else {
                    $error = 'Error adding project members.';
                }
            } 
            
            else {
                $error = 'Error creating project. Please try again.';
            }
        }
        $stmt->close();
    }
}

$stmt = $conn->prepare("SELECT userId, firstName, lastName, nickName FROM users ORDER BY firstName, lastName");
$stmt->execute();
$allUsers = $stmt->get_result();
$stmt->close();
$stmt = $conn->prepare("
    SELECT p.projectId, p.name, p.description, p.createdAt, COUNT(pm.memberId) as memberCount
    FROM projects p
    LEFT JOIN projectMembership pm ON p.projectId = pm.projectId
    GROUP BY p.projectId
    ORDER BY p.createdAt DESC
");
$stmt->execute();
$allProjects = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Project - Project Manager</title>
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
        .form-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        .form-section h2 {
            color: #333;
            margin-bottom: 20px;
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
        textarea,
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            font-family: Arial, sans-serif;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.1);
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        .members-container {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            max-height: 300px;
            overflow-y: auto;
            background: #f9f9f9;
        }
        .member-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .member-item:last-child {
            border-bottom: none;
        }
        .member-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 12px;
            cursor: pointer;
        }
        .member-item label {
            margin: 0;
            font-weight: normal;
            cursor: pointer;
            flex: 1;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
        button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
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
            border-left: 5px solid #ddd;
        }
        .project-card.new-project {
            border-left-color: #28a745;
            background: #f0f8f4;
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
        .member-count {
            background: #667eea;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <nav>
        <h1>Project Manager</h1>
        <a href="index.php">← Back to Dashboard</a>
    </nav>

    <div class="container">
        <div class="form-section">
            <h2>Add New Project</h2>
            
            <?php if ($error): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success">Project created successfully!</div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="projectName">Project Name:</label>
                    <input type="text" id="projectName" name="projectName" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
                
                <div class="form-group">
                    <label>Project Members (Select at least 3):</label>
                    <div class="members-container">
                        <?php while ($user = $allUsers->fetch_assoc()): ?>
                            <div class="member-item">
                                <input type="checkbox" id="member_<?php echo $user['userId']; ?>" 
                                       name="members[]" value="<?php echo $user['userId']; ?>">
                                <label for="member_<?php echo $user['userId']; ?>">
                                    <?php echo htmlspecialchars($user['firstName'] . ' ' . $user['lastName'] . ' (' . $user['nickName'] . ')'); ?>
                                </label>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                
                <button type="submit">Create Project</button>
            </form>
        </div>

        <div class="projects-section">
            <h3>All Projects</h3>
            <div class="projects-grid">
                <?php while ($project = $allProjects->fetch_assoc()): ?>
                    <div class="project-card <?php echo $project['projectId'] == $newProjectId ? 'new-project' : ''; ?>">
                        <h4><?php echo htmlspecialchars($project['name']); ?></h4>
                        <p><?php echo htmlspecialchars($project['description']); ?></p>
                        <small>Created: <?php echo date('M d, Y', strtotime($project['createdAt'])); ?></small>
                        <span class="member-count"><?php echo $project['memberCount']; ?> members</span>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</body>
</html>