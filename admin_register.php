<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Basic validation
    if (empty($email) || empty($password) || empty($confirm_password)) {
        echo "❌ All fields are required.";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "❌ Invalid email format.";
        exit();
    }

    if ($password !== $confirm_password) {
        echo "❌ Passwords do not match.";
        exit();
    }

    // Check if admin already exists
    $check_stmt = $conn->prepare("SELECT id FROM admins WHERE email = ?");
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        echo "❌ Admin with this email already exists.";
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new admin
    $insert_stmt = $conn->prepare("INSERT INTO admins (email, password) VALUES (?, ?)");
    $insert_stmt->bind_param("ss", $email, $hashed_password);

    if ($insert_stmt->execute()) {
        echo "✅ Admin registered successfully! <a href='admin_login.php'>Login here</a>";
    } else {
        echo "❌ Error: " . $conn->error;
    }

    $check_stmt->close();
    $insert_stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #ff9a9e, #fad0c4);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #333;
        }

        .register-container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }

        h2 {
            margin-bottom: 20px;
            color: #ff6b6b;
            animation: slideIn 1s ease-in-out;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #ff6b6b;
            box-shadow: 0 0 5px rgba(255, 107, 107, 0.5);
        }

        button {
            background-color: #ff6b6b;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #ff4757;
        }

        a {
            display: block;
            margin-top: 15px;
            color: #ff6b6b;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #ff4757;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>

<body>
    <div class="register-container">
        <h2>🛡️ Register New Admin</h2>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
            <button type="submit">Register Admin</button>
        </form>
        <a href="admin_login.php">🔑 Go to Admin Login</a>
    </div>
</body>

</html>
