<?php
include 'config.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle booking cancellation
if (isset($_GET['cancel_booking'])) {
    $booking_id = intval($_GET['cancel_booking']);

    // Delete the booking
    $delete_stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
    $delete_stmt->bind_param("i", $booking_id);

    if ($delete_stmt->execute()) {
        echo "<script>alert('✅ Booking canceled successfully!'); window.location.href='manage_bookings.php';</script>";
    } else {
        echo "<script>alert('❌ Error canceling booking: " . $conn->error . "');</script>";
    }
}

// Fetch all bookings
$bookings_stmt = $conn->prepare("SELECT b.id AS booking_id, u.name AS user_name, u.email, e.title AS event_title, e.event_date, e.venue, e.is_paid 
                                 FROM bookings b 
                                 JOIN users u ON b.user_id = u.id 
                                 JOIN events e ON b.event_id = e.id 
                                 ORDER BY e.event_date DESC");
$bookings_stmt->execute();
$bookings_result = $bookings_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
    <style>
        /* Reset */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        /* Body Styling */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #89f7fe, #66a6ff);
            color: #333;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        /* Header */
        h2 {
            margin-bottom: 20px;
            color: #444;
            animation: fadeIn 1s ease;
        }

        /* Table Styling */
        table {
            width: 90%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            animation: slideIn 1s ease;
        }

        th, td {
            padding: 12px 15px;
            text-align: center;
        }

        th {
            background-color: #4a90e2;
            color: #fff;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #d0e7ff;
            transition: background-color 0.3s ease;
        }

        /* Cancel Link */
        a.cancel-btn {
            color: #e74c3c;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease, transform 0.2s ease;
        }

        a.cancel-btn:hover {
            color: #c0392b;
            transform: scale(1.1);
        }

        /* Back Button */
        .back-link {
            margin-top: 20px;
            text-decoration: none;
            background-color: #4a90e2;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .back-link:hover {
            background-color: #357ab8;
            transform: scale(1.05);
        }

        /* No Bookings */
        .no-bookings {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            animation: fadeIn 1.5s ease;
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
    </style>
</head>

<body>

    <h2>📋 Manage Event Bookings</h2>

    <?php if ($bookings_result->num_rows > 0): ?>
        <table>
            <tr>
                <th>User Name</th>
                <th>Email</th>
                <th>Event Title</th>
                <th>Date</th>
                <th>Venue</th>
                <th>Payment</th>
                <th>Action</th>
            </tr>
            <?php while ($booking = $bookings_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($booking['user_name']); ?></td>
                    <td><?php echo htmlspecialchars($booking['email']); ?></td>
                    <td><?php echo htmlspecialchars($booking['event_title']); ?></td>
                    <td><?php echo htmlspecialchars($booking['event_date']); ?></td>
                    <td><?php echo htmlspecialchars($booking['venue']); ?></td>
                    <td><?php echo $booking['is_paid'] ? '💵 Paid' : '🆓 Free'; ?></td>
                    <td>
                        <a href="manage_bookings.php?cancel_booking=<?php echo $booking['booking_id']; ?>" class="cancel-btn" onclick="return confirmCancellation()">❌ Cancel</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <div class="no-bookings">
            <p>📭 No bookings found.</p>
        </div>
    <?php endif; ?>

    <a href="admin_dashboard.php" class="back-link">⬅️ Back to Admin Dashboard</a>

    <script>
        // Confirm before canceling booking
        function confirmCancellation() {
            return confirm("⚠️ Are you sure you want to cancel this booking?");
        }
    </script>

</body>

</html>
