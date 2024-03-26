<?php

session_status();
session_start();

if ($_SESSION['days'] === "4w") { 
    $fweek = ($_SESSION['price']*0.1);
} else if ($_SESSION['days'] === "2w") {
    $fweek = ($_SESSION['price']*0.03);
} else {
    $fweek = ($_SESSION['price']*0.1);
}

$gst = $_SESSION['price'] * 0.05;
$cgst = $sgst = $gst / 2;
$total_amount = $_SESSION['price'] + $cgst + $sgst - $fweek;



?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Foodelight is a premium fodd delivery service with the mission to bring healthy foods as much as possible">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href = "resources/css/style3.css">
        <link rel="stylesheet" href = "resources/css/queries.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;1,300&display=swap" rel="stylesheet">
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <title>Foodelight</title>
        <link rel="icon" type="image/svg+xml" href="resources/favicons/favicon.svg">
        <link rel="icon" type="image/png" href="resources/favicons/favicon.png">

    </head>

    <body>
        <div class="container d-flex justify-content-center mt-5 mb-5">
            <div class="row g-3">
                <div class="col-md-6 contact-form">
                    <span>Payment Method</span>
                    <div class="card">
                        <div class="accordion" id="accordionExample">
                            <div class="card">
                                <div class="card-header p-0" id="headingTwo">
                                    <h2 class="mb-0">
                                        <button class="btn btn-light btn-block text-left collapsed p-3 rounded-0 border-bottom-custom" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" data-payment="paypal">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span>Paypal</span>
                                                <img src="https://i.imgur.com/7kQEsHU.png" width="30">
                                            </div>
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                    <div class="card-body paypal">
                                        <input type="text" class="form-control" id="paypal" placeholder="Paypal email">
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header p-0">
                                    <h2 class="mb-0">
                                        <button class="btn btn-light btn-block text-left p-3 rounded-0" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" data-payment="ccn">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span>Credit card</span>
                                                <div class="icons">
                                                    <img src="https://i.imgur.com/2ISgYja.png" width="30">
                                                    <img src="https://i.imgur.com/W1vtnOV.png" width="30">
                                                    <img src="https://i.imgur.com/35tC99g.png" width="30">
                                                    <img src="https://i.imgur.com/2ISgYja.png" width="30">
                                                </div>
                                            </div>
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                    <div class="card-body payment-card-body ccn">
                                        <span class="font-weight-normal card-text">Card Number</span>
                                        <div class="input">
                                            <i class="fa fa-credit-card"></i>
                                            <input type="text" class="form-control" id="ccn" placeholder="0000 0000 0000 0000">
                                        </div>
                                        <div class="row mt-3 mb-3">
                                            <div class="col-md-6">
                                                <span class="font-weight-normal card-text">Expiry Date</span>
                                                <div class="input">
                                                    <i class="fa fa-calendar"></i>
                                                    <input type="text" class="form-control" id="ccn1" placeholder="MM/YY">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <span class="font-weight-normal card-text">CVC/CVV</span>
                                                <div class="input">
                                                    <i class="fa fa-lock"></i>
                                                    <input type="text" class="form-control" id="ccn2" placeholder="000">
                                                </div>
                                            </div>
                                        </div>
                                        <span class="text-muted certificate-text"><i class="fa fa-lock"></i> Your transaction is secured with ssl certificate</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header p-0" id="headingThree">
                                    <h2 class="mb-0">
                                        <button class="btn btn-light btn-block text-left collapsed p-3 rounded-0 border-bottom-custom" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" data-payment="upi">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span>UPI</span>
                                                <img src="https://www.vectorlogo.zone/logos/upi/upi-ar21.svg" width="30">
                                            </div>
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseThree" class="collapse upi" aria-labelledby="headingThree" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <input type="text" class="form-control" id="upi"placeholder="UPI ID">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3 margin-bottom billing">
                        <div class="card-header p-0" id="billingAddressHeading">
                            <h2 class="mb-0">
                                <button class="btn btn-light btn-block text-left collapsed p-3 rounded-0 border-bottom-custom" type="button" data-toggle="collapse" data-target="#billingAddressCollapse" aria-expanded="false" aria-controls="billingAddressCollapse">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span>Billing Address</span>
                                        <i class="fa fa-caret-down"></i>
                                    </div>
                                </button>
                            </h2>
                        </div>
                        <div id="billingAddressCollapse" class="collapse" aria-labelledby="billingAddressHeading">
                            <div class="card-body">
                                <input type="text" class="form-control mb-2" id="fn" placeholder="Full Name">
                                <input type="tel" class="form-control mb-2" id="ph" placeholder="Phone Number (India)" pattern="[0-9]{10}">
                                <input type="text" class="form-control mb-2" id="sa" placeholder="Street Address">
                                <input type="text" class="form-control mb-2" id="ct" placeholder="City">
                                <input type="text" class="form-control mb-2" id="st" placeholder="State">
                                <input type="text" class="form-control mb-2" id="pin" placeholder="PIN Code">
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-6">
                    <span>Summary</span>
                    <div class="card">
                        <div class="d-flex justify-content-between p-3">
                            <div class="d-flex flex-column">
                                <span>Customised Diet + Food Delivery Subscription</span>
                            </div>
                            <div class="mt-1">
                                <sup class="super-price"> ₹<?php echo $_SESSION['price']; ?></sup>
                                <span class="super-month">/ <?php if ($_SESSION['days'] === "4w") { echo "4 weeks"; }else if ($_SESSION['days'] === "2w") {echo "2 weeks";} else {echo "3 days";} ?></span>
                            </div>
                        </div>
                        <hr class="mt-0 line">
                        <div class="p-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>SGST</span>
                                <span>₹<?php echo number_format($sgst, 2); ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>CGST</span>
                                <span>₹<?php echo number_format($cgst, 2); ?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Discount</span>
                                <span>-₹<?php echo number_format($fweek, 2);?></span>
                            </div>
                        </div>
                        <hr class="mt-0 line">
                        <div class="p-3 d-flex justify-content-between">
                            <div class="d-flex flex-column">
                                <span>Total Payable Amount</span>
                            </div>
                            <span>₹<?php echo number_format($total_amount, 2); ?></span>
                        </div>
                        <div class="p-3">
                            <button class="btn btn-primary btn-block free-button" id='okay'>Subscribe</button> 
                            <div class="text-center">
                                <a href="#">Have a promo code?</a>
                            </div>
                        </div>
                        <div id="success-message" class="text-center success-message mb-3"></div>

                        <div id="error-message" class="text-center error-message mb-3"></div>
                    </div>

                    <div id="otp-card" class="card mt-3">
                        <div class="card-body">
                            <form action="resources/php/billing.php" method="post">
                                <div class="form-group mb-3">
                                    <input type="text" class="form-control" id="otp" name="otp" placeholder="Enter OTP">
                                </div>

                                <div id="timer" class="text-center timer mb-3"></div>

                                <div class="text-center d-flex justify-content-center">
                                    <input type="submit" class="btn btn-primary" id="submit-btn" value="Verify OTP">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/respond.js@1.4.2/dest/respond.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/html5shiv@3.7.3/dist/html5shiv.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/selectivizr@1.0.3/selectivizr.min.js"></script>
        <script src="resources/js/script3.js"></script>
        <script src="vendors/js/jquery.waypoints.min.js"></script>

    </body>


</html>