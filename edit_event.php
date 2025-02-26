<?php
session_start();
include 'config.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$event_id = intval($_GET['id']);
$message = ""; // For success/error messages

// Fetch Event Details
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "<p class='error-msg'>❌ Event not found.</p>";
    exit();
}

$event = $result->fetch_assoc();

// Handle Update
if (isset($_POST['update_event'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $event_date = $_POST['event_date'];
    $venue = $_POST['venue'];
    $total_seats = intval($_POST['total_seats']);

    $update_stmt = $conn->prepare("UPDATE events SET title = ?, description = ?, event_date = ?, venue = ?, total_seats = ? WHERE id = ?");
    $update_stmt->bind_param("ssssii", $title, $description, $event_date, $venue, $total_seats, $event_id);

    if ($update_stmt->execute()) {
        $message = "✅ Event updated successfully!";
        echo "<script>
                setTimeout(() => { window.location.href = 'manage_events.php'; }, 2000);
              </script>";
    } else {
        $message = "❌ Failed to update event.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <style>
        /* Global Styles */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #84fab0, #8fd3f4);
            margin: 0;
            padding: 20px;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            animation: fadeIn 1s ease-in;
        }

        h2 {
            color: #444;
            animation: slideIn 1s ease-out;
        }

        /* Form Styling */
        form {
            background-color: #fff;
            padding: 25px 35px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
            animation: popIn 0.8s ease;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            font-size: 1rem;
        }

        input:focus,
        textarea:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            outline: none;
        }

        textarea {
            resize: vertical;
            height: 100px;
        }

        /* Buttons */
        button {
            padding: 12px 25px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        a {
            display: inline-block;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #0056b3;
        }

        /* Message Box */
        .message {
            margin-top: 20px;
            padding: 12px 20px;
            border-radius: 5px;
            font-size: 1.1rem;
            animation: fadeIn 0.5s ease;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error-msg {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Animations */
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes popIn {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
    </style>
</head>

<body>

    <h2>✏️ Edit Event</h2>

    <!-- Display Success/Error Message -->
    <?php if ($message): ?>
        <div class="message <?php echo (strpos($message, '✅') !== false) ? 'success' : 'error-msg'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Event Title:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required>

        <label>Description:</label>
        <textarea name="description" required><?php echo htmlspecialchars($event['description']); ?></textarea>

        <label>Event Date:</label>
        <input type="date" name="event_date" value="<?php echo $event['event_date']; ?>" required>

        <label>Venue:</label>
        <input type="text" name="venue" value="<?php echo htmlspecialchars($event['venue']); ?>" required>

        <label>Total Seats:</label>
        <input type="number" name="total_seats" value="<?php echo $event['total_seats']; ?>" required>

        <button type="submit" name="update_event">💾 Update Event</button>
    </form>

    <a href="manage_events.php">⬅️ Back to Manage Events</a>

    <script>
        // Form submission confirmation
        document.querySelector("form").addEventListener("submit", function (e) {
            const confirmUpdate = confirm("⚠️ Are you sure you want to update this event?");
            if (!confirmUpdate) {
                e.preventDefault();
            }
        });
    </script>

</body>

</html>
