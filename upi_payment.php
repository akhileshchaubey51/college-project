<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['submit_payment'])) {
    $event_id = intval($_POST['event_id']);
    $payment_screenshot = $_FILES['payment_screenshot']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($payment_screenshot);

    if (move_uploaded_file($_FILES['payment_screenshot']['tmp_name'], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO upi_payments (user_id, event_id, screenshot, status) VALUES (?, ?, ?, 'Pending')");
        $stmt->bind_param("iis", $user_id, $event_id, $payment_screenshot);
        $stmt->execute();

        echo "<p>✅ Payment proof uploaded successfully! Awaiting admin approval.</p>";
    } else {
        echo "<p>❌ Failed to upload screenshot. Please try again.</p>";
    }
}
?>

<h2>📱 Pay via UPI</h2>
<p>Scan the QR code below to pay for the event.</p>

<!-- Display QR Code -->
<img src="payment.jpg" alt="UPI QR Code" width="300">

<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="event_id" value="<?php echo $_GET['event_id']; ?>">
    <p>📷 Upload Payment Screenshot:</p>
    <input type="file" name="payment_screenshot" required>
    <br><br>
    <button type="submit" name="submit_payment">📤 Submit Payment Proof</button>
</form>

<br><a href="events.php">⬅️ Back to Events</a>