<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$result = $conn->prepare("SELECT e.title, e.event_date, e.venue FROM bookings b JOIN events e ON b.event_id = e.id WHERE b.user_id = ?");
$result->bind_param("i", $user_id);
$result->execute();
$bookings = $result->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <style>
        /* Global Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f6d365, #fda085);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: #f9f9f9;
            border-radius: 20px;
            padding: 30px;
            width: 90%;
            max-width: 1000px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-in-out;
        }

        h2 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 20px;
            color: #444;
        }

        /* Modern Table Styling */
        .modern-table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .modern-table th, .modern-table td {
            padding: 16px 20px;
            text-align: center;
        }

        .modern-table th {
            background-color: #007BFF;
            color: #fff;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 1px;
        }

        .modern-table tr {
            background-color: #fff;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .modern-table tr:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .modern-table td {
            border-bottom: 1px solid #f1f1f1;
            color: #555;
        }

        /* No Bookings Message */
        .no-bookings {
            text-align: center;
            font-size: 1.2rem;
            color: #666;
            margin: 30px 0;
        }

        /* Buttons */
        .back-btn {
            display: inline-block;
            padding: 12px 24px;
            background: #007BFF;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            text-align: center;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .back-btn:hover {
            background: #0056b3;
            transform: scale(1.05);
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 600px) {
            .modern-table th, .modern-table td {
                font-size: 14px;
                padding: 12px;
            }

            h2 {
                font-size: 1.5rem;
            }

            .back-btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>📅 My Bookings</h2>

        <?php if ($bookings->num_rows > 0): ?>
            <table class="modern-table">
                <tr>
                    <th>Event Title</th>
                    <th>Date</th>
                    <th>Venue</th>
                </tr>
                <?php while ($row = $bookings->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['event_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['venue']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p class="no-bookings">🚫 You haven't booked any events yet! 🎉</p>
        <?php endif; ?>

        <div style="text-align: center; margin-top: 20px;">
            <a href="events.php" class="back-btn">⬅️ Back to Events</a>
        </div>
    </div>

</body>

</html>
