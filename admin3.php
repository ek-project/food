<?php

session_start();

if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    $username = "Guest";
}

include "resources/php/config.php";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    throw new Exception("Connection failed: " . $conn->connect_error);
}

// Execute a SQL query to fetch all rows
$result = $conn->query("SELECT userid, goal, duration, meals, diet, type, mealtype, subscriptionid, transactionid, amount, datein FROM subscription ORDER BY datein DESC");

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="resources/css/admin2.css">

	<title>AdminHub</title>
    <link rel="icon" type="image/svg+xml" href="resources/favicons/favicon.svg">
    <link rel="icon" type="image/png" href="resources/favicons/favicon.png">

</head>
<body>


	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand">
			<i class='bx bxs-smile'></i>
			<span class="text">AdminHub</span>
		</a>
		<ul class="side-menu top">
			<li>
				<a href="admin.php">
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
				<a href="admin2.php">
					<i class='bx bxs-user' ></i>
					<span class="text">User Management</span>
				</a>
			</li>
			<li class="active">
				<a href="admin3.php">
					<i class='bx bxs-archive' ></i>
					<span class="text">Subscription Management</span>
				</a>
			</li>
			<li>
				<a href="admin4.php">
					<i class='bx bxs-doughnut-chart' ></i>
					<span class="text">Activity Logs</span>
				</a>
			</li>
		</ul>
		<ul class="side-menu">
			<li>
				<a href="#">
					<i class='bx bxs-cog' ></i>
					<span class="text">Settings</span>
				</a>
			</li>
			<li>
				<a href="logout2.php" class="logout">
					<i class='bx bxs-log-out-circle' ></i>
					<span class="text">Logout</span>
				</a>
			</li>
		</ul>
	</section>
	<!-- SIDEBAR -->



	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu' ></i>
			<a href="#" class="nav-link">Categories</a>
			<form action="#">
				<div class="form-input">
					<input type="search" name="search" id="search" placeholder="Search...">
					<button type="submit" class="search-btn"><i class='bx bx-search' ></i></button>
				</div>
			</form>
			<input type="checkbox" id="switch-mode" hidden>
			<label for="switch-mode" class="switch-mode"></label>
			<a href="#" class="notification">
				<i class='bx bxs-bell' ></i>
				<span class="num">8</span>
			</a>
			<a href="#" class="profile">
				<img id="profileImage" width="48" height="48" src="https://img.icons8.com/fluency/48/user-male-circle--v1.png" title="<?php echo $_SESSION['username']; ?>"/>
			</a>
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Subscription Management</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Subscription Management</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="#">Home</a>
						</li>
					</ul>
				</div>
				<a href="#" class="btn-download">
					<i class='bx bxs-cloud-download' ></i>
					<span class="text">Download PDF</span>
				</a>
			</div>


			<div class="table-data scrollable-table" id="myTable">
				<div class="order">
					<div class="head">
						<h3>Subscriptions</h3>
						<i class='bx bx-search' ></i>
						<i class='bx bx-filter' ></i>
					</div>
					<table>
						<thead>
							<tr>
								<th>UserID</th>
								<th>Goal</th>
								<th>Duration</th>
								<th>Meals</th>
								<th>Diet</th>
								<th>Type</th>
								<th>Mealtype</th>
								<th>SubscriptionId</th>
								<th>TransactionId</th>
								<th>Amount</th>
								<th>Date of order</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<?php
									if ($result->num_rows > 0) {
										while ($row = $result->fetch_assoc()) 
										{
								?>
								<td>
									<p><?php echo $row['userid']; ?></p>
								</td>
								<td><?php if($row['goal']=='wl') {echo "Weight Loss"; } else if ($row['goal']=='wm') {echo "Weight Maintenance"; } else if ($row['goal']=='gm') {echo "Gain Muscle"; } else if ($row['goal']=='he') {echo "Healthy eating"; } else { echo " ";} ?></td>
								<td><?php if($row['duration']=='3d') {echo "3 days"; } else if ($row['duration']=='2w') {echo "2 weeks"; } else if ($row['duration']=='4w') {echo "4 weeks"; } else { echo " ";} ?></td>
								<td><?php if($row['meals']=='1m') {echo "1 meal"; } else if ($row['meals']=='2m') {echo "2 meals"; } else if ($row['meals']=='3m') {echo "3 meals"; } else if ($row['meals']=='4m') {echo "4 meals"; } else { echo " ";} ?></td>
								<td><?php if($row['diet']=='keto') {echo "Ketogenic Diet"; } else if ($row['diet']=='balanced') {echo "Balanced Diet"; } else if ($row['diet']=='low') {echo "Low-carb Diet"; } else if ($row['diet']=='glu') {echo "Gluten-free Diet"; } else { echo " ";} ?></td>
								<td><?php if($row['type']=='egg') {echo "Eggetarian"; } else if ($row['type']=='veg') {echo "Vegetarian"; } else if ($row['type']=='nonveg') {echo "Non-Vegetarian"; } else { echo " ";} ?></td>
								<td><?php echo $row['mealtype']; ?></td></td>
								<td><?php echo $row['subscriptionid']; ?></td>
								<td><?php echo $row['transactionid']; ?></td>
								<td>₹<?php echo $row['amount']; ?></td>
								<td><?php echo $row['datein']; ?></td>
							</tr>
							<?php 
									}	
								} else {
							?>
							<tr>
								<td colspan="11">No records found</td>
							</tr>
							<?php
								}
							?>
						</tbody>
					</table>
					<div id="no-records-found" style="display: none;">No records found</div>
				</div>
			</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
	<script src="resources/js/admin3.js"></script>
</body>
</html>