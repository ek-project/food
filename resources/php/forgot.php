<?php

$servername = "localhost";
$username = "root";
$password = "subham2003";
$dbname = "foodelight";

try {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST["email"])) {
            // Retrieve form data
            $emailpt = htmlspecialchars(trim($_POST["email"]));
                
            $checkQuery = "SELECT * FROM cdetails WHERE email = ?";

            if (!($checkStmt = $conn->prepare($checkQuery))) {
                throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
            }

            if (!$checkStmt->bind_param("s", $emailpt)) {
                throw new Exception("Binding parameters failed: (" . $checkStmt->errno . ") " . $checkStmt->error);
            }

            if (!$checkStmt->execute()) {
                throw new Exception("Execute failed: (" . $checkStmt->errno . ") " . $checkStmt->error);
            }

            $checkStmt->store_result();
            $checkStmt->bind_result($username, $email, $password); // bind the result set columns to PHP variable
            $checkStmt->fetch();

            if ($checkStmt->num_rows() > 0) {
                if ($emailpt == $email) {
                    echo "Login Successful";
                } else {
                    echo "Invalid credentials1";
                }

            } else {
                echo "Invalid credentials2";
            }

        } else {
            // Handle case where one or more keys are not set
            throw new Exception("Error: One or more form fields are missing.");
        }
        
    }

    // Close the database connection
    $conn->close();
} catch (Exception $e) {
    echo  $e->getMessage(), "\n";
}
?>