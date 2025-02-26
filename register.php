<?php
include 'config.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $student_id = trim($_POST['student_id']);
    $password = $_POST['password'];

    // Server-side Email and Password Validation
    if (!preg_match("/^[a-zA-Z0-9._%+-]+@gehu\.ac\.in$/", $email)) {
        $error = "❌ Use your college email ending with @gehu.ac.in";
    } elseif (!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/", $password)) {
        $error = "❌ Password must be at least 8 chars with uppercase, lowercase, number & special char.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO users (name, email, student_id, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $student_id, $hashed_password);

        if ($stmt->execute()) {
            $success = "✅ Registration successful!";
        } else {
            $error = "❌ Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | College Events</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Reset & Global */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ff9a9e, #fad0c4, #fbc2eb, #a18cd1);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .form-container {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            padding: 40px;
            width: 400px;
            text-align: center;
            color: #fff;
            animation: floatUp 1.5s ease;
        }

        @keyframes floatUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h2 {
            margin-bottom: 20px;
            font-weight: 600;
            animation: bounce 1s infinite alternate;
        }

        @keyframes bounce {
            from { transform: translateY(0); }
            to { transform: translateY(-5px); }
        }

        /* Input Fields */
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.1);
            color: #000000;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        
        input:focus {
            border-color: #ff9a9e;
            box-shadow: 0 0 12px rgba(255, 154, 158, 0.7);
            background: rgba(255, 255, 255, 0.2);
        }

        /* Submit Button */
        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.3s ease;
        }

        button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        }

        /* Message Display */
        .message {
            margin-top: 15px;
            padding: 12px;
            border-radius: 10px;
            font-size: 0.9rem;
            animation: fadeIn 0.5s ease;
        }

        .success {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
        }

        .error {
            background: rgba(220, 53, 69, 0.2);
            color: #dc3545;
        }

        /* Responsive */
        @media (max-width: 500px) {
            .form-container {
                width: 90%;
                padding: 30px;
            }
        }
input::placeholder {

  font-weight: bold;

}
    </style>
</head>

<body>

    <div class="form-container">
        <h2>🎓 Register for Events</h2>

        <!-- PHP Success or Error Message -->
        <?php if ($success): ?>
            <div class="message success"><?php echo $success; ?></div>
        <?php elseif ($error): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Registration Form -->
        <form method="POST" action="">
            <div class="form-group">
                <input type="text" name="name" placeholder="Full Name" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="College Email (@gehu.ac.in)" required>
            </div>
            <div class="form-group">
                <input type="text" name="student_id" placeholder="Student ID" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit">Register ➡️</button>
        </form>
    </div>

</body>

</html>
