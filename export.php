<?php 

	require_once('include/Database.inc.php');
	require_once('include/auth.inc.php');
	
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"test.csv\"");
	header("Content-Transfer-Encoding: binary");
	header("Pragma: no-cache");
	header("Cache-Control: no-cache, must-revalidate");
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

	$table = $_REQUEST['table'];
	$arrayID = $_REQUEST['arrayID'];
	$ids = join(',',$arrayID);

	if (!isLoggedIn(false)) { // if we're not logged in, tell user
		echo json_encode(array(
			'status'=>401, // unauthorized
			'message'=>'Unauthorized. Please login to complete your request.'
			)
		);
		exit();
	}

	if (isExpired()) { // if session expired, tell user
		echo json_encode(array(
			'status'=>401, // unauthorized
			'message'=>'Session expired. Please login to complete your request.'
			)
		);
		exit();
	}

	updateLastReq(); // ajax req means user is active

	// try to get current user permissions
	$r = $db->q("SELECT p.*
			FROM volunteers v
			LEFT JOIN privileges p
			ON v.privilege_id = p.id
			WHERE v.id=$_SESSION[id]"
	);

	$priv_error = json_encode(array(
			'status'=>500, //  db error
			'message'=>"An error occurred while checking your privileges.\nI cannot allow you to proceed."
		)
	);
	if (!$r->isValid())
		die($priv_error);

	// global containing all this user's privileges
	$PRIV = $r->buildArray();
	$PRIV = array_key_exists(0, $PRIV) ? $PRIV[0] : null;

	if ($PRIV == null)
		die($priv_error);

	switch ($table)
	{
		case 1: //volunteer
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
								FROM	growers g, sources s, property_types pt, property_relationships pr
								WHERE	g.id IN($ids) AND g.source_id = s.id AND g.property_type_id = pt.id AND g.property_relationship_id = pr.id");										
		break;		
		case 3: // tree
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
		
		case 4: // distribution
			$res = mysql_query("SELECT name as 'Agency Name',
									   street as 'Street Address',
									   city as City,
									   state as State,
									   zip as 'Zip Code',
									   contact as 'Agency Contact',
									   email as Email,
									   phone as Phone,
									   notes as Notes
								FROM distributions WHERE id IN($ids) ");
		break;
				
		case 6: // donation
			$res = mysql_query("SELECT donation as Donation,
									   donor as Donor,
									   value as Value,
									   date as Date
								FROM donations WHERE id IN($ids) ");
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