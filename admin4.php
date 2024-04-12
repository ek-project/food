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
$result = $conn->query("SELECT * FROM activitylog ORDER BY datetime DESC");


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="resources/css/admin.css">

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
			<li>
				<a href="admin3.php">
					<i class='bx bxs-archive' ></i>
					<span class="text">Subscription Management</span>
				</a>
			</li>
			<li class= "active">
				<a href="#">
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
					<input type="search" name="search" id="search" placeholder="Search..." >
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
					<h1>Activity Logs</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Activity Logs</a>
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


			<div class="table-data" id="myTable">
				<div class="order">
					<div class="head">
						<h3>Activity Logs</h3>
						<i class='bx bx-search' ></i>
						<i class='bx bx-filter' ></i>
					</div>
					<table>
						<thead>
							<tr>
								<th>UserId</th>
								<th>Email</th>
								<th>Event</th>
								<th>Date</th>
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
								<td><?php echo $row['email']; ?></td>
								<td><?php echo $row['event']; ?></td>
                                <td><?php echo $row['datetime']; ?></td>
							</tr>
							<?php 
									}	
								} else {
							?>
							<tr>
								<td colspan="4">No records found</td>
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
	<script src="resources/js/admin.js"></script>
</body>
</html>