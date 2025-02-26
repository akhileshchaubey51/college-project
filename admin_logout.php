<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging Out...</title>
    <style>
        /* Global Styles */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #ff9a9e, #fad0c4);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            color: #fff;
        }

        /* Logout Message Container */
        .logout-container {
            text-align: center;
            padding: 40px;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1s ease-out;
        }

        h2 {
            margin: 0;
            font-size: 2em;
            animation: slideIn 1s ease-out;
        }

        p {
            margin-top: 10px;
            font-size: 1.2em;
            animation: fadeIn 2s ease-in;
        }

        /* Loader Spinner */
        .loader {
            border: 6px solid #f3f3f3;
            border-top: 6px solid #ff6b6b;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>

<body>

    <div class="logout-container">
        <h2>🚪 Logging Out...</h2>
        <div class="loader"></div>
        <p>You will be redirected shortly.</p>
    </div>

    <script>
        // Redirect after 3 seconds
        setTimeout(function () {
            window.location.href = "admin_login.php";
        }, 3000);
    </script>

</body>

</html>
