<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch bookings including user name
$bookings_result = $conn->query("SELECT b.id, u.name AS user_name, u.email, e.title, e.event_date 
                                 FROM bookings b 
                                 JOIN users u ON b.user_id = u.id 
                                 JOIN events e ON b.event_id = e.id");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>📑 View Bookings</title>
    <style>
        /* Global Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #fdfbfb, #ebedee);
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #333;
        }

        h2 {
            text-align: center;
            color: #444;
            margin-bottom: 20px;
            animation: slideIn 0.5s ease-out;
        }

        /* Search Box */
        .search-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .search-box {
            width: 300px;
            padding: 10px 15px;
            border: 2px solid #007bff;
            border-radius: 25px;
            outline: none;
            transition: all 0.3s ease;
            font-size: 16px;
        }

        .search-box:focus {
            border-color: #0056b3;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
        }

        /* Table Styles */
        .table-container {
            width: 90%;
            max-width: 1000px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.8s ease-in;
        }

        th, td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: #fff;
            text-transform: uppercase;
        }

        tr:hover {
            background-color: #f1f1f1;
            transition: background-color 0.3s ease;
        }

        /* No Bookings Message */
        .no-bookings {
            text-align: center;
            font-size: 1.2em;
            color: #777;
            margin-top: 20px;
            animation: fadeIn 1s ease-in;
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

        /* Back Link */
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #0056b3;
        }
    </style>
</head>

<body>

    <h2>📑 All Event Bookings</h2>

    <!-- Search Box -->
    <div class="search-container">
        <input type="text" id="searchInput" class="search-box" placeholder="🔍 Search bookings...">
    </div>

    <!-- Bookings Table -->
    <div class="table-container">
        <?php if ($bookings_result->num_rows > 0): ?>
            <table id="bookingsTable">
                <tr>
                    <th>User Name</th>
                    <th>User Email</th>
                    <th>Event Title</th>
                    <th>Event Date</th>
                </tr>
                <?php while ($booking = $bookings_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($booking['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($booking['email']); ?></td>
                        <td><?php echo htmlspecialchars($booking['title']); ?></td>
                        <td><?php echo htmlspecialchars($booking['event_date']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p class="no-bookings">🚫 No bookings found.</p>
        <?php endif; ?>
    </div>

    <a href="admin_dashboard.php" class="back-link">⬅️ Back to Admin Dashboard</a>

    <script>
        // Search Functionality
        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('keyup', function () {
            const filter = searchInput.value.toLowerCase();
            const rows = document.querySelectorAll('#bookingsTable tr:not(:first-child)');

            rows.forEach(row => {
                const cells = row.getElementsByTagName('td');
                let match = false;

                for (let i = 0; i < cells.length; i++) {
                    if (cells[i].textContent.toLowerCase().includes(filter)) {
                        match = true;
                        break;
                    }
                }

                row.style.display = match ? '' : 'none';
            });
        });
    </script>

</body>

</html>
