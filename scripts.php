<?php

function dbconn(){	

	$connection = @mysql_connect("localhost", "root", "");
	$db = mysql_select_db("action", $connection);

}

?>