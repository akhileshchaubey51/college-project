<?php
$password = "Admin@123"; // Your desired admin password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
echo "Hashed Password: " . $hashed_password;
