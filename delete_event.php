<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$message = ""; // To hold success/error messages

if (isset($_GET['id'])) {
    $event_id = intval($_GET['id']);

    // Check for existing bookings before deleting
    $check_stmt = $conn->prepare("SELECT * FROM bookings WHERE event_id = ?");
    $check_stmt->bind_param("i", $event_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $message = "❌ Cannot delete event. Bookings exist.";
    } else {
        $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
        $stmt->bind_param("i", $event_id);

        if ($stmt->execute()) {
            $message = "✅ Event deleted successfully!";
            header("refresh:2;url=manage_events.php"); // Redirect after 2 seconds
        } else {
            $message = "❌ Error deleting event.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Delete Event</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #ff9966, #ff5e62);
            color: #fff;
            text-align: center;
            padding: 50px;
            animation: fadeIn 1s ease-in;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .message {
            font-size: 1.2rem;
            padding: 15px;
            border-radius: 5px;
            display: inline-block;
            animation: popIn 0.5s ease;
        }

        .success {
            background-color: #4CAF50;
        }

        .error {
            background-color: #f44336;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #fff;
            background: #007BFF;
            padding: 12px 20px;
            border-radius: 5px;
            transition: transform 0.3s ease, background 0.3s ease;
        }

        a:hover {
            background: #0056b3;
            transform: scale(1.1);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes popIn {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
    </style>
</head>

<body>

    <h1>🗑️ Delete Event</h1>

    <?php if ($message): ?>
        <div class="message <?php echo (strpos($message, '✅') !== false) ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <br>
    <a href="manage_events.php">⬅️ Back to Manage Events</a>

    <script>
        // Confirm deletion before navigating to delete URL
        const deleteLinks = document.querySelectorAll('a.delete-link');
        deleteLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                if (!confirm("⚠️ Are you sure you want to delete this event? This action cannot be undone.")) {
                    e.preventDefault();
                }
            });
        });
    </script>

</body>

</html>
