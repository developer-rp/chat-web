<?php
// Start the session
session_start();

// Include database connection
require 'db.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $uname = $_POST['uname'];
    $password = $_POST['pw'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query using prepared statement to check user credentials
    $sql = "SELECT * FROM users WHERE username=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $uname);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if a user with the provided username exists
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        // Verify the password using password_verify()
        if (password_verify($password, $row['password'])) {
            // User found and password verified, set session variables and redirect to chat page
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $uname; // Use 'username' instead of 'email'
            header("Location: chat/index.php?uname=$uname"); // Redirect to chat page after successful login
            exit;
        }
    }

    // Invalid credentials or user not found, redirect back to login page with an error message
    header("Location: index.php?login_error=1");
    exit;
} else {
    // Redirect to login page if accessed directly
    header("Location: index.php");
    exit;
}
?>
