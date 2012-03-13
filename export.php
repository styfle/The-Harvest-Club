<?php 

	require_once('include/Database.inc.php');
	require_once('include/auth.inc.php');
	
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"export.csv\"");	
	header("Content-Transfer-Encoding: binary");
	header("Pragma: no-cache");
	header("Cache-Control: no-cache, must-revalidate");
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
	date_default_timezone_set("America/Los_Angeles");
	
	$table = $_REQUEST['table'];
	$arrayID = $_REQUEST['arrayID'];
	$ids = join(',',$arrayID);
	
	function forbidden() {
		return "You do not have sufficient privileges!";
	}
 
	if (!isLoggedIn(false)) { // if we're not logged in, tell user
		exit('Unauthorized. Please login to complete your request.');
	}

	if (isExpired()) { // if session expired, tell user
		exit('Session expired. Please login to complete your request.');
	}

	updateLastReq(); // ajax req means user is active

	// try to get current user permissions
	$r = $db->q("SELECT p.*
			FROM volunteers v
			LEFT JOIN privileges p
			ON v.privilege_id = p.id
			WHERE v.id=$_SESSION[id]"
	);

	$priv_error = "An error occurred while checking your privileges.\nI cannot allow you to proceed.";
	if (!$r->isValid())
		die($priv_error);

	// global containing all this user's privileges
	$PRIV = $r->buildArray();
	$PRIV = array_key_exists(0, $PRIV) ? $PRIV[0] : null;

	if ($PRIV == null)
		die($priv_error);
	$filename = "export";
	$my_t=getdate(date("U"));
	//print("$my_t[weekday], $my_t[month] $my_t[mday], $my_t[year]");
 
		
	switch ($table)
	{
		case 1: //volunteer
			if (!$PRIV['exp_volunteer'])
				die(forbidden());
			$filename = "volunteers";		
			$res = mysql_query("SELECT first_name as 'First Name',
									   middle_name as 'Middle Name',
									   last_name as 'Last Name',
									   email as Email,
									   phone as Phone,
									   street as Street, 
									   city as City,
									   state as State,
									   zip as Zip,
									   signed_up as 'Signed Up',
									   notes as Notes
 							    FROM volunteers WHERE id IN($ids) ");
		break;
		
		case 2: // grower
			if (!$PRIV['exp_grower'])
				die(forbidden());
			$filename = "growers";
			$res = mysql_query("SELECT 	g.first_name AS First,
										g.middle_name AS Middle,
										g.last_name AS Last,
										g.phone AS Phone,
										g.email AS Email,
										g.preferred AS Preferred,
										g.street AS Street,
										g.city AS City,
										g.state AS state,
										g.zip AS Zip,
										g.tools AS Tools,
										s.name AS Source,
										g.notes AS Notes,
										IF(g.pending=1,'YES','NO') AS Pending,
										pt.name AS 'Property Type',
										pr.name AS 'Property Relationship'
								FROM	growers g	LEFT JOIN property_types pt ON g.property_type_id = pt.id
													LEFT JOIN property_relationships pr ON g.property_relationship_id = pr.id
													LEFT JOIN sources s ON g.source_id = s.id
								WHERE	g.id IN($ids)");										
		break;		
		case 3: // tree
			if (!$PRIV['exp_grower'])
				die(forbidden());
			$filename = "growers_trees";
			$res = mysql_query("SELECT 	g.first_name AS First,
										g.middle_name AS Middle,
										g.last_name AS Last,
										g.phone AS Phone,
										g.email AS Email,
										g.preferred AS Preferred,
										g.street AS Street,
										g.city AS City,
										g.state AS state,
										g.zip AS Zip,
										g.tools AS Tools,
										s.name AS Source,
										g.notes AS Notes,
										IF(g.pending=1,'YES','NO') AS Pending,
										pt.name AS 'Property Type',
										pr.name AS 'Property Relationship',
										tt.name AS 'Tree type',
										gt.varietal AS Varietal,
										gt.number AS Number,										
										IF(gt.chemicaled is null,'',(IF((gt.chemicaled=0),'No','Yes'))) AS Chemicaled,										
										th.name AS Height,
										(SELECT group_concat(m.name)
										FROM	month_harvests mh, months m
										WHERE mh.tree_id = gt.id AND mh.month_id = m.id) 'Harvest Months'										
								FROM	growers g 	LEFT JOIN sources s ON g.source_id = s.id
													LEFT JOIN property_types pt ON g.property_type_id = pt.id
													LEFT JOIN property_relationships pr ON g.property_relationship_id = pr.id
													LEFT JOIN grower_trees gt ON g.id = gt.grower_id 
													LEFT JOIN tree_types tt ON gt.tree_type = tt.id
													LEFT JOIN tree_heights th ON gt.avgHeight_id = th.id																							
								WHERE	g.id IN($ids)");
								break;		
		case 4: // distribution
			if (!$PRIV['exp_distrib'])
				die(forbidden());
			$filename = "distribs";
/*
			$res = mysql_query("SELECT name as 'Agency Name',
									   street as 'Street Address',
									   city as City,
									   state as State,
									   zip as 'Zip Code',
									   contact as 'Agency Contact',
									   phone as Phone,
									   contact2 as 'Secondary Contact',
									   phone2 as 'Secondary Phone',
									   email as Email,
									   notes as Notes,
									   (	SELECT group_concat(d.name)
										FROM	distribution_hours dh, days d
										WHERE dh.distribution_id = dis.id AND dh.day_id = d.id) Days
								FROM distributions dis WHERE id IN($ids) ");
*/
			$res = mysql_query("SELECT name as 'Agency Name',
									   street as 'Street Address',
									   city as City,
									   state as State,
									   zip as 'Zip Code',
									   contact as 'Agency Contact',
									   phone as Phone,
									   contact2 as 'Secondary Contact',
									   phone2 as 'Secondary Phone',
									   email as Email,
									   notes as Notes
								FROM distributions dis WHERE id IN($ids) ");
		break;
				
		case 6: // donation
			if (!$PRIV['exp_donor'])
					die(forbidden());
			$filename = "donors";
			$res = mysql_query("SELECT donation as Donation,
									   donor as Donor,
									   value as Value,
									   date as Date
								FROM donations WHERE id IN($ids) ");
		break;
		case 7: //volunteer with hours
			if (!$PRIV['exp_volunteer'])
				die(forbidden());
			$filename = "volunteers_hours";	
			
			// foreach ($arrayID as $id){			
				// $event_hours = 0;
				// $surplus_hours = 0;
				
				// $q = mysql_query("SELECT SUM(ve2.hour) AS event_hours FROM volunteer_events ve2 WHERE ve2.volunteer_id=$id");
				// if ($q !== false) {
					// while($result=mysql_fetch_array($q)){ 
						// $event_hours = $result['event_hours']; 
					// }
				// }			
				
				// $q = mysql_query("SELECT v1.surplus_hours FROM volunteers v1 WHERE v1.id=$id");
				
				// if ($q !== false) {
					// while($result=mysql_fetch_array($q)){ 
						// $surplus_hours = $result['surplus_hours']; 
					// }				
				// }
				// $total = $surplus_hours + $event_hours;				
				// printf( $total);
				// $sql = "UPDATE volunteers SET total_hours = $total
				// WHERE id=$id";
				// $r = $db->q($sql);	
				// if ($r == false) {				
					// echo mysql_error();
					// die;    
				// }
			// } 
				
			
			
			$res = mysql_query("SELECT v.first_name as 'First Name',
									   v.middle_name as 'Middle Name',
									   v.last_name as 'Last Name',
									   v.email as Email,
									   v.phone as Phone,
									   v.street as Street, 
									   v.city as City,
									   v.state as State,
									   v.zip as Zip,
									   v.signed_up as 'Signed Up',
									   v.notes as Notes,
									   (v.surplus_hours +  IF(temp.hour is null,0,temp.hour)) as Hours
 							    FROM volunteers v LEFT JOIN (	SELECT v2.id AS id, SUM(ve.hour ) AS hour
																FROM volunteers v2, volunteer_events ve WHERE v2.id = ve.volunteer_id 
																GROUP BY v2.id
															) 	temp ON temp.id = v.id							
								WHERE v.id IN($ids);");
		break;
	}		
	
	header("Content-Disposition: attachment; filename=\"$filename($my_t[month]-$my_t[mday]-$my_t[year]).csv\"");
	
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