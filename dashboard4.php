<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="bg-dark text-white p-3" style="width: 250px; height: 100vh;">
            <h4 class="mb-4">Admin Panel</h4>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="dashboard.php" class="nav-link text-white">📊 Dashboard</a></li>
                <li class="nav-item"><a href="categories.php" class="nav-link text-white">📁 Categories</a></li>
                <li class="nav-item"><a href="sponsors.php" class="nav-link text-white">🏆 Sponsors</a></li>
                <li class="nav-item"><a href="events.php" class="nav-link text-white">🎉 Events</a></li>
                <li class="nav-item"><a href="users.php" class="nav-link text-white">👤 Users</a></li>
                <li class="nav-item"><a href="bookings.php" class="nav-link text-white">📅 Bookings</a></li>
                <li class="nav-item"><a href="news.php" class="nav-link text-white">📰 News</a></li>
                <li class="nav-item"><a href="settings.php" class="nav-link text-white">⚙️ Settings</a></li>
                <li class="nav-item"><a href="logout.php" class="nav-link text-danger">🚪 Logout</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="p-4" style="flex:1;">
            <h2>Welcome to Admin Dashboard 🎉</h2>
            <p>Manage all aspects of the College Event Management System from here.</p>

            <div class="row">
                <div class="col-md-3">
                    <div class="card text-center bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Events</h5>
                            <p class="card-text">10 Active Events</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-center bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Bookings</h5>
                            <p class="card-text">120 Bookings</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-center bg-warning text-white">
                        <div class="card-body">
                            <h5 class="card-title">Users</h5>
                            <p class="card-text">200 Registered</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-center bg-danger text-white">
                        <div class="card-body">
                            <h5 class="card-title">Sponsors</h5>
                            <p class="card-text">15 Sponsors</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
