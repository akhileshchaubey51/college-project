<?php
include 'config.php';
session_start();

$events_stmt = $conn->prepare("SELECT * FROM events");
$events_stmt->execute();
$events_result = $events_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📅 Events</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <style>
        /* Reset */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #FFDEE9, #B5FFFC);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px;
            color: #333;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 2rem;
            color: #444;
            animation: fadeInDown 1s ease;
        }

        /* Event Card */
        .event-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            margin: 15px;
            width: 300px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: fadeIn 1s ease forwards;
        }

        .event-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        .event-card h3 {
            color: #333;
            margin-bottom: 10px;
        }

        .event-card p {
            margin: 5px 0;
            color: #666;
        }

        /* Buttons */
        button, .book-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background: #007bff;
            color: #fff;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        button:hover, .book-btn:hover {
            background: #0056b3;
            transform: scale(1.05);
        }

        /* Loader */
        .loader {
            display: none;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #007bff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        /* Animations */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .event-card {
                width: 90%;
            }

            h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>

    <h2>🎉 Available Events</h2>

    <div id="events-container" style="display: flex; flex-wrap: wrap; justify-content: center;">
        <?php while ($event = $events_result->fetch_assoc()): ?>
            <div class="event-card">
                <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                <p>📅 Date: <?php echo $event['event_date']; ?></p>
                <p>📍 Venue: <?php echo $event['venue']; ?></p>
                <p><?php echo $event['is_paid'] ? '💵 Price: ₹' . $event['price'] : '🆓 Free Event'; ?></p>

                <?php if ($event['is_paid']): ?>
                    <button onclick="payWithRazorpay('<?php echo $event['id']; ?>', '<?php echo $event['title']; ?>', '<?php echo $event['price']; ?>')">Pay & Book</button>
                <?php else: ?>
                    <form action="book_event.php" method="POST">
                        <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                        <button class="book-btn" type="submit">Book Now</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Loader -->
    <div class="loader" id="loader"></div>

    <script>
        function payWithRazorpay(event_id, event_title, event_price) {
            const loader = document.getElementById('loader');
            loader.style.display = 'block';

            var options = {
                "key": "YOUR_RAZORPAY_KEY_ID", // Replace with your Razorpay Key ID
                "amount": event_price * 100, // Amount in paisa
                "currency": "INR",
                "name": "College Event Booking",
                "description": event_title,
                "handler": function(response) {
                    loader.style.display = 'none';
                    // Redirect to payment success page
                    window.location.href = "payment_success.php?payment_id=" + response.razorpay_payment_id + "&event_id=" + event_id;
                },
                "prefill": {
                    "name": "<?php echo $_SESSION['user_name']; ?>",
                    "email": "<?php echo $_SESSION['user_email']; ?>"
                },
                "theme": {
                    "color": "#3399cc"
                },
                "modal": {
                    "ondismiss": function() {
                        loader.style.display = 'none';
                        alert("Payment cancelled!");
                    }
                }
            };

            var rzp = new Razorpay(options);
            rzp.open();
        }
    </script>

</body>

</html>
