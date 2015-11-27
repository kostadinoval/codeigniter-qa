<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo $title; ?></title>
<link rel="stylesheet" href="https://w1416464.users.ecs.westminster.ac.uk/CI_1/application/css/main.css">
<script src="https://w1416464.users.ecs.westminster.ac.uk/CI_1/application/js/jquery-2.1.3.min.js"></script>
<script src="https://w1416464.users.ecs.westminster.ac.uk/CI_1/application/js/main.js"></script>
</head>
<body>
<div id="wrapper">
<div id="header">
<div id="nav">
<ul id="top_links">
	<li><a href="https://w1416464.users.ecs.westminster.ac.uk/CI_1/index.php/home">Home</a></li>
	<li><a href="https://w1416464.users.ecs.westminster.ac.uk/CI_1/index.php/categories">Browse categories</a></li>
	<li><a href="https://w1416464.users.ecs.westminster.ac.uk/CI_1/index.php/questions/post_question">Post question</a></li>
	<li><a href="https://w1416464.users.ecs.westminster.ac.uk/CI_1/index.php/guest_search">Guest Search</a></li>
<?php
if(isset($logged_in) && $logged_in){
echo "<li><a href=\"https://w1416464.users.ecs.westminster.ac.uk/CI_1/index.php/profile/view/" . $user_id . "\">" . "Profile</a></li>";
echo "<li><a href=\"https://w1416464.users.ecs.westminster.ac.uk/CI_1/index.php/logout\">Logout</a></li>";
}
else{
echo "<li><a href=\"https://w1416464.users.ecs.westminster.ac.uk/CI_1/index.php/register\">Register</a></li>";
echo "<li><a href=\"https://w1416464.users.ecs.westminster.ac.uk/CI_1/index.php/login\">Login</a></li>";
}
?>
</ul>
</div>
<div id="search">
	<form id="header_form" action="https://w1416464.users.ecs.westminster.ac.uk/CI_1/index.php/search/term\" method="get">
	<input type="text" id="header_input" placeholder="search" />
	<input type="submit" name="submit" value="Search" />
	</form>
</div>
</div>
