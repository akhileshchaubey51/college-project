<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = ""; // Store feedback

if (isset($_POST['book_event'])) {
    $event_id = intval($_POST['event_id']);

    // Fetch event details
    $stmt = $conn->prepare("SELECT title, price, total_seats, booked_seats FROM events WHERE id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();

    if (!$event) {
        $message = "❌ Event not found!";
    } elseif ($event['booked_seats'] >= $event['total_seats']) {
        $message = "⚠️ No seats available!";
    } elseif ($event['price'] > 0) {
        // Paid event — redirect to payment page
        header("Location: payment_gateway.php?event_id=$event_id");
        exit();
    } else {
        // Free event booking
        $book_stmt = $conn->prepare("INSERT INTO bookings (user_id, event_id) VALUES (?, ?)");
        $book_stmt->bind_param("ii", $user_id, $event_id);

        if ($book_stmt->execute()) {
            // Update booked seats
            $update_seats = $conn->prepare("UPDATE events SET booked_seats = booked_seats + 1 WHERE id = ?");
            $update_seats->bind_param("i", $event_id);
            $update_seats->execute();

            $message = "✅ Successfully booked: {$event['title']}!";
        } else {
            $message = "❌ Booking failed. Try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Event</title>
    <style>
        /* Reset */
        * { box-sizing: border-box; margin: 0; padding: 0; }

        /* Global */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #74ebd5, #ACB6E5);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            color: #333;
            overflow: hidden;
        }

        h2 {
            margin-bottom: 20px;
            animation: fadeInDown 1s ease;
            color: #fff;
        }

        /* Form */
        form {
            background: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            text-align: center;
            animation: scaleIn 0.8s ease;
        }

        button {
            padding: 12px 25px;
            border: none;
            background: #007bff;
            color: #fff;
            font-size: 1.1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background: #0056b3;
            transform: translateY(-3px);
        }

        a {
            margin-top: 20px;
            display: inline-block;
            color: #fff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a:hover { color: #333; }

        /* Toast Message */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #333;
            color: #fff;
            padding: 15px 20px;
            border-radius: 5px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.5s ease;
            z-index: 1000;
        }

        .toast.show {
            opacity: 1;
            pointer-events: auto;
        }

        /* Loader */
        .loader {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #007bff;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            display: none;
            margin: 20px auto;
        }

        /* Animations */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes scaleIn {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        /* Responsive */
        @media (max-width: 600px) {
            form {
                width: 90%;
                padding: 20px;
            }

            h2 { font-size: 1.5rem; }
        }
    </style>
</head>

<body>

    <h2>🎟️ Book Your Event</h2>

    <!-- Toast Notification -->
    <div class="toast" id="toast"><?php echo $message; ?></div>

    <!-- Booking Form -->
    <form method="POST" id="bookingForm">
        <input type="hidden" name="event_id" value="<?php echo isset($_POST['event_id']) ? intval($_POST['event_id']) : ''; ?>">
        <button type="submit" name="book_event">📅 Book Now</button>
        <div class="loader" id="loader"></div>
    </form>

    <a href="events.php">⬅️ Back to Events</a>

    <script>
        // Toast Notification
        function showToast(message) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.classList.add('show');

            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        // Show toast on page load if message exists
        window.onload = () => {
            const toastMessage = "<?php echo $message; ?>";
            if (toastMessage) {
                showToast(toastMessage);
            }
        };

        // Booking Form with Loader
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const loader = document.getElementById('loader');

            if (confirm("⚠️ Confirm your booking?")) {
                loader.style.display = 'block';

                setTimeout(() => {
                    this.submit();
                }, 1500); // Simulate delay for UX
            }
        });
    </script>

</body>

</html>
