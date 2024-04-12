<?php

session_start();

include "config.php";

try {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST['id']) && isset($_POST['email']))  {

            $id = $_POST['id'];
            $email = $_POST['email'];


            // Prepare the SQL statements
            $sqlUser = "DELETE FROM user WHERE userid = ?";
            $sqlSubscription = "DELETE FROM subscription WHERE userid = ?";
            $sqlTransaction = "DELETE FROM transaction WHERE userid = ?";

            // Delete from user table
            $stmtUser = $conn->prepare($sqlUser);
            $stmtUser->bind_param("i", $id);
            $stmtUser->execute();

            // Delete from subscription table
            $stmtSubscription = $conn->prepare($sqlSubscription);
            $stmtSubscription->bind_param("i", $id);
            $stmtSubscription->execute();

            // Delete from transaction table
            $stmtTransaction = $conn->prepare($sqlTransaction);
            $stmtTransaction->bind_param("i", $id);
            $stmtTransaction->execute();

            // Check if the deletions were successful
            if($stmtUser->affected_rows > 0 && $stmtSubscription->affected_rows > 0 && $stmtTransaction->affected_rows > 0) {
                echo 1; // success

                $event = "Account Deletion in Foodelight";

                $insertQuery = "INSERT INTO activitylog (userid, email, event) VALUES (?, ?, ?)";
                $insertStmt = $conn->prepare($insertQuery);
                $insertStmt->bind_param("iss", $id, $email, $event);
                if(!$insertStmt->execute()) {
                    throw new Exception("Error: " . $insertStmt->error);
                }

            } else {
                echo "Something Went Wrong"; // failure
            }

        }
    }


} catch (Exception $e) {
    echo  $e->getMessage(), "\n";
}

?>