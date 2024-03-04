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

    if (isset($_POST["username"]) && isset($_POST["pwd"])) {
        // Retrieve form data
        $usernamept = htmlspecialchars(trim($_POST["username"]));
        $passwordpt = htmlspecialchars(trim($_POST["pwd"]));


        if (filter_var($usernamept, FILTER_VALIDATE_EMAIL)) {

            $checkQuery = "SELECT * FROM cdetails WHERE email = ?";
            $checkStmt = $conn->prepare($checkQuery);
            $checkStmt->bind_param("s", $usernamept);
            $checkStmt->execute();
            $checkStmt->store_result();

            $checkStmt->bind_result($username, $email, $password); // bind the result set columns to PHP variable
            $checkStmt->fetch();

            if (($usernamept == $email) && (password_verify($passwordpt,$password))) {
                echo "Login Sucessful";
            } else {
                echo "Login Failed";
            }


        } else {
            $checkQuery = "SELECT * FROM cdetails WHERE username = ?";
            $checkStmt = $conn->prepare($checkQuery);
            $checkStmt->bind_param("s", $usernamept);
            $checkStmt->execute();
            $checkStmt->store_result();

            $checkStmt->bind_result($username, $email, $password); // bind the result set columns to PHP variable
            $checkStmt->fetch();

            if (($usernamept == $username) && (password_verify($passwordpt,$password))) {
                echo "Login Sucessful";
            } else {
                echo "Login Failed";
            }
        }


    } else {
        // Handle case where one or more keys are not set
        echo "Error: One or more form fields are missing.";
    }
    
}

// Close the database connection
$conn->close();
?>