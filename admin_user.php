<!DOCTYPE html>
<html>

<head>
    <title>Create Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            margin: 0;
        }

        .container {
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            animation: fadeIn 1s ease;
        }

        h2 {
            color: #333;
        }

        .message {
            font-size: 1.2rem;
            margin: 20px 0;
            padding: 10px;
            border-radius: 5px;
            animation: slideIn 0.5s ease;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #6e8efb;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #a777e3;
        }

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

        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>🛡️ Create Admin User</h2>
        <?php
        include 'config.php';

        $email = "admin@gehu.ac.in"; // Replace with desired admin email
        $password = "admin123";      // Replace with desired password

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert into admins table
        $stmt = $conn->prepare("INSERT INTO admins (email, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $hashed_password);

        if ($stmt->execute()) {
            echo "<div class='message success'>✅ Admin user created successfully!</div>";
        } else {
            echo "<div class='message error'>❌ Error: " . $stmt->error . "</div>";
        }

        $stmt->close();
        $conn->close();
        ?>
        <button onclick="window.location.href='admin_login.php'">🔑 Go to Admin Login</button>
    </div>
</body>

</html>
