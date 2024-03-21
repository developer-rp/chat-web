<?php
require '../db.php';

// Validate and sanitize input parameters
$uname = isset($_GET['uname']) ? mysqli_real_escape_string($conn, $_GET['uname']) : '';
$receiver = isset($_GET['receiver']) ? mysqli_real_escape_string($conn, $_GET['receiver']) : '';

// Check if required parameters are present and not empty
if (empty($uname) || empty($receiver)) {
    echo '<div class="error-message">Error: Missing parameters.</div>';
    exit;
}

// Prepare and execute the SQL query using a prepared statement
$sql = "SELECT * FROM messages WHERE (sender = ? AND receiver = ?) OR (sender = ? AND receiver = ?) ORDER BY created_at ASC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'ssss', $uname, $receiver, $receiver, $uname);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Check if there are any messages
if ($result->num_rows > 0) {
    // Output messages
    while ($row = $result->fetch_assoc()) {
        $messageClass = $row['sender'] == $uname ? 'sent' : 'received'; // Determine message class
        
        // Output message with appropriate class
        echo '<div class="message ' . $messageClass . '">';
        echo '<strong>' . $row['sender'] . ':</strong> <span class="message-content">' . htmlspecialchars($row['message']) . '</span>';
        echo '<p>' . $row['created_at'] . ':</p> ';
        echo '</div>';
    }
} else {
    echo '<div class="no-messages">No messages found.</div>';
}

// Close the prepared statement and database connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
