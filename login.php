<?php
include('scripts.php');
	
session_start(); // Starting Session
$error=''; // Variable To Store Error Message

if (isset($_POST['submit'])) {
	if (empty($_POST['username']) || empty($_POST['password'])) {
	$error = "Username or Password is invalid";
	} else
	{
	// Define $username and $password
	$username=$_POST['username'];
	$password=$_POST['password'];
	// Establishing Connection with Server by passing server_name, user_id and password as a parameter
	dbconn();
	// To protect MySQL injection for Security purpose
	$username = stripslashes($username);
	$password = stripslashes($password);
	$username = mysql_real_escape_string($username);
	$password = mysql_real_escape_string($password);
	// Selecting Database
	// SQL query to fetch information of registerd users and finds user match.
	$query = mysql_query("select * from login where password='".$password."' AND username='".$username."'");
	$rows = mysql_num_rows($query);
	if ($rows == 1) {
	$_SESSION['login_user']=$username; // Initializing Session
	header("location: index.php"); // Redirecting To Other Page
	} else {
	$error = "Username or Password is invalid";
	}
	//mysql_close($connection); // Closing Connection
	}
}
else {
 $html = '<html>
<head>
<title>Action List: Login</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="main" align="center">
<h1>Action List Login Page</h1>
<div id="login" align="center">
<h2>Login Form</h2>
<form action="login.php" method="post">
<label>UserName :</label>
<input id="name" name="username" placeholder="username" type="text">
<label>Password :</label>
<input id="password" name="password" placeholder="**********" type="password">
<input name="submit" type="submit" value=" Login ">

</form>
</div>
</div>
</body>';	
echo $html;
}
?>