<?php

require 'validate.php';


// Check if the form has been submitted
if (isset($_POST['submit'])) {

    // Get the form data and perform input validation
    $name = validateInput($_POST['name']);
    $email = validateInput($_POST['email']);
    $office = validateInput($_POST['office']);

    // Connect to the database
    $DB_CONNECT = new mysqli("localhost", "DevLog", "BjWQHm+46gN7", "DevLog");

    // Check connection
    if ($DB_CONNECT->connect_error) {
        die("Connection failed: " . $DB_CONNECT->connect_error);
    }

    // Sanitize Input
    $name = sanitizeInput($DB_CONNECT, $name);
    $email = sanitizeInput($DB_CONNECT, $email);
    $office = sanitizeInput($DB_CONNECT, $office);

    // Format Strings
    $name = strtoupper($name);
    $email = strtolower($email);
    $office = strtoupper($office);


    // Prepare the statement
    $stmt = $DB_CONNECT->prepare("INSERT INTO Client 
	(name, email, office) VALUES (?, ?, ?)");
    
    if ($stmt) {
        // Bind the parameters
        $stmt->bind_param("sss", $name, $email, $office);

        // Execute the statement
        if ($stmt->execute()) {
            echo "New client added successfully";
            header('Location: index.php#add_client');
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

