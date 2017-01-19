<?php
 // Includes Login Script
include('scripts.php');
dbconn();
session_start();

function idtouser($id){
	$res = mysql_fetch_assoc(mysql_query('select alias from login where id='.$id));
	return $res['alias'];	
}

function proIdtoDesc($id){
    $res = mysql_fetch_assoc(mysql_query('select description from project where id='.$id));
    return $res['description'];
}

if (isset($_POST['project'])){
	mysql_query('insert into task (project,description,user_id,findings) values ('.$_POST['project'].',"'.$_POST['description'].'",'.$_POST['userid'].',"None")');
	header("location: index.php");
}

if (isset($_GET['deltc']))
	mysql_query('delete from task where id='.$_GET['deltc']);

if (isset($_GET['dn'])){
	mysql_query('update task set comp='.time().' where id='.$_GET['dn']);
	
}

if (isset($_GET['busy'])){
	mysql_query('update task set busy=1, comp=2000000000 where id='.$_GET['busy']);

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
    <script src="js/tooltip.js"></script>
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

    <div class="container theme-showcase" role="main">
    <table class="table table-bordered">
        <tr><th class="col-lg-4">Open Projects</th>
            <th class="col-lg-4">In Progress</th>
            <th class="col-lg-4">Finished Projects</th>
        </tr>';
        $html .='<tr><td><table class="table">';
        $tsk = mysql_query('select * from task where comp = 2000000000 and busy = 0');
        while($line = mysql_fetch_assoc($tsk)){
            $proj = proIdtoDesc($line['project']);
            if (strlen($proj) > 1)
                $html .= '<tr><td><span class="label label-danger" data-toggle="tooltip" data-placement="top" title="'.$line['findings'].'">'.$line['description'].'</span><br><span class="label label-primary">'.$proj.'</span><br>'
                    . '<a href="kanboard.php?deltc='.$line['id'].'"><button class="btn btn-xs btn-danger">Delete</button></a> '
                    . '<a href="edit.php?tske='.$line['id'].'"><button class="btn btn-xs btn-warning">Edit</button></a> '
                    . '<a href="edit.php?fnde='.$line['id'].'"><button class="btn btn-xs btn-info">Description</button></a> '
                    . '<a href="kanboard.php?busy='.$line['id'].'"><button class="btn btn-xs btn-primary">Start</button></a> '
                    . '<a href="kanboard.php?dn='.$line['id'].'"><button class="btn btn-xs btn-success">Complete</button></a></td></tr>';
        }  
            $html .='</table></td>';
        
        $html .='<td><table class="table">';
        $tsk = mysql_query('select * from task where comp = 2000000000 and busy = 1');
        while($line = mysql_fetch_assoc($tsk)){
            $proj = proIdtoDesc($line['project']);
            if (strlen($proj) > 1)
            $html .= '<tr><td><span class="label label-warning">'.$line['description'].'</span><br><span class="label label-primary">'.$proj.'</span><br>'
                    . '<a href="kanboard.php?deltc='.$line['id'].'"><button class="btn btn-xs btn-danger">Delete</button></a> '
                    . '<a href="edit.php?tske='.$line['id'].'"><button class="btn btn-xs btn-warning">Edit</button></a> '
                    . '<a href="edit.php?fnde='.$line['id'].'"><button class="btn btn-xs btn-info">Description</button></a> '
                    . '<a href="kanboard.php?dn='.$line['id'].'"><button class="btn btn-xs btn-success">Complete</button></a></td></tr>';
        }  
            $html .='</table></td>';
        

        $html .='<td><table class="table">';
        $tsk = mysql_query('select * from task where comp <> 2000000000');
        while($line = mysql_fetch_assoc($tsk)){
            $proj = proIdtoDesc($line['project']);
            if (strlen($proj) > 1)
            $html .= '<tr><td><span class="label label-success">'.$line['description'].'</span><br><span class="label label-primary">'.$proj.'</span><br>'
                    . '<a href="kanboard.php?deltc='.$line['id'].'"><button class="btn btn-xs btn-danger">Delete</button></a> '
                    . '<a href="edit.php?tske='.$line['id'].'"><button class="btn btn-xs btn-warning">Edit</button></a> '
                    . '<a href="edit.php?fnde='.$line['id'].'"><button class="btn btn-xs btn-info">Description</button></a> '
                    . '<a href="kanboard.php?busy='.$line['id'].'"><button class="btn btn-xs btn-primary">Not Finished</button></a> ';

        }  
            $html .='</table></td>
                        
        </tr>
    </table>
	
      </div>';
	

	  

	
    
		
	
	echo $html;
} else {
	
	header("location: login.php");
}
?>
