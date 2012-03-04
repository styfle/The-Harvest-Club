<?php 

	require_once('include/Database.inc.php');

	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"data.csv\"");
	// $data="col1, col2,col3, \n";
	// $data .= "data1, data2, data3";
	// echo $data; 
	// $ids = $_REQUEST['ids'];
	// echo($ids);
	$sql = "SELECT v.id,
                   v.first_name,
                   v.last_name
              FROM volunteers v
             WHERE v.id = 1";
	$r = mysql_query($sql);	
	while ($row = mysql_fetch_assoc($r)) {   
		
		echo $row['first_name'];
	}

  

?>