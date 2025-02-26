<?php
include 'config.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle Approval/Rejection
if (isset($_POST['update_status'])) {
    $payment_id = intval($_POST['payment_id']);
    $new_status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE upi_payments SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $payment_id);
    $stmt->execute();

    echo "<p>✅ Payment status updated!</p>";
}

// Fetch all UPI payments
$result = $conn->query("SELECT p.*, u.name AS user_name, e.title AS event_title FROM upi_payments p JOIN users u ON p.user_id = u.id JOIN events e ON p.event_id = e.id");
?>

<h2>💳 UPI Payments Management</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>User</th>
        <th>Event</th>
        <th>Screenshot</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php while ($payment = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($payment['user_name']); ?></td>
            <td><?php echo htmlspecialchars($payment['event_title']); ?></td>
            <td><a href="uploads/<?php echo $payment['screenshot']; ?>" target="_blank">📷 View</a></td>
            <td><?php echo $payment['status']; ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="payment_id" value="<?php echo $payment['id']; ?>">
                    <select name="status">
                        <option value="Pending" <?php if ($payment['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                        <option value="Approved" <?php if ($payment['status'] == 'Approved') echo 'selected'; ?>>Approved</option>
                        <option value="Rejected" <?php if ($payment['status'] == 'Rejected') echo 'selected'; ?>>Rejected</option>
                    </select>
                    <button type="submit" name="update_status">✅ Update</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
</table>