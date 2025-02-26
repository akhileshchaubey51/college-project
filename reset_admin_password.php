<?php
$new_password = "NewAdmin@123"; // Your new desired password
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
echo "New Hashed Password: " . $hashed_password;
