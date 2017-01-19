<?php
 // Includes Login Script
include('scripts.php');
dbconn();
session_start();

if (isset($_POST['prjec'])){
	mysql_query('update project set description="'.$_POST['prjec'].'" where id ='.$_POST['prjeid']);
	header("location: index.php");
}

if (isset($_POST['tskec'])){
	mysql_query('update task set description="'.$_POST['tskec'].'", comp=2000000000 where id ='.$_POST['tskeid']);
	header("location: index.php");
}

if (isset($_POST['fndec'])){
	mysql_query('update task set findings="'.$_POST['fndec'].'" where id ='.$_POST['fndeid']);
	header("location: index.php");
}

if(isset($_SESSION['login_user'])){
	
	
	$html = '';
	$html .= '<!DOCTYPE html>
	<html lang="en">
	<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Theme Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap theme -->
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="theme.css" rel="stylesheet">

 
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body role="document">

    <!-- Fixed navbar -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Action List</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li ><a href="index.php">Home</a></li>
            <li ><a href="add.php">Add Project</a></li>
            <li class="active"><a href="kanboard.php">Kanboard</a></li>
			<li><a href="logout.php">Logout</a></li>

          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container theme-showcase" role="main">';
	
	if (isset($_GET['prje'])){
		$res = mysql_fetch_assoc(mysql_query('select * from project where id = '.$_GET['prje']));
	$html .= '<div class="jumbotron" align="center">
        <h1>Edit Project</h1>
        <form action="edit.php" method="post"><h4>Please type a descriptive name for the project:</h4><br>
			<input size="80" name="prjec" type="text" value="'.$res['description'].'"></input>
			<input name="prjeid" type="hidden" value="'.$_GET['prje'].'"></input>
			<input type="submit"></input>
		</form>
		<h4>Click on "Submit" when done.</h4>
      </div>';
	}
	if (isset($_GET['tske'])){
		$res = mysql_fetch_assoc(mysql_query('select * from task where id = '.$_GET['tske']));
	$html .= '<div class="jumbotron" align="center">
        <h1>Edit Task</h1>
        <form action="edit.php" method="post"><h4>Please type a description for the task:</h4><br>
			<textarea name="tskec" cols="120" rows="20">'.$res['description'].'</textarea><br>
			<input name="tskeid" type="hidden" value="'.$_GET['tske'].'"></input>
			<input type="submit"></input>
		</form>
		<h4>Click on "Submit" when done.</h4>
      </div>';
	}
	if (isset($_GET['fnde'])){
		$res = mysql_fetch_assoc(mysql_query('select * from task where id = '.$_GET['fnde']));
	$html .= '<div class="jumbotron" align="center">
        <h1>Edit Findings</h1>
        <form action="edit.php" method="post"><h4>Please type the findings for the task:</h4><br>
			<textarea name="fndec" cols="120" rows="20">'.$res['findings'].'</textarea><br>
			<input name="fndeid" type="hidden" value="'.$_GET['fnde'].'"></input>
			<input type="submit"></input>
		</form>
		<h4>Click on "Submit" when done.</h4>
      </div>';
	}

	  

	
    
		
	
	echo $html;
} else {
	
	header("location: login.php");
}
?>
