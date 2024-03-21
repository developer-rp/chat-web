<?php
require 'db.php';

// Validate and sanitize input
$uname = isset($_POST['uname']) ? mysqli_real_escape_string($conn, $_POST['uname']) : '';
$pw = isset($_POST['pw']) ? mysqli_real_escape_string($conn, $_POST['pw']) : '';

// Check if username or password is empty
if (empty($uname) || empty($pw)) {
    header("Location: index.php?alert=3"); // Redirect with an error alert
    exit;
}

// Check if the username already exists in the database
$sql_check_username = "SELECT * FROM users WHERE username = ?";
$stmt_check_username = mysqli_prepare($conn, $sql_check_username);
mysqli_stmt_bind_param($stmt_check_username, 's', $uname);
mysqli_stmt_execute($stmt_check_username);
$result_check_username = mysqli_stmt_get_result($stmt_check_username);

if (mysqli_num_rows($result_check_username) > 0) {
    header("Location: index.php?alert=2"); // Username already exists alert
    exit;
} else {
    // Username doesn't exist, insert new user
    $hashed_pw = password_hash($pw, PASSWORD_DEFAULT); // Hash the password

    $sql_insert_user = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt_insert_user = mysqli_prepare($conn, $sql_insert_user);
    mysqli_stmt_bind_param($stmt_insert_user, 'ss', $uname, $hashed_pw);
    mysqli_stmt_execute($stmt_insert_user);

    header("Location: index.php?alert=1"); // Successful registration alert
    exit;
}
?>
