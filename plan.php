<?php

session_start();

include "resources/php/config.php";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    throw new Exception("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];


$checkQuery = "SELECT transactionid,datein,amount FROM subscription WHERE username = ?";

if (!($checkStmt = $conn->prepare($checkQuery))) {
    throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

if (!$checkStmt->bind_param("s", $username)) {
    throw new Exception("Binding parameters failed: (" . $checkStmt->errno . ") " . $checkStmt->error);
}

if (!$checkStmt->execute()) {
    throw new Exception("Execute failed: (" . $checkStmt->errno . ") " . $checkStmt->error);
}

$checkStmt->store_result();
$checkStmt->bind_result($trasnsactionid, $datein ,$amount); // bind the result set columns to PHP variable
$checkStmt->fetch();

if ($trasnsactionid && $datein && $amount) {
    $result = "Success";
} else {
    $result = "Failed";
}






?>



<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Foodelight is a premium fodd delivery service with the mission to bring healthy foods as much as possible">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href = "resources/css/plan.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;1,300&display=swap" rel="stylesheet">
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <title>Foodelight</title>
        <link rel="icon" type="image/svg+xml" href="resources/favicons/favicon.svg">
        <link rel="icon" type="image/png" href="resources/favicons/favicon.png">
    </head>
    <body>
        <div class="header-bg">
            <header>
                <nav>
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col">
                            <a href="index2s.php">
                                <img src="resources/img/orange.svg" alt="Foodelight logo" class="logo">
                            </a>
                        </div>
                        <div class="col">
                            <ul class="main-nav js--main- p-0">
                                <li class="mr-3"><a href="#">Pricing</a></li>
                                <li class="mr-3"><a href="#">My Account</a></li>
                                <li class="mr-3"><a href="#">My Plans</a></li>
                                <li ><a href="logout.php">Logout</a></li>
                                <li><a href="#"><img id="profileImage" width="48" height="48" src="https://img.icons8.com/fluency/48/user-male-circle--v1.png" alt="Profile Photo" title="<?php echo $_SESSION['username']; ?>"/></a></li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </header>
        </div>
        <section class="mt-3">
            <h2 class="text-center mb-5">PLAN HISTORY</h2>
            <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css">
            <div class="container">
                <div class="row">
                    <div class="col-12 mb-3 mb-lg-5">
                        <div class="position-relative card table-nowrap table-card">
                            <div class="card-header align-items-center">
                                <h5 class="mb-0">Latest Transactions</h5>
                            </div>
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead class="small text-uppercase bg-body text-muted">
                                        <tr>
                                            <th>Transaction ID</th>
                                            <th>Date</th>
                                            <th>Name</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="align-middle">
                                            <td>
                                                <?php echo $trasnsactionid; ?>
                                            </td>
                                            <td><?php echo $datein; ?></td>
                                            <td><?php echo $username; ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span>₹<?php echo  number_format($amount,2); ?></span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge fs-6 fw-normal bg-tint-success text-success"><?php echo $result; ?></span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer text-end">
                                <a href="#!" class="btn btn-gray">View All Transactions</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <footer>
            <div class="row">
                <div class="col span-1-of-2">
                    <ul class="footer-nav">
                        <li><a href="#">About us</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Press</a></li>
                        <li><a href="#">iOS App</a></li>
                        <li><a href="#">Android App</a></li>
                    </ul>
                </div>
                <div class="col span-1-of-2">
                    <ul class="social-links">
                        <li>
                            <a href="#">
                                <ion-icon name="logo-facebook" class="facebook"></ion-icon>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <ion-icon name="logo-twitter" class="twitter"></ion-icon>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <ion-icon name="logo-linkedin" class="linkedin"></ion-icon>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <ion-icon name="logo-instagram" class="instagram"></ion-icon>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="row">
                    <p>
                        Copyright &copy; 2024 by Foodelight. All rights reserved. 
                    </p>
                </div>
            </div>
        </footer>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/respond.js@1.4.2/dest/respond.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/html5shiv@3.7.3/dist/html5shiv.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/selectivizr@1.0.3/selectivizr.min.js"></script>
        <script src="resources/js/script2.js"></script>
        <script src="vendors/js/jquery.waypoints.min.js"></script>
    </body>
</html>