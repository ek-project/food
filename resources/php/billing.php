<?php

session_start();

date_default_timezone_set("Asia/Kolkata");

require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

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

        if (isset($_POST["paymentData"]) && isset($_POST["fullName"]) && isset($_POST["phoneNumber"]) && isset($_POST['streetAddress']) && isset($_POST["city"]) && isset($_POST["state"]) && isset($_POST["pinCode"])) {
            
            $username = $_SESSION['username'];
            $email = $_SESSION['email'];

            $otppt = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            $mail = new PHPMailer(true);
        
            try {
                //Server settings
                $mail->SMTPDebug = 0;                                 
                $mail->isSMTP();                                      
                $mail->Host = 'smtp.elasticemail.com';  
                $mail->SMTPAuth = true;                               
                $mail->Username = 'nileriver6630@gmail.com';                 
                $mail->Password = 'D46F06FCC4076DFE0DB9E16386B509454EA6';                           
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;                            
                $mail->Port = 2525;                                    
    
                //Recipients
                $mail->setFrom('nileriver6630@gmail.com', 'Foodelight');
                $mail->addAddress($email);     
    
                //Content
                $mail->isHTML(true);                                  
                $mail->Subject = 'Payment Authentication';
                $mail->Body    = "Hi " . $username . ",<br><br>Your OTP for Payment is: " . $otppt . "<br><br>This OTP is valid for 2 minutes only.<br><br>Please use this OTP for successful payment to the Foodelight.<br><br>Thanks,<br>Foodelight";
                
    
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


        }else if (isset($_POST["otp"])) {
            try {
                
                $username = $_SESSION['username'];
                $email = $_SESSION['email'];
                $goal = $_SESSION['goal'];
                $gender = $_SESSION['gender'];
                $days = $_SESSION['days'];
                $meal = $_SESSION['meal'];
                $diet = $_SESSION['diet'];
                $sty = $_SESSION['sty'];
                $choose = $_SESSION['choose'];
                $price = $_SESSION["tp"];
                $userid = $_SESSION['userId'];

                $transactionId = substr(bin2hex(random_bytes(8)), 0, 16);

                $timestamp = time(); // Get the current Unix timestamp
                $randomDigits = substr(bin2hex(random_bytes(8)), 0, 16 - strlen($timestamp)); // Generate the random digits
                $subscriptionId = $timestamp . $randomDigits;

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
    
                    echo "Your Order Successfully Placed";

                    $insertQuery2 = "INSERT INTO transaction (username, transactionid, userid) VALUES (?, ?, ?)";
                    $insertStmt2 = $conn->prepare($insertQuery2);
                    $insertStmt2->bind_param("ssi", $username, $transactionId, $userid);
        
                    $insertQuery = "INSERT INTO subscription (username, goal, gender, duration, meals, diet, type, mealtype, transactionid, amount,  subscriptionid, userid) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $insertStmt = $conn->prepare($insertQuery);
                    $insertStmt->bind_param("sssssssssdsi", $username, $goal, $gender, $days, $meal, $diet, $sty, $choose, $transactionId, $price, $subscriptionId, $userid);
        
                    if (!$insertStmt2->execute()) {
                        throw new Exception("Error: " . $insertStmt2->error);
                    } else if (!$insertStmt->execute()){
                        throw new Exception("Error: " . $insertStmt->error);
                    } else {

                        $mail2 = new PHPMailer(true);

                        try {
        
                            //Server settings
                            $mail2->SMTPDebug = 0;                                 
                            $mail2->isSMTP();                                      
                            $mail2->Host = 'smtp.elasticemail.com';  
                            $mail2->SMTPAuth = true;                               
                            $mail2->Username = 'nileriver6630@gmail.com';                 
                            $mail2->Password = 'D46F06FCC4076DFE0DB9E16386B509454EA6';                           
                            $mail2->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;                            
                            $mail2->Port = 2525;                                    
                
                            //Recipients
                            $mail2->setFrom('nileriver6630@gmail.com', 'Foodelight');
                            $mail2->addAddress($email);     
                
                            //Content
                            $mail2->isHTML(true);                                  
                            $mail2->Subject = 'Welcome to Foodelight';
                            $mail2->Body    = "Hi " . $username . ", <br><br>Your Transaction Id is " . $transactionId . " and Subscription ID is " . $subscriptionId . " for your subscription plan.<br><br>You have have subscribed with customised diet + food delivery.<br><br>Your meal will be delievred according to your timings.<br><br>Thanks,<br>Foodelight";
                            
                
                            $result = $mail2->send();
        
                        } catch (Exception $e) {
                            echo 'Message could not be sent. Mailer Error: ', $mail2->ErrorInfo;
                        }

                        $event = "Subscribed to Foodelight";

                        $insertQuery = "INSERT INTO activitylog (userid, email, event) VALUES (?, ?, ?)";
                        $insertStmt = $conn->prepare($insertQuery);
                        $insertStmt->bind_param("iss", $userid, $email, $event);
                        if(!$insertStmt->execute()) {
                            throw new Exception("Error: " . $insertStmt->error);
                        }
                    }
                } else {
                    echo "Invalid OTP!";
                }
            } catch (Exception $e) {
                die("Error: " . $e->getMessage());
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