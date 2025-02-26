<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch total users and events
$users_result = $conn->query("SELECT COUNT(*) AS total_users FROM users WHERE is_admin = 0");
$events_result = $conn->query("SELECT COUNT(*) AS total_events FROM events");

$users_count = $users_result->fetch_assoc()['total_users'];
$events_count = $events_result->fetch_assoc()['total_events'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
            overflow: hidden;
        }

        /* Dashboard Container */
        .dashboard-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            width: 500px;
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }

        /* Heading */
        h2 {
            color: #0072ff;
            animation: slideInDown 1s ease-in-out;
        }

        h3 {
            margin-top: 30px;
            color: #333;
            animation: slideInLeft 1s ease-in-out;
        }

        /* List Styles */
        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            background-color: #f4f4f4;
            padding: 12px;
            margin: 8px 0;
            border-radius: 6px;
            font-size: 18px;
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        li:hover {
            background-color: #e0f0ff;
            transform: scale(1.05);
        }

        /* Links */
        a {
            text-decoration: none;
            color: #0072ff;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #0056b3;
        }

        /* Manage Section */
        .manage-links li {
            background-color: #e7f1ff;
        }

        /* Logout Button */
        .logout-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #ff4d4d;
            color: #fff;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #e60000;
            transform: scale(1.1);
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>

<body>

    <div class="dashboard-container">
        <h2>👋 Welcome, Admin</h2>

        <h3>📊 Dashboard Overview</h3>
        <ul>
            <li>Total Users: <strong><?php echo $users_count; ?></strong></li>
            <li>Total Events: <strong><?php echo $events_count; ?></strong></li>
        </ul>

        <h3>🛠 Manage:</h3>
        <ul class="manage-links">
            <li><a href="manage_events.php">📅 Manage Events</a></li>
            <li><a href="manage_bookings.php">📖 Manage Bookings</a></li>
            <li><a href="manage_users.php">👥 Manage Users</a></li>
	<a href="view_bookings.php" class="btn">📑 View All Bookings</a>

        </ul>

        <a href="admin_logout.php" class="logout-btn">🚪 Logout</a>
    </div>
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>

</html>
