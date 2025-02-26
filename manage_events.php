<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch events
$events_result = $conn->query("SELECT * FROM events");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events</title>
    <style>
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #f6d365, #fda085);
            margin: 0;
            padding: 20px;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        h2 {
            text-align: center;
            color: #444;
            animation: slideIn 1s ease-out;
        }

        /* Add New Event Button */
        .add-event-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .add-event-btn:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        /* Table Styles */
        table {
            width: 90%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1.5s ease-in;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
            transition: background-color 0.3s ease;
        }

        /* Action Buttons */
        .action-btn {
            padding: 6px 12px;
            margin: 0 4px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: #fff;
            transition: transform 0.2s ease, background-color 0.3s ease;
        }

        .edit-btn {
            background-color: #ffc107;
        }

        .edit-btn:hover {
            background-color: #e0a800;
            transform: scale(1.1);
        }

        .delete-btn {
            background-color: #dc3545;
        }

        .delete-btn:hover {
            background-color: #c82333;
            transform: scale(1.1);
        }

        /* No Events Message */
        .no-events {
            text-align: center;
            font-size: 1.2em;
            color: #555;
            margin-top: 40px;
            animation: fadeIn 1s ease-in;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.5s ease;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            animation: slideIn 0.5s ease;
        }

        .modal-content h3 {
            margin-bottom: 15px;
        }

        .modal-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .modal-buttons button {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: #fff;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .confirm-btn {
            background-color: #dc3545;
        }

        .confirm-btn:hover {
            background-color: #c82333;
            transform: scale(1.1);
        }

        .cancel-btn {
            background-color: #6c757d;
        }

        .cancel-btn:hover {
            background-color: #5a6268;
            transform: scale(1.1);
        }

        /* Animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
</head>

<body>

    <h2>📋 Manage Events</h2>
    <div style="text-align: center;">
        <a href="add_event.php" class="add-event-btn">➕ Add New Event</a>
    </div>

    <?php if ($events_result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Title</th>
                <th>Date</th>
                <th>Venue</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
            <?php while ($event = $events_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($event['title']); ?></td>
                    <td><?php echo htmlspecialchars($event['event_date']); ?></td>
                    <td><?php echo htmlspecialchars($event['venue']); ?></td>
                    <td><?php echo $event['is_paid'] ? "₹" . $event['price'] : "Free"; ?></td>
                    <td>
                        <a href="edit_event.php?id=<?php echo $event['id']; ?>" class="action-btn edit-btn">✏️ Edit</a>
                        <button class="action-btn delete-btn" onclick="openModal(<?php echo $event['id']; ?>)">🗑️ Delete</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p class="no-events">🚫 No events found. Please add new events!</p>
    <?php endif; ?>

    <!-- Delete Confirmation Modal -->
    <div class="modal" id="deleteModal">
        <div class="modal-content">
            <h3>⚠️ Confirm Delete</h3>
            <p>Are you sure you want to delete this event?</p>
            <div class="modal-buttons">
                <button class="confirm-btn" id="confirmDelete">Yes, Delete</button>
                <button class="cancel-btn" onclick="closeModal()">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        let deleteEventId = null;

        // Open modal for delete confirmation
        function openModal(eventId) {
            deleteEventId = eventId;
            document.getElementById('deleteModal').style.display = 'flex';
        }

        // Close the modal
        function closeModal() {
            document.getElementById('deleteModal').style.display = 'none';
            deleteEventId = null;
        }

        // Confirm delete action
        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (deleteEventId) {
                window.location.href = 'delete_event.php?id=' + deleteEventId;
            }
        });

        // Close modal when clicking outside the modal content
        window.onclick = function(event) {
            const modal = document.getElementById('deleteModal');
            if (event.target === modal) {
                closeModal();
            }
        };
    </script>

</body>

</html>
