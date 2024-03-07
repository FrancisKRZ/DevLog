<?php
require 'validate.php';

// Check if the form has been submitted
if (isset($_POST['submit'])) {
    // Get the form data and perform input validation
    $id = validateInput($_POST['id']);
    $brand = validateInput($_POST['brand']);
    $type = validateInput($_POST['type']);
    $problem = validateInput($_POST['problem']);
    $date_of_problem = validateInput($_POST['date_of_problem']);
    $client_email = validateInput($_POST['client_email']);
    $status = validateInput($_POST['status']);

    // Connect to the database
    $DB_CONNECT = new mysqli("localhost", "DevLog", "BjWQHm+46gN7", "DevLog");

    // Check connection
    if ($DB_CONNECT->connect_error) {
        die("Connection failed: " . $DB_CONNECT->connect_error);
    }

    // Sanitize the form data
    $id = sanitizeInput($DB_CONNECT, $id);
    $brand = sanitizeInput($DB_CONNECT, $brand);
    $type = sanitizeInput($DB_CONNECT, $type);
    $problem = sanitizeInput($DB_CONNECT, $problem);
    $date_of_problem = sanitizeInput($DB_CONNECT, $date_of_problem);
    $client_email = sanitizeInput($DB_CONNECT, $client_email);
    $status = sanitizeInput($DB_CONNECT, $status);

    // Prepare the statement
    $stmt = $DB_CONNECT->prepare("UPDATE Device SET brand=?, type=?, problem=?, 
    date_of_problem=?, client_email=?, status=? WHERE ID=?");

    if ($stmt) {
        // Bind the parameters
        $stmt->bind_param("ssssssi", $brand, $type, $problem, $date_of_problem,
        $client_email, $status, $id);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Device updated successfully";
            header('Location: index.php#view_devices');
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

// Fetch the device details for pre-filling the form
if (isset($_GET['id'])) {
    // Connect to the database
    $DB_CONNECT = new mysqli("localhost", "DevLog", "BjWQHm+46gN7", "DevLog");

    // Check connection
    if ($DB_CONNECT->connect_error) {
        die("Connection failed: " . $DB_CONNECT->connect_error);
    }

    // Sanitize the device ID
    $id = sanitizeInput($DB_CONNECT, $_GET['id']);

    // Prepare the statement
    $stmt = $DB_CONNECT->prepare("SELECT * FROM Device WHERE ID=?");

    if ($stmt) {
        // Bind the parameter
        $stmt->bind_param("i", $id);

        // Execute the statement
        if ($stmt->execute()) {
            // Get the result
            $result = $stmt->get_result();

            // Fetch the device data
            $device = $result->fetch_assoc();

            // Close the result and statement
            $result->close();
            $stmt->close();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Error: " . $DB_CONNECT->error;
    }

    // Close the database connection
    $DB_CONNECT->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Device</title>
    <style>
        /* Add your custom CSS styling here */
    </style>
</head>
<body>
    <h2>Edit Device</h2>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="hidden" name="id" value="<?php echo $device['ID']; ?>">
        <label for="brand">Brand:</label>
        <input type="text" name="brand" value="<?php echo $device['brand']; ?>"><br>
        <label for="type">Type:</label>
        <input type="text" name="type" value="<?php echo $device['type']; ?>"><br>
        <label for="problem">Problem:</label>
        <input type="text" name="problem" value="<?php echo $device['problem']; ?>"><br>
        <label for="date_of_problem">Date of Problem:</label>
        <input type="text" name="date_of_problem" value="<?php echo $device['date_of_problem']; ?>"><br>
        <label for="client_email">Client Email:</label>
        <input type="email" name="client_email" value="<?php echo $device['client_email']; ?>"><br>
        <label for="status">Status:</label>
        <input type="text" name="status" value="<?php echo $device['status']; ?>"><br>
        <input type="submit" name="submit" value="Update Device">
    </form>
</body>
</html>
