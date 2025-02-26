<?php
// config.php
$host = 'localhost';
$db = 'college_event_db';
$user = 'root';
$pass = '';

// Establish connection
$conn = new mysqli($host, $user, $pass, $db);

// Output HTML for messages
echo '<!DOCTYPE html>
<html>
<head>
    <title>Database Connection</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #74ebd5, #acb6e5);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            text-align: center;
            animation: fadeIn 0.5s ease-out forwards;
        }
        .modal.show {
            display: block;
        }
        .modal h2 {
            margin-top: 0;
        }
        .btn {
            margin-top: 15px;
            padding: 10px 20px;
            border: none;
            background: #4CAF50;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #45a049;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translate(-50%, -60%); }
            to { opacity: 1; transform: translate(-50%, -50%); }
        }
    </style>
</head>
<body>';

// Check connection
if ($conn->connect_error) {
    echo '
    <div class="modal show" id="errorModal">
        <h2>❌ Connection Failed</h2>
        <p>' . $conn->connect_error . '</p>
        <button class="btn" onclick="closeModal()">Close</button>
    </div>';
} else {
    '
    <div class="modal show" id="successModal">
        <h2>✅ Connection Successful</h2>
           <h2>✅ Connection Successful</h2>
        <p>Connected to <strong>' . $db . '</strong> database!</p>
        <button class="btn" onclick="closeModal()">Continue</button>
    </div>';

    // Ensure unique booking per user per event
    $conn->query("ALTER TABLE bookings ADD CONSTRAINT unique_booking UNIQUE (user_id, event_id)");
}

echo '
<script>
    function closeModal() {
        const modals = document.querySelectorAll(".modal");
        modals.forEach(modal => modal.classList.remove("show"));
    }
</script>
</body>
</html>';
?>
