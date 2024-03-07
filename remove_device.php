<?php

// Connect to the database
$DB_CONNECT = mysqli_connect("localhost", "DevLog", "BjWQHm+46gN7", "DevLog");

// Check for errors in the database connection
if (!$DB_CONNECT) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the device id from the URL parameter
$device_id = $_GET['id'];

// Delete the device with the specified id from the database
$query = "DELETE FROM Device WHERE id = '$device_id'";

if (mysqli_query($DB_CONNECT, $query)) {
    // Device removed successfully, redirect to index.php
    header('Location: index.php#view_devices');
    exit();
} else {
    // Error deleting device, display error message
    echo "Error deleting device: " . mysqli_error($DB_CONNECT);
    sleep(3);
}

// Close the database connection
mysqli_close($DB_CONNECT);
header('Location: index.php#view_devices');
exit();

?>
