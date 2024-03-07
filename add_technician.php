<?php


require 'validate.php';


// Check if the form has been submitted
if (isset($_POST['submit'])) {

    // Get the form data and perform input validation
    $name = validateInput($_POST['name']);
    $labour_performed = validateInput($_POST['labour_performed']);
    $date_start = validateInput($_POST['date_start']);
    $date_finished = validateInput($_POST['date_finished']);
    $device_id = validateInput($_POST['device_id']);

    // Connect to the database
    $DB_CONNECT = new mysqli("localhost", "DevLog", "BjWQHm+46gN7", "DevLog");

    // Check connection
    if ($DB_CONNECT->connect_error) {
        die("Connection failed: " . $DB_CONNECT->connect_error);
    }

    // Sanitize the form data
    $name = sanitizeInput($DB_CONNECT, $name);
    $labour_performed = sanitizeInput($DB_CONNECT, $labour_performed);
    $date_start = sanitizeInput($DB_CONNECT, $date_start);
    $date_finished = sanitizeInput($DB_CONNECT, $date_finished);
    $device_id = sanitizeInput($DB_CONNECT, $device_id);


    // Format Strings
    $name = strtoupper($name);
    $device_id = strtoupper($device_id);


    // Prepare the statement
    $stmt = $DB_CONNECT->prepare("INSERT INTO Technician 
		(name, labour_performed, date_start, date_finished, device_id)
		VALUES (?, ?, ?, ?, ?)");

    if ($stmt) {
        // Bind the parameters
        $stmt->bind_param("sssss", $name, $labour_performed, $date_start, 
		$date_finished, $device_id);

        // Execute the statement
        if ($stmt->execute()) {
            echo "New technician added successfully";
            header('Location: index.php#add_technician');
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error: " . $DB_CONNECT->error;
    }

    // Close the database connection
    $DB_CONNECT->close();
}


?>