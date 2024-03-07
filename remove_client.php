<?php

// Connect to the database
$DB_CONNECT = mysqli_connect("localhost", "DevLog", "BjWQHm+46gN7", "DevLog");

// Check for errors in the database connection
if (!$DB_CONNECT) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the device id from the URL parameter
$email= $_GET['email'];

// Delete the device with the specified id from the database
$query = "DELETE FROM Client WHERE email = '$email'";

if (mysqli_query($DB_CONNECT, $query)) {
    // Device removed successfully, redirect to index.php
    header('Location: index.php#view_client');
    exit();
} else {
    // Error deleting device, display error message
    echo "Error deleting client: " . mysqli_error($DB_CONNECT);
    sleep(3);
}

// Close the database connection
mysqli_close($DB_CONNECT);
header('Location: index.php#view_client');
exit();

?>
