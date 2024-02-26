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

        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO cdetails (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);

        if ($stmt->execute()) {
            echo "Record added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the prepared statement
        $stmt->close();

    } else {
        // Handle case where one or more keys are not set
        echo "Error: One or more form fields are missing.";
    }
    
}

// Close the database connection
$conn->close();
?>