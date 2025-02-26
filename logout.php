<!-- logout.php -->
<?php
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="4;url=login.php"> <!-- Auto Redirect -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging Out...</title>
    <style>
        /* Reset */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        /* Body Styling */
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #ff9a9e, #fad0c4);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow: hidden;
        }

        /* Logout Container */
        .logout-container {
            text-align: center;
            background: #fff;
            padding: 40px 50px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            animation: fadeIn 1s ease-in;
        }

        /* Fade In Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        /* Spinner Animation */
        .spinner {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #ff6b81;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            margin: 20px auto;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        h2 {
            color: #333;
            margin-bottom: 10px;
        }

        p {
            color: #555;
            font-size: 1rem;
        }

        /* Redirect Link */
        a {
            display: inline-block;
            margin-top: 20px;
            color: #ff6b81;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #e63946;
        }
    </style>
</head>

<body>

    <div class="logout-container">
        <h2>👋 Logging You Out...</h2>
        <div class="spinner"></div>
        <p>You will be redirected to the login page shortly.</p>
        <a href="login.php">🔗 Click here if not redirected</a>
    </div>

    <script>
        // Fallback redirect after 4 seconds
        setTimeout(() => {
            window.location.href = "login.php";
        }, 4000);
    </script>

</body>

</html>
