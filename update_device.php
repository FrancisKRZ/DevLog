<?php

// Made to be used with the Edit Button

require 'validate.php';

// Connect to the database
$DB_CONNECT = mysqli_connect("localhost", "DevLog", "BjWQHm+46gN7", "DevLog");

// Check for errors in the database connection
if (!$DB_CONNECT) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the device id from the form submission
$device_id = $_POST['device_id'];

// Get the updated values from the form fields
$brand = validateInput($_POST['brand']);
$type = validateInput($_POST['type']);
$problem = validateInput($_POST['problem']);
$date_of_problem = validateInput($_POST['date_of_problem']);
$status = validateInput($_POST['status']);

// Update the device with the specified id in the database
$query = "UPDATE Device SET
            brand = '$brand',
            type = '$type',
            problem = '$problem',
            date_of_problem = '$date_of_problem',
            status = '$status'
          WHERE id = '$device_id'";

if (mysqli_query($DB_CONNECT, $query)) {
    // Device updated successfully, redirect to index.php
    header('Location: index.php#view_devices');
    exit();
} else {
    // Error updating device, display error message
    echo "Error updating device: " . mysqli_error($DB_CONNECT);
}

// Close the database connection
mysqli_close($DB_CONNECT);

?>
