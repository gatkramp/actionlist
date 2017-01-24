<?php
 // Includes Login Script
include('scripts.php');
dbconn();
session_start();

function idtouser($id){
	$res = mysql_fetch_assoc(mysql_query('select alias from login where id='.$id));
	return $res['alias'];	
}

if (isset($_POST['project'])){
	mysql_query('insert into task (project,description,user_id,findings) values ('.$_POST['project'].',"'.$_POST['description'].'",'.$_POST['userid'].',"None")');
	header("location: index.php");
}


if(isset($_SESSION['login_user'])){
	
	if (isset($_POST['prjname']))
	{
		$a = mysql_fetch_assoc(mysql_query('select max(prio) as prio from project'));
		mysql_query('insert into project (description,prio) values ("'.$_POST['prjname'].'",'.($a['prio']+1).')');
		$a = mysql_fetch_assoc(mysql_query('select * from project where prio = "'.($a['prio']+1).'"'));
		mysql_query('insert into task (project,description,user_id,findings) values ('.$a['id'].',"Initial Task",1,"None")');	
		header("location: index.php?n=1");
	}
	
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
	
	
	
	$html .= '<div class="jumbotron" align="center">
        <h1>Busy Time</h1>
        <form action="kanboard.php" method="get"><h4>Please select the duration the project will out of your hands:</h4><br>
                        <input type="hidden" name="id" value="'.$_GET['task'].'">
                        Working Days:<br>
			<input size="5" name="days" type="text" value="0"></input><br><br>
                        Hours:<br>
			<input size="5" name="hours" type="text" value="0"></input><br><br>
                        Min:<br>
			<input size="5" name="min" type="text" value="0"></input><br><br>
			<input type="submit"></input>
		</form>
		<h4>Click on "Submit" when done.</h4>
      </div>';
	

	  

	
    
		
	
	echo $html;
} else {
	
	header("location: login.php");
}
?>
