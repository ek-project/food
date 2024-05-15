<?php

session_start();

date_default_timezone_set("Asia/Kolkata");

require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

// Database connection parameters
include 'config.php';
include 'smtp.php';

try {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST["name"]) && isset($_POST["email"]) && isset($_POST["pwd2"])) {
            // Retrieve form data

            $username = htmlspecialchars($_POST["name"]);
            $email = htmlspecialchars($_POST["email"]);
            $password = htmlspecialchars($_POST["pwd2"]);

            // Store data in session
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $password;

            // Check if username already exists
            $checkQuery = "SELECT * FROM user WHERE username = ? OR email = ?";
            $checkStmt = $conn->prepare($checkQuery);
            $checkStmt->bind_param("ss", $username, $email);
            $checkStmt->execute();
            $checkStmt->store_result();

            if ($checkStmt->num_rows > 0) {
                // Username already taken, display an error message
                echo "Email is already taken. Please choose a different one.";
            } else {
                $otppt = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

                $mail = new PHPMailer(true);
    
                try {
                    //Server settings
                    $mail->SMTPDebug = 0;                                 
                    $mail->isSMTP();                                      
                    $mail->Host = $smtphost;  
                    $mail->SMTPAuth = true;                               
                    $mail->Username = $smtpusername;                 
                    $mail->Password = $smtppassword;                           
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;                            
                    $mail->Port = $smtpport;                                    
        
                    //Recipients
                    $mail->setFrom($smtpusername, 'Foodelight');
                    $mail->addAddress($email);     
        
                    //Content
                    $mail->isHTML(true);                                  
                    $mail->Subject = 'Signup to Foodelight';
                    $mail->Body    = "Hi " . $username . ",<br><br>Your OTP for Signing Up is: " . $otppt . "<br><br>This OTP is valid for 2 minutes only.<br><br>Please use this OTP to create your account.<br><br>Thanks,<br>Foodelight";
                    
        
                    $result = $mail->send();
                    $expiry = 0;
    
                    echo 'OTP has been sent to your email';
    
                    if ($result == 1) {
                        $checkQuery1 = "INSERT INTO otp(otp,expired) VALUES (?,?)";
    
                        if (!($checkStmt1 = $conn->prepare($checkQuery1))) {
                            throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
                        }
    
                        if (!$checkStmt1->bind_param("ii", $otppt, $expiry)) {
                            throw new Exception("Binding parameters failed: (" . $checkStmt1->errno . ") " . $checkStmt->error);
                        }
    
                        if (!$checkStmt1->execute()) {
                            throw new Exception("Error: " . $checkStmt1->error);
                        }
    
                        $checkStmt1->close();
    
                    } else {
                        echo "ERROR";
                    }
    
                } catch (Exception $e) {
                    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
                }
            }

        }else if (isset($_POST["otp"])) {
            try {

                $username = $_SESSION['username'];
                $email = $_SESSION['email'];
                $password = $_SESSION['password'];

                $checkStmt = $conn->prepare("SELECT * FROM otp WHERE otp = ? AND expired != 1 AND NOW() <= DATE_ADD(created, INTERVAL 2 MINUTE)");
                if ($checkStmt === false) {
                    throw new Exception($conn->error);
                }
                $checkStmt->bind_param("s", $_POST["otp"]);
                $checkStmt->execute();
                $result = $checkStmt->get_result();

                if ($result->num_rows > 0) {
                    $updateStmt = $conn->prepare("UPDATE otp SET expired = 1 WHERE otp = ?");
                    if ($updateStmt === false) {
                        throw new Exception($conn->error);
                    }
                    $updateStmt->bind_param("s", $_POST["otp"]);
                    $updateStmt->execute();

                    $updateStmt->close();

                    // Get the current timestamp
                    $timestamp = microtime(true);

                    // Remove the decimal point from the timestamp
                    $timestamp = str_replace('.', '', $timestamp);

                    // Get the last 6 digits of the timestamp
                    $timestamp = substr($timestamp, -6);

                    // Generate a random 3-digit number
                    $randomNumber = random_int(100, 999);

                    // Combine the timestamp and the random number to create a unique 6-digit user ID
                    $userId = $timestamp . $randomNumber;

                    $_SESSION['userId'] = $userId;

                    // Hash the password
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    // Username is available, proceed with insertion
                    $insertQuery = "INSERT INTO user (username, email, password, userid) VALUES (?, ?, ?, ?)";
                    $insertStmt = $conn->prepare($insertQuery);
                    $insertStmt->bind_param("sssi", $username, $email, $hashedPassword, $userId);
    
                    if (!$insertStmt->execute()) {
                        throw new Exception("Error: " . $insertStmt->error);
                    } else {
                        $mail = new PHPMailer(true);
    
                        try {
                            //Server settings
                            $mail->SMTPDebug = 0;                                 
                            $mail->isSMTP();                                      
                            $mail->Host = $smtphost;  
                            $mail->SMTPAuth = true;                               
                            $mail->Username = $smtpusername;                 
                            $mail->Password = $smtppassword;                           
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;                            
                            $mail->Port = $smtpport;                                    
                
                            //Recipients
                            $mail->setFrom($smtpusername, 'Foodelight');
                            $mail->addAddress($email);     
                
                            //Content
                            $mail->isHTML(true);                                  
                            $mail->Subject = 'Foodelight details';
                            $mail->Body    = "Hi " . $username . ",<br><br>Your UserID for Foodelight: " . $userId . "<br><br>UserID can be referenced in the future.<br><br>Thanks,<br>Foodelight";
                            
                
                            $result = $mail->send();
                            $expiry = 0;
            
                            echo 'UserID has been sent to your email';
            
                            if ($result == 1) {
                                $checkQuery1 = "INSERT INTO otp(otp,expired) VALUES (?,?)";
            
                                if (!($checkStmt1 = $conn->prepare($checkQuery1))) {
                                    throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
                                }
            
                                if (!$checkStmt1->bind_param("ii", $otppt, $expiry)) {
                                    throw new Exception("Binding parameters failed: (" . $checkStmt1->errno . ") " . $checkStmt->error);
                                }
            
                                if (!$checkStmt1->execute()) {
                                    throw new Exception("Error: " . $checkStmt1->error);
                                }
            
                                $checkStmt1->close();
            
                            } else {
                                echo "ERROR";
                            }
            
                        } catch (Exception $e) {
                            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
                        }

                        $event = "Signup to Foodelight";

                        $insertQuery = "INSERT INTO activitylog (userid, email, event) VALUES (?, ?, ?)";
                        $insertStmt = $conn->prepare($insertQuery);
                        $insertStmt->bind_param("iss", $userId, $email, $event);
                        if(!$insertStmt->execute()) {
                            throw new Exception("Error: " . $insertStmt->error);
                        }
                    }
    
                    // Close the prepared statements
                    $insertStmt->close();


                } else {
                    echo "Invalid OTP!";
                }
            } catch (Exception $e) {
                die("Error: " . $e->getMessage());
            }

        } else if (isset($_POST["name"])) {
            
            $username = htmlspecialchars($_POST["name"]);

            // Check if username already exists
            $checkQuery = "SELECT * FROM user WHERE username = ?";
            $checkStmt = $conn->prepare($checkQuery);
            $checkStmt->bind_param("s", $username);
            $checkStmt->execute();
            $checkStmt->store_result();

            if ($checkStmt->num_rows > 0) {
                // Username already taken, display an error message
                echo "Username already taken. Please choose a different one.";
            } else {
                echo "Username available.";
            }

        }else {
            // Handle case where one or more keys are not set
            throw new Exception("Error: One or more form fields are missing2.");
        }
        
    }
    // Close the database connection
    $conn->close();
} catch (Exception $e) {
    echo  $e->getMessage(), "\n";
}

?>