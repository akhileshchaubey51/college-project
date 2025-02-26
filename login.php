<!-- login.php -->
<?php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "❌ Invalid password.";
        }
    } else {
        $error = "❌ No user found with that email.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - College Event Management</title>
    <style>
        /* Reset */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        /* Body Styling */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #89f7fe, #66a6ff);
            animation: bgAnimation 10s infinite alternate;
        }

        /* Background Animation */
        @keyframes bgAnimation {
            0% { background-position: left; }
            100% { background-position: right; }
        }

        /* Form Container */
        .login-container {
            background: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            text-align: center;
            animation: fadeIn 1s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-50px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        /* Input Fields */
        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 40px 12px 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        input:focus {
            border-color: #007bff;
            outline: none;
        }

        /* Floating Labels */
        label {
            position: absolute;
            top: 12px;
            left: 12px;
            font-size: 1rem;
            color: #777;
            pointer-events: none;
            transition: all 0.3s ease;
        }

        input:focus + label,
        input:not(:placeholder-shown) + label {
            top: -10px;
            left: 10px;
            font-size: 0.8rem;
            color: #007bff;
            background: #fff;
            padding: 0 4px;
        }

        /* Password Toggle */
        .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 1.2rem;
            color: #888;
        }

        /* Login Button */
        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        /* Error Message */
        .error {
            color: red;
            margin-bottom: 15px;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-container {
                width: 90%;
                padding: 20px;
            }
        }
    </style>
</head>

<body>

    <div class="login-container">
        <h2>🔑 User Login</h2>

        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="input-group">
                <input type="email" name="email" placeholder=" " required>
                <label>Email</label>
            </div>

            <div class="input-group">
                <input type="password" name="password" placeholder=" " id="password" required>
                <label>Password</label>
                <span class="toggle-password" onclick="togglePassword()">👁️</span>
            </div>

            <button type="submit">Login</button>
        </form>
    </div>

    <script>
        // Password visibility toggle
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.querySelector('.toggle-password');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.textContent = '🙈';
            } else {
                passwordField.type = 'password';
                toggleIcon.textContent = '👁️';
            }
        }
    </script>

</body>

</html>
