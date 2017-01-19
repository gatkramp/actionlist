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
            <li class="active"><a href="add.php">Add Project</a></li>
            <li><a href="kanboard.php">Kanboard</a></li>
			<li><a href="logout.php">Logout</a></li>

          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container theme-showcase" role="main">';
	
	if (isset($_GET['tsk'])){
	$html .= '<div class="jumbotron" align="center">
        <h1>New Task</h1>';
	$ref = mysql_fetch_assoc(mysql_query('select * from project where id='.$_GET['tsk']));
	$html .= '<h4>For: '.$ref['description'].'</h4>';
    $html .= '<form action="add.php" method="post"><br>
			<h4>Please add a description for the task:</h4>
			<textarea name="description" cols="100" rows="10"></textarea><br>
			<br><h4>Who will be responsible for the task?</h4>
			<select name="userid">
				<option value="1">Frik van der Merwe</option>
				<option value="2">Josh Letlhoo</option>
			</select>
			<input type="hidden" name="project" value="'.$_GET['tsk'].'">
			<br><h4>Click on "Submit" when done.</h4><input type="submit"></input>
		</form>
		
      </div>';
		
	} else {
	
	$html .= '<div class="jumbotron" align="center">
        <h1>New Project</h1>
        <form action="add.php" method="post"><h4>Please type a descriptive name for the project:</h4><br>
			<input size="80" name="prjname" type="text"></input>
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
