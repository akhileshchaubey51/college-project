<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $event_date = $_POST['event_date'];
    $venue = $_POST['venue'];
    $is_paid = isset($_POST['is_paid']) ? 1 : 0;
    $price = $is_paid ? max(0, $_POST['price']) : 0;

    $stmt = $conn->prepare("INSERT INTO events (title, event_date, venue, is_paid, price) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssii", $title, $event_date, $venue, $is_paid, $price);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Event added successfully!'); window.location.href='manage_events.php';</script>";
        exit();
    } else {
        $error = "❌ Error adding event.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event</title>
    <style>
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #f6d365, #fda085);
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        h2 {
            text-align: center;
            color: #444;
            animation: slideIn 1s ease-out;
        }

        /* Form Styling */
        form {
            background-color: #fff;
            padding: 20px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
            animation: fadeIn 1.5s ease-in;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.3s ease;
        }

        input:focus {
            border-color: #007bff;
            outline: none;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        /* Checkbox */
        input[type="checkbox"] {
            margin-right: 5px;
        }

        /* Button */
        button {
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        /* Error Message */
        p {
            color: red;
            text-align: center;
            animation: shake 0.5s ease;
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

        @keyframes shake {
            0% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            50% { transform: translateX(5px); }
            75% { transform: translateX(-5px); }
            100% { transform: translateX(0); }
        }
    </style>
</head>

<body>

    <form method="POST">
        <h2>➕ Add New Event</h2>
        <?php if (isset($error)) echo "<p>$error</p>"; ?>

        <label for="title">Event Title:</label>
        <input type="text" name="title" id="title" placeholder="Enter event title" required>

        <label for="event_date">Event Date:</label>
        <input type="date" name="event_date" id="event_date" required>

        <label for="venue">Venue:</label>
        <input type="text" name="venue" id="venue" placeholder="Event venue" required>

        <label>
            <input type="checkbox" name="is_paid" id="is_paid"> Paid Event
        </label>

        <input type="number" name="price" id="price" placeholder="Price (₹)" min="0" style="display:none;">

        <button type="submit">Add Event</button>
    </form>

    <script>
        // Show/Hide price field for paid events
        const isPaidCheckbox = document.getElementById('is_paid');
        const priceField = document.getElementById('price');

        isPaidCheckbox.addEventListener('change', function() {
            priceField.style.display = this.checked ? 'block' : 'none';
            if (!this.checked) priceField.value = '';
        });

        // Form validation before submission
        document.querySelector("form").addEventListener("submit", function(e) {
            const title = document.getElementById('title').value.trim();
            const date = document.getElementById('event_date').value;
            const venue = document.getElementById('venue').value.trim();

            if (!title || !date || !venue) {
                alert("❗ Please fill out all required fields.");
                e.preventDefault();
            }

            if (isPaidCheckbox.checked && (!priceField.value || priceField.value < 0)) {
                alert("❗ Please enter a valid price for the paid event.");
                e.preventDefault();
            }
        });
    </script>

</body>

</html>
