<?php
include 'config.php';
session_start();

// Debugging: Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Debugging: Check input values
    if (empty($email) || empty($password)) {
        $error = "❌ Please enter both email and password.";
    } else {
        // Prepare SQL to fetch admin details
        $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
        if (!$stmt) {
            $error = "❌ Prepare failed: " . $conn->error;
        } else {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            // Debugging: Check if admin exists
            if ($result->num_rows === 1) {
                $admin = $result->fetch_assoc();

                // Verify password
                if (password_verify($password, $admin['password'])) {
                    // Login successful
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_email'] = $admin['email'];
                    header("Location: admin_dashboard.php");
                    exit();
                } else {
                    $error = "❌ Invalid password. Please try again.";
                }
            } else {
                $error = "❌ Admin with this email does not exist.";
            }

            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #00c6ff, #0072ff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 350px;
        }

        h2 {
            margin-bottom: 20px;
            color: #0072ff;
        }

        input[type="email"],
        input[type="password"] {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        button {
            width: 95%;
            padding: 12px;
            background-color: #0072ff;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #005bb5;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }

        .success-message {
            color: green;
            margin-top: 10px;
        }

        a {
            color: #0072ff;
            text-decoration: none;
            display: block;
            margin-top: 15px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="login-container">
        <h2>🔑 Admin Login</h2>

        <!-- Display Error Message -->
        <?php if (!empty($error)) : ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="POST" action="admin_login.php">
            <input type="email" name="email" placeholder="Admin Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>

        <a href="forgot_password.php">Forgot Password?</a>
    </div>

</body>

</html>
