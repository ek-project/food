<?php

session_start();

// Check if the username is set in the session
if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    $username = "Guest";
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Foodelight is a premium fodd delivery service with the mission to bring healthy foods as much as possible">

        <link rel="stylesheet" href = "vendors/css/grid.css">
        <link rel="stylesheet" href = "vendors/css/normalize.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
        <link rel="stylesheet" href = "resources/css/style2.css">
        <link rel="stylesheet" href = "resources/css/queries2.css">
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
                    <div class="row">
                        <img src="resources/img/orange.svg" alt="Foodelight logo" class="logo">
                        <ul class="main-nav js--main-nav">
                            <li><a href="#">Pricing</a></li>
                            <li><a href="acc.php">My Account</a></li>
                            <li><a href="plan.php">My Plans</a></li>
                            <li><a href="logout.php">Logout</a></li>
                            <li><a href="#"><img id="profileImage" width="48" height="48" src="https://img.icons8.com/fluency/48/user-male-circle--v1.png" alt="Profile Photo" title="<?php echo $_SESSION['username']; ?>"/></a></li>
                        </ul>
                        <a class="mobile-nav-icon js--nav-icon"><ion-icon name="menu-sharp"></ion-icon></a>
                    </div>
                </nav>
            </header>
        </div>

        <section>
            <div class="row">
                <h2>Start your subscription</h2>
            </div>
            <div class="row">
                <form method="post" action="resources/php/index2.php" class="contact-form">
                    <div class="row">
                        <div class="col span-1-of-3">
                            <label for="goal">What's your goal?</label>
                        </div>
                        <div class="col span-2-of-3">
                            <select name="goal" id="goal" required>
                                <option value="wl">Weight Loss</option>
                                <option value="wm">Weight Maintenance</option>
                                <option value="gm">Gain Muscle</option>
                                <option value="he">Healthy eating</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col span-1-of-3">
                            <label for="gender">Gender</label>
                        </div>
                        <div class="col span-2-of-3">
                            <select name="gender" id="gender" required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col span-1-of-3">
                            <label for="days">How many days you want to subscribe?</label>
                        </div>
                        <div class="col span-2-of-3">
                            <select name="days" id="days" required>
                                <option value="3d">3 days</option>
                                <option value="2w">2 weeks</option>
                                <option value="4w">4 weeks</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col span-1-of-3">
                            <label for="meal">How many meals per day?</label>
                        </div>
                        <div class="col span-2-of-3">
                            <select name="meal" id="meal" required>
                                <option value="1m">1 meal</option>
                                <option value="2m">2 meals</option>
                                <option value="3m">3 meals</option>
                                <option value="4m">4 meals</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col span-1-of-3">
                            <label for="diet">Choose your diet!</label>
                        </div>
                        <div class="col span-2-of-3">
                            <select name="diet" id="diet" required>
                                <option value="keto" id="keto">Ketogenic Diet</option>
                                <option value="balanced" selected>Balanced Diet</option>
                                <option value="low">Low-carb Diet</option>
                                <option value="glu">Gluten-free Diet</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col span-1-of-3">
                            <label for="sty">Are you vegetarian or non vegetarian?</label>
                        </div>
                        <div class="col span-2-of-3">
                            <select name="sty" id="sty" required>
                                <option value="veg">Vegetarian</option>
                                <option value="nonveg">Non-Vegetarian</option>
                                <option value="egg">Eggetarian</option>
                            </select>
                        </div>
                    </div>

                    <div class="row choose">
                        <div class="col span-1-of-3">
                            <label for="choose" id="choose">Choose your for each day!</label>
                        </div>
                        <div class="col span-2-of-3">
                            <input type="checkbox" name="choose[]"  class="choose" value="Breakfast">Breakfast
                            <input type="checkbox" name="choose[]"  class="choose" value="Lunch">Lunch
                            <input type="checkbox" name="choose[]"  class="choose" value="Snacks">Snacks
                            <input type="checkbox" name="choose[]"  class="choose" value="Dinner">Dinner
                        </div>
                    </div>

                    <div class="row">
                        <div class="row span-2-of-2 sys">
                            <p>Note: These values are defualt selected </p>
                        </div>
                    </div>

                    <div id="price"></div>

                    <div id="success-message" class="success-message"></div>

                    <div id="error-message" class="error-message"></div>
    
                    <!-- Step 2 -->
                    <div class="row">
                        <div class="col span-1-of-3">
                            <label>&nbsp;</label>
                        </div>
                        <div class="col span-2-of-3">
                            <input type="submit" id="submit-btn" value="Subscribe it!">
                        </div>
                    </div>

                </form>
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
                        <li><a href="#"><ion-icon name="logo-facebook" class="facebook"></ion-icon></a></li>
                        <li><a href="#"><ion-icon name="logo-twitter" class="twitter"></ion-icon></a></li>
                        <li><a href="#"><ion-icon name="logo-linkedin" class="linkedin"></ion-icon></a></li>
                        <li><a href="#"><ion-icon name="logo-instagram" class="instagram"></ion-icon></a></li>
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
        <script src="https://cdn.jsdelivr.net/npm/respond.js@1.4.2/dest/respond.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/html5shiv@3.7.3/dist/html5shiv.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/selectivizr@1.0.3/selectivizr.min.js"></script>
        <script src="resources/js/script2.js"></script>
        <script src="vendors/js/jquery.waypoints.min.js"></script>

    </body>


</html>