<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f9; padding: 50px; }
        .dashboard-card { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); max-width: 600px; margin: auto; text-align: center; }
        .logout-btn { display: inline-block; background: #dc3545; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: bold; margin-top: 20px; }
        .logout-btn:hover { background: #c82333; }
    </style>
</head>
<body>

<div class="dashboard-card">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8'); ?>! 🛡️</h1>
    <p>You have successfully logged .</p>
    
    <a href="index.php?action=logout" class="logout-btn">Log Out</a>
</div>

</body>
</html>