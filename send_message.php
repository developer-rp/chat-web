<?php
require '../db.php';

// Validate and sanitize user input
$uname = isset($_POST['uname']) ? mysqli_real_escape_string($conn, $_POST['uname']) : '';
$message = isset($_POST['message']) ? mysqli_real_escape_string($conn, $_POST['message']) : '';
$receiver = isset($_POST['receiver']) ? mysqli_real_escape_string($conn, $_POST['receiver']) : '';

// Check if required fields are not empty
if (empty($uname) || empty($message) || empty($receiver)) {
    // Handle the situation when required fields are empty (e.g., show an error message)
    echo 'Error: All fields are required.';
    exit;
}

// Insert message into the database using prepared statement
$sql1 = "INSERT INTO messages (`sender`, `message`, `receiver`) VALUES (?, ?, ?)";
$stmt1 = mysqli_prepare($conn, $sql1);
mysqli_stmt_bind_param($stmt1, 'sss', $uname, $message, $receiver);
$result1 = mysqli_stmt_execute($stmt1);

if ($result1) {
    // Message inserted successfully
    echo 'Message sent successfully.';
} else {
    // Handle the situation when the message insertion fails (e.g., show an error message)
    echo 'Error: Message sending failed.';
}

// Close the prepared statement and database connection
mysqli_stmt_close($stmt1);
mysqli_close($conn);
?>
