<?php

require 'validate.php';


// Check if the form has been submitted
if (isset($_POST['submit'])) {
	
    // Get the form data
    $id = validateInput($_POST['id']);
	$brand = validateInput($_POST['brand']);
	$type = validateInput($_POST['type']);
	$problem = validateInput($_POST['problem']);
	$date_of_problem = validateInput($_POST['date_of_problem']);
	$status = 0; // Default value
	$client_email = validateInput($_POST['client_email']);

	// Connect to the database
	$DB_CONNECT = new mysqli("localhost", "DevLog", "BjWQHm+46gN7", "DevLog");
	
	// Check connection
	if ($DB_CONNECT->connect_error) {
		die("Connection failed: " . $DB_CONNECT->connect_error);
	}
	
    // Sanitize Input Data
    $id = sanitizeInput($DB_CONNECT, $id);
    $brand = sanitizeInput($DB_CONNECT, $brand);
    $type = sanitizeInput($DB_CONNECT, $type);
    $problem = sanitizeInput($DB_CONNECT, $problem);
    $date_of_problem = sanitizeInput($DB_CONNECT, $date_of_problem);
    $client_email = sanitizeInput($DB_CONNECT, $client_email);


    // Format Strings
    $id = strtoupper($id);
    $brand = strtoupper($brand);
    $type = strtoupper($type);
    $client_email = strtolower($email);


	// Insert the new device into the database
    // Prepare the statement
    $stmt = $DB_CONNECT->prepare("REPLACE INTO Device 
							(id, brand, type, problem, 
				date_of_problem, status, client_email)
						VALUES (?, ?, ?, ?, ?, ?, ?)");
	
    if ($stmt) {
        // Bind the parameters with the sanitized values
        $stmt->bind_param("sssssis", $id, $brand, $type, $problem, 
		$date_of_problem, $status, $client_email);

        // Execute the statement
        if ($stmt->execute()) {
            echo "New device added successfully";
            header('Location: index.php#add_device');
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