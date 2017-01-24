<?php
 // Includes Login Script
include('scripts.php');
dbconn();
session_start();

function idtouser($id){
	$res = mysql_fetch_assoc(mysql_query('select alias from login where id='.$id));
	return $res['alias'];	
}

function prettyTime($time){
    $days = floor($time/(24*60*60));
    $time = $time - $days*24*60*60;
    $hours = floor($time/(60*60));
    $time = $time - $hours*60*60;
    $min = floor($time/(60));
    $sec = $time - $min*60;
    return $days.'d '.$hours.'h '.$min.'m '.$sec.'s';
    //return $time;
}

function proIdtoDesc($id){
    $res = mysql_fetch_assoc(mysql_query('select description from project where id='.$id));
    return $res['description'];
}

if ((isset($_GET['id'])) and (isset($_GET['hours'])) and (isset($_GET['days'])) and (isset($_GET['min']))){
    $days = $_GET['days'];
    $hours = $_GET['hours'];
    $min = $_GET['min'];
    $days = $days + floor($days/5)*2;
    $isum = $min*60 + $hours*60*60 + $days*60*60*24 ; 
    $fu = time()+$isum;
    mysql_query('update task set fu='.$fu.' where id='.$_GET['id']);
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

if (isset($_GET['stop'])){
	mysql_query('update task set busy=0, comp=2000000000 where id='.$_GET['stop']);

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

    <title>Actionlist: Kanboard</title>

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

    <div class=" theme-showcase" role="main">
    <table class="table table-bordered">
        <tr><th class="col-lg-4">Open Projects</th>
            <th class="col-lg-4">In Progress</th>
            <th class="col-lg-4">Finished Projects</th>
        </tr>';
        $html .='<tr><td><table class="table">';
        $tsk = mysql_query('select task.id,task.findings,task.description,task.project,project.prio from task inner join project on task.project=project.id where comp = 2000000000 and busy = 0 order by project.prio');
        while($line = mysql_fetch_assoc($tsk)){
            $proj = proIdtoDesc($line['project']);
            if (strlen($proj) > 1)
                $html .= '<tr><td><span class="label label-danger" data-toggle="tooltip" data-placement="top" title="'.$line['findings'].'">'.$line['description'].'</span><br><span class="label label-primary">'.$proj.'</span><br>'
                    . '<a href="kanboard.php?deltc='.$line['id'].'"><button class="btn btn-xs btn-danger">Delete</button></a> '
                    . '<a href="edit.php?tske='.$line['id'].'"><button class="btn btn-xs btn-warning">Edit</button></a> '
                    . '<a href="edit.php?fnde='.$line['id'].'"><button class="btn btn-xs btn-info">Description</button></a> '
                    . '<a href="kanboard.php?busy='.$line['id'].'"><button class="btn btn-xs btn-primary">Start</button></a> '
                    . '<a href="kanboard.php?dn='.$line['id'].'"><button class="btn btn-xs btn-success">Complete</button></a>'
                    . '<a href="kanboard.php?up='.$line['prio'].'">
				<button type="button" class="btn btn-xs btn-default">Up</button></a>
			<a href="kanboard.php?down='.$line['prio'].'">
				<button type="button" class="btn btn-xs btn-default">Down</button></a></td></tr>';
        }  
            $html .='</table></td>';
        
        $html .='<td><table class="table">';
        $tsk = mysql_query('select task.id,task.findings,task.description,task.project,project.prio,task.fu from task inner join project on task.project=project.id where  comp = 2000000000 and busy = 1 order by task.fu,project.prio');
        while($line = mysql_fetch_assoc($tsk)){
            $proj = proIdtoDesc($line['project']);
            if (strlen($proj) > 1){
                $html .= '<tr><td>';
            if (($line['fu']-time()) > 0){
                $html .= '<span class="label label-default" data-toggle="tooltip" data-placement="top" title="'.$line['findings'].'">'.$line['description'].'</span><br>';
                $html .= '<span class="label label-default">'.$proj.'</span> ';
                $html .=  '<span class="label label-success">Waiting: '.  prettyTime($line['fu']-time()).'</span><br>';
                $html .= '<a href="kanboard.php?deltc='.$line['id'].'"><button class="btn btn-xs btn-default">Delete</button></a> '
                    . '<a href="edit.php?tske='.$line['id'].'"><button class="btn btn-xs btn-default">Edit</button></a> '
                    . '<a href="edit.php?fnde='.$line['id'].'"><button class="btn btn-xs btn-default">Description</button></a> '
                    . '<a href="kanboard.php?stop='.$line['id'].'"><button class="btn btn-xs btn-default">Stop</button></a> '
                    . '<a href="kanboard.php?dn='.$line['id'].'"><button class="btn btn-xs btn-default">Complete</button></a>'
                    . '<a href="add2.php?tsk='.$line['project'].'&id='.$line['id'].'"><button class="btn btn-xs btn-default">+</button></a> '
                    . '<a href="busy.php?task='.$line['id'].'"><button class="btn btn-xs btn-default">Follow-up</button></a> ';
            }
                
            else {
                $html .= '<span class="label label-warning" data-toggle="tooltip" data-placement="top" title="'.$line['findings'].'">'.$line['description'].'</span><br>';
                $html .= '<span class="label label-primary">'.$proj.'</span> ';
                $html .=  '<span class="label label-danger">Attention Needed</span><br>';
                $html .= '<a href="kanboard.php?deltc='.$line['id'].'"><button class="btn btn-xs btn-danger">Delete</button></a> '
                    . '<a href="edit.php?tske='.$line['id'].'"><button class="btn btn-xs btn-warning">Edit</button></a> '
                    . '<a href="edit.php?fnde='.$line['id'].'"><button class="btn btn-xs btn-info">Description</button></a> '
                    . '<a href="kanboard.php?stop='.$line['id'].'"><button class="btn btn-xs btn-primary">Stop</button></a> '
                    . '<a href="kanboard.php?dn='.$line['id'].'"><button class="btn btn-xs btn-success">Complete</button></a>'
                    . '<a href="add2.php?tsk='.$line['project'].'&id='.$line['id'].'"><button class="btn btn-xs btn-success">+</button></a> '
                    . '<a href="busy.php?task='.$line['id'].'"><button class="btn btn-xs btn-info">Follow-up</button></a> ';
            }
             
            
                
            
        }  }
            $html .='</table></td>';
        

        $html .='<td><table class="table">';
        $tsk = mysql_query('select task.id,task.findings,task.description,task.project,task.comp from task inner join project on task.project=project.id where comp <> 2000000000  and comp > '.(time()-60*60*24*7).' order by task.comp desc');
        while($line = mysql_fetch_assoc($tsk)){
            $proj = proIdtoDesc($line['project']);
            if (strlen($proj) > 1)
            $html .= '<tr><td><span class="label label-success" data-toggle="tooltip" data-placement="top" title="'.$line['findings'].'">'.$line['description'].'</span><br><span class="label label-primary">'.$proj.'</span>'
                    . '<br><span class="label label-info">Completed: '.date('Y-m-d h:i:s',$line['comp']+3600).'</span><br>'
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
