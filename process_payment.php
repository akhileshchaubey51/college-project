
<?php
include 'config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Process Payment & Booking
if (isset($_POST['event_id'], $_POST['price'])) {
    $user_id = $_SESSION['user_id'];
    $event_id = intval($_POST['event_id']);
    $price = floatval($_POST['price']);

    // Simulate payment success
    $stmt = $conn->prepare("INSERT INTO bookings (user_id, event_id, payment_status) VALUES (?, ?, ?)");
    $payment_status = 'Paid';

    if ($stmt) {
        $stmt->bind_param("iis", $user_id, $event_id, $payment_status);
        if ($stmt->execute()) {
            echo "✅ Payment successful and event booked!";
            echo "<br><a href='my_bookings.php'>View My Bookings</a>";
        } else {
            echo "❌ Booking failed: " . $stmt->error;
        }
    } else {
        echo "❌ Database Error: " . $conn->error;
    }
} else {
    echo "❌ Invalid payment data.";
}
