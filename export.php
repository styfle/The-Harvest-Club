<?php 

	require_once('include/Database.inc.php');

	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"test.csv\"");
	header("Content-Transfer-Encoding: binary");
	header("Pragma: no-cache");
	header("Cache-Control: no-cache, must-revalidate");
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

	$table = $_REQUEST['table'];
	$arrayID = $_REQUEST['arrayID'];
	$ids = join(',',$arrayID);
	
//	$fp = fopen("test.csv", "w");

	switch ($table)
	{
		case 1: //volunteer
			$res = mysql_query("SELECT first_name, middle_name, last_name, email, phone, street, city, state, zip, signed_up, notes  FROM volunteers WHERE id IN($ids) ");
		break;
		
		case 2: // grower
		break;
		
		case 4: // distribution
//			$res = 
		break;
				
		case 6: // donation
		break;
	}		

	// fetch a row and write the column names out to the file
	$row = mysql_fetch_assoc($res);
	$line = "";
	$comma = "";
	foreach($row as $name => $value) {
		$line .= $comma . '"' . str_replace('"', '""', $name) . '"';
		$comma = ",";
	}
	$line .= "\n";
//	fputs($fp, $line);
	echo ($line);

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
//		fputs($fp, $line);
		echo ($line);
	}

//	fclose($fp);	
 

?>