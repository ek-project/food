<?php

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "subham2003";
$dbname = "foodelight";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["name"]) && isset($_POST["email"]) && isset($_POST["pwd"])) {
        // Retrieve form data
        $username = htmlspecialchars($_POST["name"]);
        $email = htmlspecialchars($_POST["email"]);
        $password = htmlspecialchars($_POST["pwd"]);

        // Check if username already exists
        $checkQuery = "SELECT username FROM cdetails WHERE username = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("s", $username);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            // Username already taken, display an error message
            echo "Username is already taken. Please choose a different one.";
        } else {
            // Username is available, proceed with insertion
            $insertQuery = "INSERT INTO cdetails (username, email, password) VALUES (?, ?, ?)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bind_param("sss", $username, $email, $password);

            if ($insertStmt->execute()) {
                echo "Record added successfully!";
            } else {
                echo "Error: " . $insertStmt->error;
            }

            // Close the prepared statements
            $insertStmt->close();
        }

        // Close the prepared statement
        $checkStmt->close();


    } else {
        // Handle case where one or more keys are not set
        echo "Error: One or more form fields are missing.";
    }
    
}

// Close the database connection
$conn->close();
?>