<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Secure Login</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .form-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { width: 100%; padding: 10px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #007bff; color: white; padding: 10px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%; }
        .msg { padding: 10px; border-radius: 4px; margin-bottom: 15px; text-align: center; font-weight: bold; }
        .error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
<div class="form-container">
    <h2 style="text-align: center;">Secure Login</h2>
    
    <?php if(!empty($message)) echo $message; ?>

    <form method="POST" action="index.php?action=login">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        
        <div class="form-group">
            <label>Email Address:</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Login</button>

        <p style="text-align: center; margin-top: 15px;">
            Don't have an account? <a href="index.php?action=register">Register here</a>
        </p>
    </form>
</div>
</body>
</html>