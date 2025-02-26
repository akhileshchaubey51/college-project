<!-- index.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Event Management System</title>

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
            flex-direction: column;
            background: linear-gradient(270deg, #FFDEE9, #B5FFFC);
            background-size: 400% 400%;
            animation: gradientBG 10s ease infinite;
            color: #333;
            text-align: center;
            overflow: hidden;
        }

        /* Gradient Background Animation */
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Heading Animation */
        h1 {
            font-size: 2.5rem;
            margin-bottom: 30px;
            animation: fadeInDown 1s ease-out;
            color: #444;
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-50px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Navigation Buttons */
        .nav-links a {
            text-decoration: none;
            padding: 15px 30px;
            margin: 10px;
            background-color: #007bff;
            color: #fff;
            border-radius: 30px;
            font-size: 1.1rem;
            transition: background 0.3s ease, transform 0.2s ease;
            display: inline-block;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 1s ease-out;
        }

        .nav-links a:hover {
            background-color: #0056b3;
            transform: scale(1.1);
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Floating Bubbles Animation */
        .bubble {
            position: absolute;
            bottom: -100px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            animation: rise 10s infinite ease-in;
        }

        @keyframes rise {
            0% { transform: translateY(0) scale(1); opacity: 1; }
            100% { transform: translateY(-1200px) scale(1.5); opacity: 0; }
        }

        /* Responsive */
        @media (max-width: 600px) {
            h1 { font-size: 2rem; }
            .nav-links a { font-size: 1rem; padding: 12px 20px; }
        }
    </style>
</head>

<body>

    <h1>🎓 Welcome to the College Event Management System</h1>

    <div class="nav-links">
        <a href="register.php">📝 Register</a>
        <a href="login.php">🔑 Login</a>
        <a href="events.php">📅 View Events</a>
    </div>

    <!-- Floating Bubbles for Background Effect -->
    <script>
        // Generate floating bubbles dynamically
        const body = document.querySelector('body');

        function createBubble() {
            const bubble = document.createElement('span');
            bubble.classList.add('bubble');
            const size = Math.random() * 60 + 20; // Random bubble size

            bubble.style.width = `${size}px`;
            bubble.style.height = `${size}px`;
            bubble.style.left = `${Math.random() * 100}%`;
            bubble.style.animationDuration = `${Math.random() * 5 + 5}s`;

            body.appendChild(bubble);

            // Remove bubble after animation
            setTimeout(() => {
                bubble.remove();
            }, 10000);
        }

        // Generate bubbles continuously
        setInterval(createBubble, 500);
    </script>

</body>

</html>
