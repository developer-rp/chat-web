<?php 
require '../db.php';

// Validate and sanitize user input
$uname = isset($_SESSION['username']) ? $_SESSION['username'] : '';
if (empty($uname)) {
    echo 'User not authenticated.';
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Website</title>
    <link rel="icon" type="image/png" href="../img/favicon.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style type="text/css">
        /* styles.css */

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        .header {
            background-color: #be3e2f; /* Red background color */
            color: #fff; /* White text color */
            padding: 10px 20px;
            display: flex;
            justify-content: space-between; /* Align items horizontally */
            align-items: center; /* Center items vertically */
        }

        .header h1 {
            margin: 0; /* Remove default margin */
        }

        .header button {
            background-color: #000000;
            color: rgb(255, 0, 0);
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .header button:hover {
            background-color: #e73d3d;
            color: rgb(3, 3, 3);
        }

        .container {
            display: flex; /* Set chat container to flex container */
            height: calc(100vh - 70px); /* Calculate remaining height minus header height */
        }

        .friends-list {
            width: 30%;
            background-color: #ffe6e6; /* Light red background color */
            padding: 0px 20px;
            border-right: 1px solid #ccc;
            overflow-y: auto; /* Add vertical scrollbar if needed */
        }

        /* Center list items horizontally */
        .friends-list ul {
            list-style-type: none;
            padding: 0;
            margin: 0; /* Remove default margin */
            display: flex; /* Set display to flex */
            flex-direction: column; /* Arrange list items in a column */
            align-items: center; /* Center list items horizontally */
        }

        /* Remove margin between list items */
        .friends-list li {
            font-size: larger;
            cursor: pointer;
            width: 100%; /* Adjust the width as needed */
            padding: 10px 40px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Add a subtle box shadow */
            transition: background-color 0.3s ease; /* Smooth transition for hover effect */
            margin-bottom: 10px; /* Add margin between list items */
        }

        .friends-list li:hover {
            background-color: #ffd1d1; /* Lighter red background color on hover */
        }

        .message {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f2f2f2;
            border-radius: 10px;
            max-width: 70%;
            word-wrap: break-word; /* Add text wrapping */
        }

        .sent {
            background-color: #DCF8C6; /* Light green background for sent messages */
            float: right; /* Float sent messages to the right */
        }

        .received {
            background-color: #ECEFF1; /* Light gray background for received messages */
            float: left; /* Float received messages to the left */
        }

        @media (min-width: 769px) {
            .chat-messages {
                display: flex; /* Change to flex layout */
                flex-direction: column; /* Align messages in a column */
                align-items: flex-start; /* Align messages to the start of the container */
            }

            .message {
                max-width: 70%; /* Limit message width to 70% of the container */
            }

            .sent, .received {
                max-width: 60%; /* Limit message width to 60% of the container */
            }

            .sent {
                background-color: #DCF8C6; /* Light green background for sent messages */
                align-self: flex-end; /* Align sent messages to the end of the container */
            }

            .received {
                background-color: #ECEFF1; /* Light gray background for received messages */
                align-self: flex-start; /* Align received messages to the start of the container */
            }
        }

        .message strong {
            margin-right: 10px; /* Add some spacing between sender and message */
        }

        .message p {
            margin-left: 100px; /* Add some spacing between sender and message */
        }

        .chat-container1 {
            width: 100%;
            height: 100%;
            background-color: #e73d3d;
            display: flex;
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
        }

        .chat-container1 p h1 {
            margin: 0; /* Remove default margin */
        }

        .chat-container2 {
            width: 100%;
            height: 100%;
            background-color: #fff; /* White background color */
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex; /* Set chat container to flex container */
            flex-direction: column; /* Arrange children in a column */
            overflow-y: auto; /* Add vertical scrollbar if needed */
        }

        /* Chat header */
        .chat-header {
            position: fixed;
            right: 0;
            padding: 20px;
            border-bottom: 1px solid #ccc;
            text-align: center;
            background-color: #e74c3c; /* Red background color */
            color: #fff; /* White text color */
            overflow-y: auto; /* Add vertical scrollbar if content exceeds height */
            max-height: 100px; /* Set a fixed height for the chat header */
        }

        .chat-header h1 {
            margin: 0;
        }

        /* Chat messages */
        .chat-messages {
            flex: 1; /* Allow chat messages to grow and fill remaining space */
            overflow-y: auto; /* Add vertical scrollbar if needed */
            padding: 20px;
            margin-bottom: 120px; /* Add bottom margin to accommodate chat input and button */
        }

        .chat-messages div {
            margin-bottom: 10px;
        }

        /* Chat input */
        .chat-input {
            position: fixed; /* Position the chat input section */
            bottom: 0; /* Align it to the bottom of the viewport */
            width: calc(100% - 40px); /* Make it take the full width of the viewport minus padding */
            padding: 20px;
            background-color: #f2f2f2; /* Light gray background color */
            z-index: 1; /* Ensure chat input is above other elements */
        }

        /* Adjust input and button styles for better visibility */
        #message-input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 20px;
            resize: none;
            outline: none;
            line-height: 1.5; /* Adjust the line spacing as needed */
            margin-right: 10px;
            width: 65%; /* Limit the maximum width to 80% of the container */
        }

        .chat-input button {
            border: none;
            background-color: red; /* Red background color */
            color: white;
            padding: 10px 20px;
            border-radius: 20px;
            cursor: pointer;
            max-width: 10%; /* Limit the maximum width to 20% of the container */
            position: fixed; /* Position the button */
            right: 13px; /* Distance from the right side */
            bottom: 27px; /* Distance from the bottom */
        }

        .chat-input button:hover {
            background-color: darkred; /* Darker red background color on hover */
        }

        /* Hide chat-container1 in mobile view */
        @media (max-width: 768px) {
            .friends-list {
                width: 100%; /* Occupy full width */
            }

            .chat-container1 {
                display: none; /* Hide chat-container1 */
            }

            .chat-container2 {
                width: 100%; /* Make chat-container2 full width */
            }
        }


    </style>
</head>
<body>
    <header class="header">
        <h1>Welcome to the Chat</h1>
        <form action="../" method="post">
            <button type="submit"><strong>Logout</strong></button>
        </form>
    </header>

    <div class="container">
        <aside class="friends-list">
            <h2>Select Friend</h2>
            <?php 
                // Fetch friends data from the database using prepared statement
				$sql1 = "SELECT * FROM users WHERE username != ?";
				$stmt = $conn->prepare($sql1);
				$stmt->bind_param('s', $uname);
				$stmt->execute();
				$result1 = $stmt->get_result();

				// Check if there are any results
				if ($result1->num_rows > 0) {
					echo '<ul>';
					// Loop through the results and generate list items for each friend
					while ($row = $result1->fetch_assoc()) {
						// Use htmlspecialchars to prevent XSS when outputting usernames
						$escaped_username = htmlspecialchars($row['username']);
						echo '<li onclick="toggleChatContainer(\'' . $escaped_username . '\')">' . $escaped_username . '</li>';
					}
					echo '</ul>';
				} else {
					// If no friends found, display a message or handle the situation accordingly
					echo 'No friends found.';
				}

				// Close the prepared statement and database connection
				$stmt->close();
				$conn->close();
            ?>
        </aside>

        <div class="chat-container1">
            <p><h1>Select A Chat To Continue</h1></p><br>
            <p><h1>😍</h1></p>
        </div>

        <div class="chat-container2" style="display:none;">
            <section>
                <div class="chat-header">
                    <button id="backto" onclick="backto()"><strong>◀ Back</strong></button>
                    <h1></h1>
                </div>
            </section>
            <section>
                <div class="chat-messages" id="chat-messages">
                    <!-- Messages will appear here -->
                </div>
                <div>
                    <p id="scrollto"></p>
                    <a href="#scrollto"><button style="position: fixed; right: 15px; bottom: 100px;">🔻</button></a>
                </div>
            </section>
            <section>
                <div class="chat-input">
                    <input name="uname" type="text" value="<?php echo $uname; ?>" style="display:none;">
                    <input class="receiver" id="receiver" name="receiver" type="text" value="" style="display:none;">
                    <textarea name="message" id="message-input" placeholder="Type your message..." rows="1"></textarea>
                    <button onclick="sendMessage()">Send</button>
                </div>
            </section>
        </div>
    </div>

    <script>
        function sendMessage() {
            var messageInput = $("#message-input").val().trim(); // Trim whitespace from the input
            var uname = "<?php echo $uname; ?>";
            var receiver = $("#receiver").val();

            // Check if the message input is empty
            if (messageInput === "") {
                alert("Message is empty.");
            } else {
                // AJAX request to send message to the server
                $.ajax({
                    url: 'send_message.php',
                    method: 'POST',
                    data: { uname: uname, message: messageInput, receiver: receiver },
                    success: function (response) {
                        // Message sent successfully, update chat interface
                        fetchMessages();
                        // Clear input field after sending message
                        $("#message-input").val('');
                    },
                    error: function (xhr, status, error) {
                        console.error('Error sending message');
                    }
                });
            }
        }


        function fetchMessages() {
            var uname = "<?php echo $uname; ?>";
            var receiver = $("#receiver").val();

            // AJAX request to fetch messages from the server
            $.ajax({
                url: 'fetch_messages.php',
                method: 'GET',
                data: { uname: uname, receiver: receiver }, // Pass uname and receiver as data
                success: function (response) {
                    // Update chat interface with fetched messages
                    $("#chat-messages").html(response);
                    
                    // Scroll to the specified element with id "scrollto"
                    var scrollToElement = document.getElementById("scrollto");
                    scrollToElement.scrollIntoView({ behavior: "auto" });
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching messages');
                }
            });
        }

        function loadMessages() {
            var uname = "<?php echo $uname; ?>";
            var receiver = $("#receiver").val();

            // AJAX request to fetch messages from the server
            $.ajax({
                url: 'fetch_messages.php',
                method: 'GET',
                data: { uname: uname, receiver: receiver }, // Pass uname and receiver as data
                success: function (response) {
                    // Update chat interface with fetched messages
                    $("#chat-messages").html(response);

                },
                error: function (xhr, status, error) {
                    console.error('Error fetching messages');
                }
            });
        }

        // Fetch messages on page load
        $(document).ready(function () {
            loadMessages(); // Initial fetch
            setInterval(loadMessages, 500); // Fetch messages every second
        });

        // Event listener for keydown event in the message input field
        $("#message-input").keypress(function (e) {
            // Check if Enter key was pressed
            if (e.which === 13) {
                // Prevent default form submission
                e.preventDefault();
                // Call sendMessage() function
                sendMessage();
            }
        });

        function toggleChatContainer(username) {
            // Hide the logout button
            var logoutButton = document.querySelector('.header button[type="submit"]');
            logoutButton.style.display = 'none';

            // Show or hide chat-container1 based on screen width
            if (window.innerWidth <= 768) {
                document.querySelector('.friends-list').style.display = 'none';
                
                // Create and append the back button
                var backButton = document.createElement('button');
                backButton.id = 'backtoH';
                backButton.innerHTML = '<strong>◀ Back</strong>';
                backButton.onclick = backto;
                document.querySelector('.header').appendChild(backButton);

                // Move content from chat-header to header
                var header = document.querySelector('.header h1');
                var chatHeader = document.querySelector('.chat-header h1').innerText;
                header.innerText = chatHeader;
                document.querySelector('.chat-header').style.display = 'none';
            } else {
                document.querySelector('.chat-container1').style.display = 'none'; // Show chat-container1 for larger screens
            }

            // Show chat-container2
            document.querySelector('.chat-container2').style.display = 'block';

            // Set the username as the chat header
            document.querySelector('.header h1').innerText = username;
            document.querySelector('.receiver').value = username;

            fetchMessages();
        }

        function backto() {
            // Show the logout button
            var logoutButton = document.querySelector('.header button[type="submit"]');
            logoutButton.style.display = 'block';

            // Show the friends list
            document.querySelector('.friends-list').style.display = 'block';

            // Hide chat-container2
            document.querySelector('.chat-container2').style.display = 'none';

            // Reset header content
            document.querySelector('.header h1').innerText = 'Welcome to the Chat';

            // Remove the back button
            var backButton = document.getElementById('backtoH');
            backButton.parentNode.removeChild(backButton);

            // Show or hide chat-container1 based on screen width
            if (window.innerWidth > 768) {
                document.querySelector('.chat-container1').style.display = 'flex'; // Show chat-container1 for larger screens
            } else {
                document.querySelector('.chat-container1').style.display = 'none'; // Hide chat-container1 for smaller screens
            }
        }


    </script>

</body>
</html>
