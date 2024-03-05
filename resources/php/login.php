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

        if (isset($_POST["username"]) && isset($_POST["pwd"])) {
            // Retrieve form data
            $usernamept = htmlspecialchars(trim($_POST["username"]));
            $passwordpt = htmlspecialchars(trim($_POST["pwd"]));
            
            if (filter_var($usernamept, FILTER_VALIDATE_EMAIL)) {
                $checkQuery = "SELECT * FROM cdetails WHERE email = ?";
            } else {
                $checkQuery = "SELECT * FROM cdetails WHERE username = ?";
            }

            if (!($checkStmt = $conn->prepare($checkQuery))) {
                throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
            }

            if (!$checkStmt->bind_param("s", $usernamept)) {
                throw new Exception("Binding parameters failed: (" . $checkStmt->errno . ") " . $checkStmt->error);
            }

            if (!$checkStmt->execute()) {
                throw new Exception("Execute failed: (" . $checkStmt->errno . ") " . $checkStmt->error);
            }

            $checkStmt->store_result();
            $checkStmt->bind_result($username, $email, $password); // bind the result set columns to PHP variable
            $checkStmt->fetch();

            if ($checkStmt->num_rows() > 0) {
                if (($usernamept == $username || $usernamept == $email) && (password_verify($passwordpt,$password))) {
                    // Start a session
                    if (session_start() === false) {
                        throw new Exception("Failed to start session");
                    }

                    if (session_regenerate_id(true) === false) {
                        throw new Exception("Failed to regenerate session ID");
                    }

                    // Store user data in the session
                    $_SESSION['username'] = $username;
                    $_SESSION['email'] = $email;

                    echo "Login Successful";
                } else {
                    echo "Invalid credentials";
                }

            } else {
                echo "Invalid credentials";
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