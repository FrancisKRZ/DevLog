<?php

// Function to sanitize user input
function validateInput($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

// Function to sanitize input data
function sanitizeInput($connection, $data) {
	// Escape special characters for use in SQL query
    $data = $connection->real_escape_string($data); 
    return $data;
}
?>