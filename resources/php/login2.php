<?php

session_start();

date_default_timezone_set("Asia/Kolkata");

require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

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

        if (isset($_POST["username"]) && isset($_POST["pwd"])) {
            // Retrieve form data
            $usernamept = htmlspecialchars(trim($_POST["username"]));
            $passwordpt = htmlspecialchars(trim($_POST["pwd"]));
            
            
            $checkQuery = "SELECT * FROM admin WHERE username = ?";

            if (!($checkStmt = $conn->prepare($checkQuery))) {
                throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
            }

            if (!$checkStmt->bind_param("i", $usernamept)) {
                throw new Exception("Binding parameters failed: (" . $checkStmt->errno . ") " . $checkStmt->error);
            }

            if (!$checkStmt->execute()) {
                throw new Exception("Execute failed: (" . $checkStmt->errno . ") " . $checkStmt->error);
            }

            $checkStmt->store_result();
            $checkStmt->bind_result($adminid, $username, $password, $email); // bind the result set columns to PHP variable
            $checkStmt->fetch();

            if ($checkStmt->num_rows() > 0) {
                if (($usernamept == $username) && (password_verify($passwordpt,$password))) {

                    // Store user data in the session
                    $_SESSION['username'] = $username;
                    $_SESSION['email'] = $email;
                    
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
                        $mail->Subject = 'Login Authentication for Admim';
                        $mail->Body    = "Hi " . $username . ",<br><br>Your OTP for Login is: " . $otppt . "<br><br>This OTP is valid for 2 minutes only.<br><br>Please use this OTP to login to the Foodelight.<br><br>Thanks,<br>Foodelight";
                        
            
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
                } else {
                    echo "Invalid credentials";
                }

            } else {
                echo "Invalid credentials";
            }

        } else if (isset($_POST["otp"])) {
            try {
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

                    echo "Admin Login Success";

                } else {
                    echo "Invalid OTP!";
                }
            } catch (Exception $e) {
                die("Error: " . $e->getMessage());
            }
        } else if (isset($_POST["username"])) {
            $usernamept = htmlspecialchars(trim($_POST["username"]));


            $checkQuery = "SELECT * FROM user WHERE userid = ?";

            if (!($checkStmt = $conn->prepare($checkQuery))) {
                throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
            }

            if (!$checkStmt->bind_param("i", $usernamept)) {
                throw new Exception("Binding parameters failed: (" . $checkStmt->errno . ") " . $checkStmt->error);
            }

            if (!$checkStmt->execute()) {
                throw new Exception("Execute failed: (" . $checkStmt->errno . ") " . $checkStmt->error);
            }

            $checkStmt->store_result();

            if ($checkStmt->num_rows > 0) {
                // Username already taken, display an error message
                echo "Account Found!";
            } else {
                echo "Account Not Found! Please create your account.";
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