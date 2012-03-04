<?php 

	require_once('include/Database.inc.php');

	//header("Content-type: application/octet-stream");
	//header("Content-Disposition: attachment; filename=\"test.csv\"");

	$arrayID = $_REQUEST['arrayID'];
	$ids = join(',',$arrayID);
	
	$fp = fopen("test.csv", "w");

	$res = mysql_query("SELECT * FROM volunteers WHERE id IN($ids) ");

	// fetch a row and write the column names out to the file
	$row = mysql_fetch_assoc($res);
	$line = "";
	$comma = "";
	foreach($row as $name => $value) {
		$line .= $comma . '"' . str_replace('"', '""', $name) . '"';
		$comma = ",";
	}
	$line .= "\n";
	fputs($fp, $line);

	// remove the result pointer back to the start
	mysql_data_seek($res, 0);

	// and loop through the actual data
	while($row = mysql_fetch_assoc($res)) {
	   
		$line = "";
		$comma = "";
		foreach($row as $value) {
			$line .= $comma . '"' . str_replace('"', '""', $value) . '"';
			$comma = ",";
		}
		$line .= "\n";
		fputs($fp, $line);
	   
	}

	fclose($fp);	
 

?>