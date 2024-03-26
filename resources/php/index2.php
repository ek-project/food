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

        if (isset($_POST["goal"]) && isset($_POST["gender"]) && isset($_POST["age"]) && isset($_POST["weight"]) && isset($_POST["height"])  && isset($_POST["days"]) && isset($_POST["meal"]) && isset($_POST["diet"]) && isset($_POST["sty"]) && isset($_POST["choose"])) {
            // Retrieve form data
            $goal = htmlspecialchars(trim($_POST["goal"]));
            $gender = htmlspecialchars(trim($_POST["gender"]));
            $age = htmlspecialchars(trim($_POST["age"]));
            $weight = htmlspecialchars(trim($_POST["weight"]));
            $height = htmlspecialchars(trim($_POST["height"]));
            $days = htmlspecialchars(trim($_POST["days"]));
            $meal = htmlspecialchars(trim($_POST["meal"]));
            $diet = htmlspecialchars(trim($_POST["diet"]));
            $sty = htmlspecialchars(trim($_POST["sty"]));
            $choose = htmlspecialchars(trim($_POST["choose"]));
            $usernamept = $_SESSION['username'];


            /*if ($days === "4w") { 
                $fweek = 28;
            } else if ($days === "2w") {
                $fweek = 14;
            } else {
                $fweek = 3;
            }*/
            

            // Check if an active subscription already exists for the account
            $checkQuery = "SELECT duration FROM subscription WHERE username = ?";
            $checkStmt = $conn->prepare($checkQuery);
            $checkStmt->bind_param("s", $usernamept);
            $checkStmt->execute();
            $checkStmt->store_result();
            $checkStmt->bind_result($duration); // bind the result set columns to PHP variable
            $checkStmt->fetch();

            if ($duration === "4w") { 
                $fweek = 28;
            } else if ($duration === "2w") {
                $fweek = 14;
            } else {
                $fweek = 3;
            }

            $checkQuery2 = "SELECT * FROM subscription WHERE username = ? AND NOW() <= DATE_ADD(datein, INTERVAL ? DAY)";
            $checkStmt2 = $conn->prepare($checkQuery2);
            $checkStmt2->bind_param("si", $usernamept, $fweek);
            $checkStmt2->execute();
            $checkStmt2->store_result();

            if ($checkStmt2->num_rows > 0) {
                // An active subscription already exists, do not insert a new one
                echo "You already have an active subscription.";
            }else {

                $_SESSION['goal'] = $goal;
                $_SESSION['gender']= $gender;
                $_SESSION['age'] = $age;
                $_SESSION['weight'] = $weight;
                $_SESSION['height'] = $height;
                $_SESSION['days'] = $days;
                $_SESSION['meal'] = $meal;
                $_SESSION['diet'] = $diet;
                $_SESSION['sty'] = $sty;
                $_SESSION['choose'] = $choose;

                echo "Redirecting to payment Page...";
            }

        } else if (isset($_POST['price'])) {
            $_SESSION['price'] = $_POST['price'];

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