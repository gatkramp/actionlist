<?php
 // Includes Login Script
include('scripts.php');
dbconn();
session_start();

function idtouser($id){
	$res = mysql_fetch_assoc(mysql_query('select alias from login where id='.$id));
	return $res['alias'];	
}

function notifs($id){
	global $_GET;
	if ($id == 1){
		return '<div class="alert alert-success" role="alert" align="center">
        <strong>Success!</strong> You have successfully added a new project.
      </div>';}
	if ($id == 2){
		return '<div class="alert alert-danger" role="alert" align="center">
        <strong>Oh snap!</strong> Are you sure you want to delete this project?
		<a href="index.php"><button type="button" class="btn btn-sm btn-success">No</button></a>
		<a href="index.php?delpc='.$_GET['delp'].'"><button type="button" class="btn btn-sm btn-danger">YES</button></a>
      </div>';}
	 if ($id == 3){
		return '<div class="alert alert-danger" role="alert" align="center">
        <strong>Oh snap!</strong> Are you sure you want to delete this finding?
		<a href="index.php"><button type="button" class="btn btn-sm btn-success">No</button></a>
		<a href="index.php?delfc='.$_GET['delf'].'"><button type="button" class="btn btn-sm btn-danger">YES</button></a>
      </div>';}
	 if ($id == 4){
		return '<div class="alert alert-danger" role="alert" align="center">
        <strong>Oh snap!</strong> Are you sure you want to delete this task?
		<a href="index.php"><button type="button" class="btn btn-sm btn-success">No</button></a>
		<a href="index.php?deltc='.$_GET['delt'].'"><button type="button" class="btn btn-sm btn-danger">YES</button></a>
      </div>';}
	  
	
	
}

function colors($color_prio)
{
    if ($color_prio == 2)
    {
        return '<img src="pic/high.jpg">';
    } 
    else if ($color_prio == 1)
    {
        return '<img src="pic/med.jpg">';
    }
    else 
    {
        return '<img src="pic/low.jpg">';
    }

}

//========================================
//Startup procedures

if (isset($_GET['show']))
	mysql_query('update project set hidden=0 where id = '.$_GET['show']);
	
if (isset($_GET['hide']))
	mysql_query('update project set hidden=1 where id = '.$_GET['hide']);

if (isset($_GET['delpc']))
	mysql_query('delete from project where id='.$_GET['delpc']);

if (isset($_GET['deltc']))
	mysql_query('delete from task where id='.$_GET['deltc']);
	
if (isset($_GET['delfc']))
	mysql_query('update task set findings="None" where id='.$_GET['delfc']);
	
if (isset($_GET['dn'])){
	mysql_query('update task set comp='.time().' where id='.$_GET['dn']);
	header("location: edit.php?fnde=".$_GET['dn']);
}

if (isset($_GET['prioh'])){
	mysql_query('update task set color_prio=2 where id='.$_GET['prioh']);
}

if (isset($_GET['priom'])){
	mysql_query('update task set color_prio=1 where id='.$_GET['priom']);
}

if (isset($_GET['priol'])){
	mysql_query('update task set color_prio=0 where id='.$_GET['priol']);
}

if (isset($_GET['up']))
	{
		$a = mysql_query('select prio,max(comp) as comp from project inner join task on project.id=task.project where prio < '.$_GET['up'].' group by project.id order by comp desc,prio desc limit 1');
		$a = mysql_fetch_assoc($a);
		mysql_query('update project set prio=-1 where prio = '.$_GET['up']);
		mysql_query('update project set prio='.$_GET['up'].' where prio = '.$a['prio']);
		mysql_query('update project set prio='.$a['prio'].' where prio = -1');
	}
if (isset($_GET['down']))
	{
		$a = mysql_query('select prio,max(comp) as comp from project inner join task on project.id=task.project where prio > '.$_GET['down'].' group by project.id order by comp desc,prio limit 1');
		$a = mysql_fetch_assoc($a);
		mysql_query('update project set prio=-1 where prio = '.$_GET['down']);
		mysql_query('update project set prio='.$_GET['down'].' where prio = '.$a['prio']);
		mysql_query('update project set prio='.$a['prio'].' where prio = -1');	
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
	<meta http-equiv="refresh" content="600" >
    <link rel="icon" href="../../favicon.ico">

    <title>Action List</title>

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
            <li class="active"><a href="index.php">Home</a></li>
            <li><a href="add.php">Add Project</a></li>
            <li><a href="kanboard.php">Kanboard</a></li>
            <li><a href="mindmap.php">Mindmap</a></li>
			<li><a href="logout.php">Logout</a></li>

          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container theme-showcase" role="main">

      ';
	  
	if (isset($_GET['n']))
		$html .= notifs($_GET['n']);
      

	
	  
	$projectlist = mysql_query('select project.id,project.description,project.hidden,prio,max(comp) as comp,count(task.project) as cnt from project inner join task on project.id= task.project group by project.id order by comp desc,prio');
	while ($project = mysql_fetch_assoc($projectlist))
	{
		$id = 0;	
		$html .= '  <div class="row"> ';
		$res = mysql_fetch_assoc(mysql_query('select count(*) as cnt from task where project='.$project['id'].' and comp=2000000000'));  
	   	
		if ($res['cnt'] == 0 )
			$html .= '<div class="panel panel-default">'; else
			$html .= '<div class="panel panel-primary">';
			
		$html .= '<div class="panel-heading">
			<h3 class="panel-title">';
		if ($project['hidden'] == 1){
		$html .= '<a href="index.php?show='.$project['id'].'">
				<button type="button" class="btn btn-xs btn-default">
					<span class="glyphicon glyphicon-eye-open"></span></button></a> ';
		} else {
		$html .= '<a href="index.php?hide='.$project['id'].'">
				<button type="button" class="btn btn-xs btn-default">
					<span class="glyphicon glyphicon-eye-close"></span></button></a>
			<a href="index.php?up='.$project['prio'].'">
				<button type="button" class="btn btn-xs btn-default">Up</button></a>
			<a href="index.php?down='.$project['prio'].'">
				<button type="button" class="btn btn-xs btn-default">Down</button></a>
			<a href="edit.php?prje='.$project['id'].'">
				<button type="button" class="btn btn-xs btn-warning">Edit</button></a>
			<a href="index.php?delp='.$project['id'].'&n=2">
				<button type="button" class="btn btn-xs btn-danger">Delete</button></a> 
			<a href="add.php?tsk='.$project['id'].'">
				<button type="button" class="btn btn-xs btn-info">Add Task</button></a> ';
		}
		
		$done = mysql_fetch_assoc(mysql_query('select count(*) as cnt from task where project = '.$project['id'].' and comp < 2000000000'));
		$html .= $project['description'].' ('.round(($done['cnt']/$project['cnt'])*100,0).'%)</h3></div>';
				
		if ($project['hidden'] == 0){	   
		$html .= '<table class="table table-striped" >
				<thead>
				  	<tr>
						<th>#</th>
						<th>Description</th>
						<th>Priority</th>
						<th>Action</th>
				  	</tr>
				</thead><tbody>';
		$tasklist = mysql_query('select * from task where project = '.$project['id'].' order by comp desc');
		while ($task = mysql_fetch_assoc($tasklist))
		{
			$id = $id + 1;
                        if ($task['comp'] != 2000000000)
				$html .= '<tr class="info">';
                        else
                            $html .= '<tr>';
			$html .= '<td width = "25px">'.$id.'</td>
						<td ><a href="edit.php?tske='.$task['id'].'"><button type="button" class="btn btn-xs btn-warning">Edit</button></a> ';
						
			if (($task['findings'] == 'None'))			
				$html .= '<a href="edit.php?fnde='.$task['id'].'"><button type="button" class="btn btn-xs btn-info">Description</button></a> ';
				
			  $html .= nl2br($task['description']);
			 if ($task['comp'] < 2000000000)
			 	$html .= ' <span class="label label-success">Completed</span>';			
						
			if (($task['findings'] != 'None')){
				$task['findings'] = nl2br($task['findings']);
				$html .= '<div class="well well-sm" style="margin-bottom:0px"><a href="edit.php?fnde='.$task['id'].'"><button type="button" class="btn btn-xs btn-warning">Edit</button></a> <a href="index.php?delf='.$task['id'].'&n=3"><button type="button" class="btn btn-xs btn-danger">Delete</button></a> '.$task['findings'].'</div>';
			}
				
			$html .= '</td>
					<td width = "130px">';
                        if ($task['comp'] == 2000000000)
                        {
				$html .= colors($task['color_prio']).' | 
                                            <a href="index.php?prioh='.$task['id'].'"><img src="pic/high.jpg"></a>
                                            <a href="index.php?priom='.$task['id'].'"><img src="pic/med.jpg"></a>
                                            <a href="index.php?priol='.$task['id'].'"><img src="pic/low.jpg"></a>';
                        }
                     
                                            
                                $html .= ' </td>
					<td width = "150px">
					<a href="index.php?dn='.$task['id'].'">';
			if ($task['comp'] == 2000000000)
				$html .= '<button type="button" class="btn btn-xs btn-success">Done</button></a> ';
			$html .= '<a href="index.php?delt='.$task['id'].'&n=4"><button type="button" class="btn btn-xs btn-danger">Delete</button></a>
					</td>
						</tr>
					';
		}
		$html .= '</tbody>
				 </table>';}
		
			
				   
		$html .= '</div>
				
			  </div>';
	}
    
		
	
	echo $html.'<div align="center">Loaded: '.date(' h:i:s A').'</div>';
} else {
	
	header("location: login.php");
}
?>
