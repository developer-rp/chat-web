<?php

// Sanitize user input
$alertR = isset($_REQUEST['alert']) ? htmlspecialchars($_REQUEST['alert']) : null;
$alertL = isset($_REQUEST['login_error']) ? htmlspecialchars($_REQUEST['login_error']) : null;
$count = 0;

if ($alertR == 1 && $count == 0) {
    $message = "Successful Registered.";
    echo "<script type='text/javascript'>alert('$message');</script>";
    $count = 1;
}
if ($alertR == 2 && $count == 0) {
    $message = "Username Already Taken.";
    echo "<script type='text/javascript'>alert('$message');</script>";
    $count = 1;
}

if ($alertL == 1 && $count == 0) {
    $message = "You Are Not Registered Yet.";
    echo "<script type='text/javascript'>alert('$message');</script>";
    $count = 1;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat App</title>
    <link rel="icon" type="image/png" href="img/favicon.png">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Welcome to Our Chat</h2>
            <div class="buttons">
                <button onclick="toggleForm('login')">Login</button>
                <button onclick="toggleForm('signup')">Sign Up</button>
            </div>
            <form id="loginForm" class="form" action="login.php" method="POST">
                <input type="text" placeholder="Username" name="uname" required>
                <input type="password" placeholder="Password" name="pw" required>
                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                <button type="submit">Login</button>
            </form>
            <form id="signupForm" class="form" action="signup.php" method="POST">
                <input type="text" placeholder="Username" name="uname" required>
                <input type="password" placeholder="Password" name="pw" required>
                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                <button type="submit">Sign Up</button>
            </form>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>
