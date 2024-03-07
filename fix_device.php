<?php
// Your database connection code goes here

if (isset($_POST['device_id'])) {
    
    $device_id = $_POST['device_id'];

    // Connect to the database
    $DB_CONNECT = new mysqli("localhost", "DevLog", "BjWQHm+46gN7", "DevLog");
    
    // Check connection
    if ($DB_CONNECT->connect_error) {
        die("Connection failed: " . $DB_CONNECT->connect_error);
    }

    
    // Check Status to Toggle
    $GET_STATUS = mysqli_query($DB_CONNECT, "SELECT status FROM Device WHERE ID = '$device_id'");

    if ($GET_STATUS) {
        $status_row = mysqli_fetch_assoc($GET_STATUS);
        $current_status = $status_row['status'];

        if ($current_status == '0') {
            $QUERY = "UPDATE Device SET status = '1' WHERE ID = '$device_id'";
            $RESULT = mysqli_query($DB_CONNECT, $QUERY);
        } else {
            $QUERY = "UPDATE Device SET status = '0' WHERE ID = '$device_id'";
            $RESULT = mysqli_query($DB_CONNECT, $QUERY);
        }

        if (!$RESULT) {
            die("Device update failed: " . mysqli_error($DB_CONNECT));
        } else {
            echo "Device status updated.";
            header('Location: index.php#view_devices');
            exit();
        }
    } else {
        die("Error fetching device status: " . mysqli_error($DB_CONNECT));
    }

} else {
    echo "Invalid device ID.";
}

?>
