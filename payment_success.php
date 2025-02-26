<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$event_id = intval($_GET['event_id']);
$payment_id = $_GET['payment_id'];

// Insert booking with payment details
$booking_stmt = $conn->prepare("INSERT INTO bookings (user_id, event_id, payment_id) VALUES (?, ?, ?)");
$booking_stmt->bind_param("iis", $user_id, $event_id, $payment_id);

if ($booking_stmt->execute()) {
    echo "<h2>✅ Payment Successful & Booking Confirmed!</h2>";
    echo "<p>🆔 Payment ID: $payment_id</p>";
    echo "<a href='my_bookings.php'>View My Bookings</a>";
} else {
    echo "❌ Error booking event: " . $conn->error;
}
