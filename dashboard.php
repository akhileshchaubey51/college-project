<!-- dashboard.php -->
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

echo "Welcome to your dashboard!";
?>
<a href="events.php">View Events</a> | <a href="my_bookings.php">My Bookings</a> | <a href="logout<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #f5af19, #f12711);
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            animation: fadeIn 1s ease-in;
        }

        h1 {
            margin-bottom: 10px;
            font-size: 2.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        p#greeting {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        .nav-links {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        a {
            text-decoration: none;
            color: #fff;
            background: #4CAF50;
            padding: 12px 20px;
            border-radius: 5px;
            transition: transform 0.3s ease, background 0.3s ease;
        }

        a:hover {
            background: #45a049;
            transform: scale(1.1);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>
    <h1>👋 Welcome to Your Dashboard!</h1>
    <p id="greeting">Loading greeting...</p>

    <div class="nav-links">
        <a href="events.php">📅 View Events</a>
        <a href="my_bookings.php">📖 My Bookings</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <script>
        // Dynamic Greeting based on Time
        const greetingEl = document.getElementById('greeting');
        const now = new Date();
        const hour = now.getHours();
        let greeting;

        if (hour < 12) {
            greeting = "🌞 Good Morning!";
        } else if (hour < 18) {
            greeting = "☀️ Good Afternoon!";
        } else {
            greeting = "🌙 Good Evening!";
        }

        greetingEl.textContent = greeting;
    </script>
</body>

</html>