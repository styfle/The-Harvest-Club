<?php
require_once('include/Database.inc.php');
require_once('include/Mail.inc.php');
require_once('include/auth.inc.php');

header('Content-type: application/json');

if (!isLoggedIn(false)) { // if we're not logged in, tell user
	echo json_encode(array(
		'status'=>401,
		'message'=>'Unauthorized. Please login to complete your request.'
		)
	);
	exit();
}

if (isExpired()) { // if session expired, tell user
	echo json_encode(array(
		'status'=>401,
		'message'=>'Session expired. Please login to complete your request.'
		)
	);
	exit();
}


$cmd = $_REQUEST['cmd'];
$data = array('status'=>200); // default to OK
// See http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html

function contains($haystack, $needle) {
	return stripos($haystack, $needle) !== false;
}

function updateVolunteer($exists) {
	global $db;
	global $data;
	global $mail;
	$id = $_REQUEST['id'];						
	$firstName = $_REQUEST['firstname'];		
	$middleName = $_REQUEST['middlename'];
	$lastName = $_REQUEST['lastname'];
	$organization = $_REQUEST['organization'];
	$phone = $_REQUEST['phone'];
	$email = $_REQUEST['email'];
	$status = $_REQUEST['status'];
	$street = $_REQUEST['street'];
	$city = $_REQUEST['city'];
	$state = $_REQUEST['state'];
	$zip = $_REQUEST['zip'];
	$priv_id = $_REQUEST['privilege_id'];
	$notes =  $_REQUEST['note'];
	$source_id = $_REQUEST['source_id']; // change to source_id
	
	if ($exists) { // volunteer exists so just update
		$sql = "Update volunteers Set first_name='$firstName', middle_name='$middleName',last_name='$lastName', phone='$phone', email='$email', status=$status, street='$street', city='$city', state='$state',zip='$zip', notes='$notes', source_id=$source_id where id=$id";						
		$r = $db->q($sql);
		
		for ($i=1; $i<6 ; $i++) {
			if (isset($_GET["volunteerRole$i"])) { //it is checked
				$sql = "Insert into volunteer_roles(volunteer_id, volunteer_type_id) Values($id,$i)";
				$r = $db->q($sql);
			} else { // it is unchecked
				$sql = "Delete From volunteer_roles Where volunteer_type_id=$i And volunteer_id=$id";					
				$r = $db->q($sql);
			}
		}
			
		for ($i=1; $i<8 ; $i++) {
			if (isset($_GET["volunteerDay$i"])) { //it is checked	   
				$sql = "Insert into volunteer_prefers(volunteer_id, day_id) Values($id,$i)";
				$r = $db->q($sql);
			} else { // it is unchecked			
				$sql = "Delete From volunteer_prefers Where day_id=$i And volunteer_id=$id";					
				$r = $db->q($sql);			
			}
		}
		
		// Check if priv changed
		$sql = "SELECT p.id, p.name, v.password, v.first_name, v.last_name FROM volunteers v LEFT JOIN privileges p ON v.privilege_id = p.id WHERE v.id = $id";
		$r = $db->q($sql);
		$row = $r->getRow();
		
		if ($priv_id != $row[0]) { // privs have changed
			$sql = "UPDATE volunteers SET privilege_id=$priv_id"; // new priv
			$message = "$firstName $lastName,\r\nYour privileges have changed. You are now a(n) $row[1]!\r\n";
			if (empty($row[3])) { // no password
				$pass = generatePassword(); // so generate
				// http://dev.mysql.com/doc/refman/5.5/en/encryption-functions.html#function_sha2
				$sql .= ", password = SHA2($pass) "; // and add to update
				$message .=  "The following password has been generated for you:\r\n$pass";
			}
			$sql .= " WHERE id=$id"; // only update this volunteer
			$r = $db->q($sql); // execute
			// Send an email
			$subject = 'The Harvest Club - Privileges Changed';
			$mail->send($subject, $message, $email); // use default from/replyto
		}
	
	} else { // adding new volunteer
		$sql = "INSERT INTO volunteers (first_name, middle_name, last_name, organization, phone, email, status, street, city, state, zip, privilege_id, notes, source_id, signed_up) VALUES
		('$firstName', '$middleName', '$lastName', '$organization', '$phone', '$email', '$status', '$street', '$city', '$state', '$zip', '$priv_id', '$notes', $source_id, CURDATE())";
		$r = $db->q($sql);
		getError($r);
	}
}

function updateTree($exist){
	global $db;	
	global $data;
	$id = $_REQUEST['id'];
	$grower_id = $_REQUEST['grower_id'];
	$tree_type_id = $_REQUEST['tree_type_id'];
	$varietal = $_REQUEST['varietal'];		
	$number = $_REQUEST['number'];		
	$avgHeight_id = $_REQUEST['avgHeight_id'];
	$chemicaled_id = $_REQUEST['chemicaled_id'];
			
	if($exist){
		$sql = "Update grower_trees Set grower_id='".$grower_id."', tree_type='".$tree_type_id."', varietal='".$varietal."', number='".$number."',  avgHeight_id='".$avgHeight_id."',  chemicaled='".$chemicaled_id."' where id=".$id;				
		$r = $db->q($sql);	
		for ($i=1; $i<13 ; $i++) {
			if (isset($_GET["tree_month$i"])) { //it is checked
				$sql = "Insert into month_harvests(tree_id, month_id) Values($id,$i)";
				$r = $db->q($sql);
			} else { // it is unchecked
				$sql = "Delete From month_harvests Where month_id=$i And tree_id=$id";					
				$r = $db->q($sql);
			}
		}
		
	}
	else{
		$sql = "INSERT INTO grower_trees(grower_id, tree_type, varietal, number, avgHeight_id, chemicaled)
				VALUES ('$grower_id', '$tree_type_id', '$varietal', '$number', '$avgHeight_id', '$chemicaled_id')";				
		$r = $db->q($sql);
		$treeID =  mysql_insert_id();
		for ($i=1; $i<13 ; $i++) {			
			if (isset($_GET["tree_month$i"])) { //it is checked
				$sql = "INSERT INTO month_harvests(tree_id,month_id) VALUES('$treeID', '$i')";
				$r = $db->q($sql);
			} else { // it is unchecked
				$sql = "Delete From month_harvests Where month_id=$i And tree_id=$treeID";					
				$r = $db->q($sql);
			}
		}
		getError($r);
	}
}

function getTree_Months($sql){
	global $db;
	global $data;
	$r = $db->q($sql);
	if (getError($r))
		return $data;
	
	// get result as giant array
	$a = $r->buildArray();	
	
	foreach ($a as $v) {
		// add a checkbox to each row (might need unique names)
		$record = array();
		foreach ($v as $name=>$value) {
			$record[] = $value;
		}
		$data['datatable']['aaData'][] = $record;
	}
}


function getError($r) {
	global $data;
	global $db;

	if ($r->isValid())
		return 0; // 0 is success

	$data['status'] = 999;
	$data['message'] = 'DB: ' . $db->error(); // show error message
	return $data;
}

function getTable($sql) {
	global $db;
	global $data;
	//$data['datatable'] = array('aoColumns', 'aaData');

	$r = $db->q($sql);
	if (getError($r))
		return $data;
	
	// get result as giant array
	$a = $r->buildArray();
	
	// if empty return empty result
	if (!$a) {
		$data['datatable']['aoColumns'][] = array('sTitle'=>'Empty Set');
		$data['datatable']['aaData'][] = array('No results found. Maybe you should add something?');
		return; 
	}
	
	// first column is select-all checkbox
	$data['datatable']['aoColumns'][] = array(
		'sTitle' => '<input type="checkbox" name="select-all" />',
		'sWidth' => '1%',
		'bSortable' => false
	);

	// add column data
	foreach ($a[0] as $k => $v) {
		$column = array();
		$column['sTitle'] = $k;
		if ($k == 'id' || $k == 'password' || contains($k, '_id')) {
			$column['bSearchable'] = false;
			$column['bVisible'] = false;
		} else if ($k == 'middle_name' || $k == 'street' || $k == 'state' || $k == 'zip') {
			$column['bVisible'] = false; // hide but still searchable
		} else if ($k == 'notes') {
			$column['sClass'] = 'left'; // align left
		}
		$data['datatable']['aoColumns'][] = $column;
	}
	
	// add row data
	foreach ($a as $v) {
		// add a checkbox to each row (might need unique names)
		$record = array('<input type="checkbox" name="select-row" />');
		foreach ($v as $name=>$value) {
			$record[] = $value;
		}
		$data['datatable']['aaData'][] = $record;
	}

}

function getDistribution_Hours($sql) {
	global $db;
	global $data;

	$r = $db->q($sql);
	if (getError($r))
		return $data;
	
	// get result as giant array
	$a = $r->buildArray();
	
	foreach ($a as $v) {
		$record = array();
		foreach ($v as $name=>$value) {
			$record[] = $value;
		}
		$data['datatable']['aaData'][] = $record;
	}	

}

function getVolunteer_Roles($sql) {
	global $db;
	global $data;
	//$data['datatable'] = array('aoColumns', 'aaData');

	$r = $db->q($sql);
	if (getError($r))
		return $data;
	
	// get result as giant array
	$a = $r->buildArray();
	
	
	
	foreach ($a as $v) {
		// add a checkbox to each row (might need unique names)
		$record = array();
		foreach ($v as $name=>$value) {
			$record[] = $value;
		}
		$data['datatable']['aaData'][] = $record;
	}	

}
function getVolunteer_Prefer($sql) {
	global $db;
	global $data;
	//$data['datatable'] = array('aoColumns', 'aaData');

	$r = $db->q($sql);
	if (getError($r))
		return $data;
	
	// get result as giant array
	$a = $r->buildArray();
	
	
	
	foreach ($a as $v) {
		// add a checkbox to each row (might need unique names)
		$record = array();
		foreach ($v as $name=>$value) {
			$record[] = $value;
		}
		$data['datatable']['aaData'][] = $record;
	}	

}

function getName($sql) {
	global $db;
	global $data;
	//$data['datatable'] = array('aoColumns', 'aaData');

	$r = $db->q($sql);
	if (getError($r))
		return $data;
	
	// get result as giant array
	$a = $r->buildArray();
	
	
	
	foreach ($a as $v) {
		// add a checkbox to each row (might need unique names)
		$record = array();
		foreach ($v as $name=>$value) {
			$record[] = $value;
		}
		$data['datatable']['aaData'][] = $record;
	}	

}

function generatePassword($length=8) {   
	$password = "";
	$possible = "12346789abcdfghjkmnpqrtvwxyzABCDFGHJKLMNPQRTVWXYZ";	
	$maxlength = strlen($possible);  
	
	if ($length > $maxlength)
		$length = $maxlength;
	
	// add random characters to $password until $length is reached
	for ($i=0; $i<$length; $i++) { 
		// pick a random character from the possible ones
		$char = substr($possible, mt_rand(0, $maxlength-1), 1);		
		// have we already used this character in $password?
		if (!strstr($password, $char)) { 
		// no, so it's OK to add it onto the end of whatever we've already got...
		$password .= $char;
	  }
	}
	
	return $password;
  }



switch ($cmd)
{
	case 'get_notifications':
		$data['id'] = 0;
		$data['title'] = 'Notifications';
		$data['content'] = '';
		break;
	case 'get_volunteers':
		$data['id'] = 1;
		$data['title'] = 'Volunteers';
		$sql = "SELECT v.*, p.name AS user_type FROM volunteers v LEFT JOIN privileges p ON v.privilege_id = p.id;";
		getTable($sql);
		break;
	case 'get_growers':
		$data['id'] = 2;
		$data['title'] = 'Growers';
		$sql = "SELECT g.*, pt.name AS property_type, pr.name AS property_relationship
				FROM growers g, property_types pt, property_relationships pr
				WHERE g.property_type_id = pt.id AND g.property_relationship_id = pr.id;";
		getTable($sql);
		break;
	case 'get_trees':
		$data['id'] = 3;
		$data['title'] = 'Trees';
		$sql = "SELECT gt.id AS tree_id, Concat(g.first_name,' ', g.last_name) AS Owner, g.id AS grower_id , tt.id AS 'tree_type_id', tt.name AS 'Tree type', gt.varietal AS Varietal, gt.number AS Number, gt.chemicaled AS Chemicaled_id, IF((gt.chemicaled=0),'No','Yes') AS Chemicaled, th.id AS avgHeight_id, th.name AS Height
				FROM grower_trees gt, tree_types tt, growers g, tree_heights th
				WHERE g.id = gt.grower_id AND gt.tree_type=tt.id AND gt.avgHeight_id = th.id;";
		getTable($sql);
		break;
	case 'get_distribs':
		$data['id'] = 4;
		$data['title'] = 'Distributions';
		$sql = "SELECT * FROM distributions d;";
		getTable($sql);
		break;
	case 'get_distribution_times':
		$id = $_REQUEST['id'];
		$data['title'] = 'Hours';
		$sql = "SELECT h.* FROM distributions d, distribution_hours h WHERE h.distribution_id = d.id AND d.id=$id";				
		getDistribution_Hours($sql);
		break;
	case 'update_distribution':
		global $db;
		global $data;
		$id = $_REQUEST['id'];
		$name = $_REQUEST['name'];
		$phone = $_REQUEST['phone'];
		$email = $_REQUEST['email'];
		$street = $_REQUEST['street'];
		$city = $_REQUEST['city'];
		$state = $_REQUEST['state'];
		$zip = $_REQUEST['zip'];
		$notes =  $_REQUEST['note'];
		$sql = "Update distributions Set name='$name', phone='$phone', email='$email', street='$street', city='$city', state='$state',zip='$zip', notes='$notes' where id=$id";				
		$r = $db->q($sql);	
		
		for ($i=1; $i<8 ; $i++) {
			$openHour = $_REQUEST['distributionHour'.$i.'-OpenHour'];			
			$openMin = $_REQUEST['distributionHour'.$i.'-OpenMin'];			
			$closeHour = $_REQUEST['distributionHour'.$i.'-CloseHour'];			
			$closeMin= $_REQUEST['distributionHour'.$i.'-CloseMin'];			
			
			$sql = "Select * From distribution_hours where distribution_id= ".$id." And day_id = ".$i;			
			$r = $db->q($sql);
			if($r->hasRows()) {
				if (($openHour!='') && ($openMin!='') && ($closeHour!='') && ($closeMin!='')) {
				  $sql = "Update distribution_hours Set open='".$openHour.":".$openMin."', close='".$closeHour.":".$closeMin."' Where distribution_id=".$id." And day_id=".$i;							  
				  $db->q($sql);
				}
				if (($openHour=='') && ($openMin=='') && ($closeHour=='') && ($closeMin=='')) {
				  $sql = "Delete From distribution_hours Where distribution_id=".$id." And day_id=".$i;						   
				  $db->q($sql);
				}
			} else if (($openHour!='') && ($openMin!='') && ($closeHour!='') && ($closeMin!='')) {
			   $sql = "Insert into distribution_hours(distribution_id, day_id, open, close) values (".$id.",".$i.",'".$openHour.":".$openMin."','".$closeHour.":".$closeMin."')";						   
			   $db->q($sql);
			}
		
		}
		break;
	
	case 'add_distribution':
		global $db;
		global $data;
	
		if (isset($_REQUEST['name']))
			$name = $_REQUEST['name'];
		else $name ="";
		
		if (isset($_REQUEST['phone']))
			$phone = $_REQUEST['phone'];
		else $phone ="";
		
		if (isset($_REQUEST['email']))
			$email = $_REQUEST['email'];
		else $email ="";
		
		if (isset($_REQUEST['street']))
			$street = $_REQUEST['street'];
		else $street ="";
		
		if (isset($_REQUEST['city']))
			$city = $_REQUEST['city'];
		else $city ="";
		
		if (isset($_REQUEST['state']))
			$state = $_REQUEST['state'];
		else $state ="";
		
		if (isset($_REQUEST['zip']))
			$zip = $_REQUEST['zip'];
		else $zip ="";
		
		if (isset($_REQUEST['notes']))
			$notes = $_REQUEST['notes'];
		else $notes ="";
		
		$sql = "Insert into distributions(name,phone, email, street, city, state, zip, notes) Values ('$name', '$phone', '$email','$street','$city', '$state','$zip','$notes')";				
		$r = $db->q($sql);
		if (!$r->isValid())
			$data = getError();
		else
			$id = $db->getInsertId();	
		
		for ($i=1; $i<8 ; $i++) {
			$openHour = $_REQUEST['distributionHour'.$i.'-OpenHour'];			
			$openMin = $_REQUEST['distributionHour'.$i.'-OpenMin'];			
			$closeHour = $_REQUEST['distributionHour'.$i.'-CloseHour'];			
			$closeMin= $_REQUEST['distributionHour'.$i.'-CloseMin'];			
			 if (($openHour!='') && ($openMin!='') && ($closeHour!='') && ($closeMin!='')) {
			   $sql = "Insert into distribution_hours(distribution_id, day_id, open, close) values (".$id.",".$i.",'".$openHour.":".$openMin."','".$closeHour.":".$closeMin."')";						   
			   $db->q($sql);
			}
		
		}
		break;
		
	case 'remove_distribution':
		global $db;
		global $data;
		$id = $_REQUEST['id'];
		$sql = "DELETE FROM distribution_hours WHERE distribution_id=$id";
		$r = $db->q($sql);
		$sql = "DELETE FROM distributions WHERE id=$id";
		$r = $db->q($sql);
		getError($r);
		break;
	case 'get_volunteer_role':
		$id = $_REQUEST['id'];
		$data['title'] = 'Roles';
		$sql = "SELECT t.id FROM volunteers v, volunteer_roles r , volunteer_types t Where v.id = r.volunteer_id And r.volunteer_type_id = t.id And v.id=".$id;			
		getVolunteer_Roles($sql);
		break;
		
	case 'get_volunteer_prefer':
		$id = $_REQUEST['id'];
		$data['title'] = 'Prefer';
		$sql = "SELECT d.id FROM volunteers v, volunteer_prefers p , days d Where v.id = p.volunteer_id And p.day_id = d.id And v.id=".$id;			
		getVolunteer_Prefer($sql);
		break;
	case 'update_grower':
		global $db;
		global $data;
		$id = $_REQUEST['id'];
		$firstname = $_REQUEST['firstname'];
		$middlename = $_REQUEST['middlename'];
		$lastname = $_REQUEST['lastname'];
		$phone = $_REQUEST['phone'];
		$email = $_REQUEST['email'];
		$street = $_REQUEST['street'];
		$city = $_REQUEST['city'];
		$state = $_REQUEST['state'];
		$zip = $_REQUEST['zip'];
		$tools = $_REQUEST['tools'];		
		$source_id = $_REQUEST['source_id'];
		$notes =  $_REQUEST['notes'];		
		$property_type= $_REQUEST['property_type'];
		$property_relationship = $_REQUEST['property_relationship'];		
		$sql = "Update growers Set first_name='".$firstname."', middle_name ='".$middlename."', last_name='".$lastname."', phone='".$phone."', email='".$email."', street='".$street."', city='".$city."', state='".$state."',zip='".$zip."', tools='".$tools."', source_id='".$source_id."', notes='".$notes."', property_type_id ='".$property_type."', property_relationship_id ='".$property_relationship."' where id=".$id;				
		$r = $db->q($sql);
		break;
	case 'update_tree':		
		updateTree(true);
		break;	
	case 'add_tree':
		updateTree(false);
		break;
	case 'get_tree_month':
		$id = $_REQUEST['id'];
		$data['title'] = 'Months';
		$sql = "SELECT month_id FROM month_harvests mh Where mh.tree_id=".$id;				
		getTree_Months($sql);		
		break;
	case 'update_volunteer':
		updateVolunteer(true);
		break;
	case 'add_volunteer':
		updateVolunteer(false);
		break;
	case 'remove_volunteer':
		global $db;
		global $data;
		$id = $_REQUEST['id'];
		$sql = "DELETE FROM volunteers
				WHERE id=$id";
		$r = $db->q($sql);
		getError($r);
		break;
	case 'send_email':
		global $data;
		global $mail;
		global $db;
		// check if user can send email
		$sql = "SELECT send_email,email FROM volunteers v
				LEFT JOIN privileges p ON v.privilege_id = p.id
				WHERE v.id=1"; // TODO user auth needed here
		$r = $db->q($sql);
		//getError($r);
		$a = $r->buildArray();
		$can_send = $a[0];
		$my_email = $a[1];
		if (!$can_send) { // not allowed to send email
			$data['status'] = 403; // forbidden
			$data['message'] = 'You are not allowed to send email!';
		} else {
			$bcc = $_REQUEST['bcc'];
			$subject = $_REQUEST['subject'];
			$message = $_REQUEST['message'];
			$sent = $mail->sendBulk($subject, $message, $bcc); // maybe use $my_email for replyto
			if (!$sent) {
				$data['status'] = 500;
				$data['message'] = 'Mail could not be sent';
			}
		}
		break;
	case 'get_donors':
		$data['id'] = 6;
		$data['title'] = 'Donations';
		$sql = "SELECT id, donation as Donation, donor as Donor, value as Value, date(date) as Date FROM donations";
		getTable($sql);
		break;
		
	///////These are for event
	
	case 'get_events':
		$data['id'] = 5;
		$data['title'] = 'Events';		
		$sql = "SELECT id, name as 'Event Name', grower_id, captain_id, date(date) as Date FROM events ;";
		getTable($sql);
		break;
		
	case 'get_grower_name':
		$data['id'] = 10;
		$data['title'] = 'Grower-Name';
		$sql = "SELECT id, Concat(first_name,' ',middle_name,' ',last_name) FROM growers ;";
		getName($sql);
		break;	
		
	case 'get_volunteer_name':
		$data['id'] = 11;
		$data['title'] = 'Volunteer-Name';
		$sql = "SELECT id, Concat(first_name,' ',middle_name,' ',last_name) FROM volunteers ;";
		getName($sql);
		break;	
	
		
	case 'get_tree_name':
		$id = $_REQUEST['grower_id'];
		$data['id'] = 12;
		$data['title'] = 'Tree-Name';
		$sql = "SELECT gt.id, Concat(tt.name,'-',gt.varietal) FROM tree_types tt, grower_trees gt Where gt.tree_type = tt.id AND gt.grower_id = $id";
		getName($sql);
		break;
		
	case 'get_event_tree':
		$grower_id = $_REQUEST['id'];
		$event_id = $_REQUEST['event_id'];
		$data['id'] = 13;
		$data['title'] = 'Event Tree';
		$sql = "SELECT gt.id, h.pound FROM harvests h, grower_trees gt, events e where e.grower_id = gt.grower_id AND e.id = $event_id  And gt.id = h.tree_id And gt.grower_id = $grower_id And h.event_id = $event_id";
		getName($sql);
		break;
	
	case 'get_event_volunteer_name':
		$event_id = $_REQUEST['event_id'];
		$data['id'] = 14;
		$data['title'] = 'Volunteer-Name';
		$sql = "SELECT v.id, Concat(v.first_name,' ',v.middle_name,' ',v.last_name), ve.driver, ve.hour FROM volunteers v, events e, volunteer_events ve Where v.id = ve.volunteer_id And e.id = ve.event_id And e.id = $event_id;";
		getName($sql);
		break;
		
	case 'get_distribution_name':
		$data['id'] = 15;
		$data['title'] = 'Distribution-Name';
		$sql = "SELECT id, name FROM distributions ;";
		getName($sql);
		break;
		
	case 'get_driver':
		$data['id'] = 16;
		$data['title'] = 'Driver-Name';
		$id = $_REQUEST['id'];
		$sql = "SELECT * FROM drivings Where volunteer_id = $id ;";
		getName($sql);
		break;
		
	case 'update_event':
		$data['id'] = 16;
		$data['title'] = 'Update Event';
		$rawData = $_POST;
		$event_id = ($rawData["event_id"]);
		$event_name = ($rawData["event_name"]);
		$event_date = ($rawData["event_date"]);
		$grower_id = ($rawData["grower_id"]);
		$captain_id = ($rawData["captain_id"]);
		$tree_type = array();
		$volunteers = array();
		if (isset($rawData["treeType"]))
		 $tree_type = $rawData["treeType"];
		if (isset($rawData["volunteers"])) 
		 $volunteers = $rawData["volunteers"];
		
		$hostname =  MYSQL_SERVER;
		$dbname =  MYSQL_DB;
		$username = MYSQL_USER;
		$password = MYSQL_PASS;
		try {
			$dbh = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
		
		if ($dbh != null)
		{
			$dbh->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			   try
			   {
				$dbh->beginTransaction ();           # start the transaction
				// Delete old info
				$dbh->exec ("Delete From drivings Where event_id=$event_id");
				$dbh->exec ("Delete From volunteer_events Where event_id=$event_id");
				$dbh->exec ("Delete From harvests Where event_id=$event_id");		
				// Adding new info	
				$dbh->exec ("Update events Set name = '$event_name', grower_id = $grower_id, captain_id = $captain_id , date ='$event_date' Where id = $event_id");
				
				for( $i=0; $i< count($tree_type); $i++)
				{
				  $treeID = $tree_type[$i]["tree_id"];
				  $pound = $tree_type[$i]["pound"];
				  $dbh->exec ("Insert into harvests(event_id, tree_id, pound) Values ($event_id,$treeID,$pound)");
				}
				
				for( $i=0; $i< count($volunteers); $i++)
				{
				  $d=0;
				  $volunteerID = $volunteers[$i]["volunteer_id"];
				  $hour = $volunteers[$i]["hour"];
				  $driver = $volunteers[$i]["driver"];
				  if ( $driver == 'true')
				  {		
					$d++;
				    $treeID = $volunteers[$i]["tree_id"];
					$pound =  $volunteers[$i]["pound"];
					$distributionID = $volunteers[$i]["distribution_id"];
					$dbh->exec ("Insert into drivings(event_id, tree_id, volunteer_id, distribution_id, pound) Values ($event_id,$treeID,$volunteerID,$distributionID, $pound)");	
				  }
					$dbh->exec ("Insert into volunteer_events(event_id, volunteer_id, hour, driver) Values ($event_id, $volunteerID, $hour, $d)");
				  
				  
				}
				$dbh->commit ();                     # success
			   }
			   catch (PDOException $e)
			   {
				 print ("Transaction failed: " . $e->getMessage () . "\n");
				 $dbh->rollback ();                   # failure
			   }
		}
		break;
	

	case 'create_event':
		$data['id'] = 17;
		$data['title'] = 'Create Event';
		$rawData = $_POST;		
		$event_name = ($rawData["event_name"]);
		$event_date = ($rawData["event_date"]);
		$grower_id = ($rawData["grower_id"]);
		$captain_id = ($rawData["captain_id"]);
		$tree_type = array();
		$volunteers = array();
		if (isset($rawData["treeType"]))
		 $tree_type = $rawData["treeType"];
		if (isset($rawData["volunteers"])) 
		 $volunteers = $rawData["volunteers"];
		 
		$hostname =  MYSQL_SERVER;
		$dbname =  MYSQL_DB;
		$username = MYSQL_USER;
		$password = MYSQL_PASS;
		 
		$sql = "Insert into events( name, grower_id, captain_id, date) Values ('$event_name', $grower_id, $captain_id,'$event_date')";			
		$r = $db->q($sql);
		if (!$r->isValid())
			$data = getError();
		else
			$event_id = $db->getInsertId();
		
		try {
			$dbh = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
		
		if ($dbh != null)
		{
			$dbh->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			   try
			   {
				$dbh->beginTransaction ();           # start the transaction
				
				// Creating new info	
				//$dbh->exec ("Insert into events(id, name, grower_id, captain_id, date) Values ($event_id, $event_name, $grower_id, $captain_id,'$event_date')");
				
				for( $i=0; $i< count($tree_type); $i++)
				{
				  $treeID = $tree_type[$i]["tree_id"];
				  $pound = $tree_type[$i]["pound"];
				  $dbh->exec ("Insert into harvests(event_id, tree_id, pound) Values ($event_id,$treeID,$pound)");
				}
				
				for( $i=0; $i< count($volunteers); $i++)
				{
				  $d=0;
				  $volunteerID = $volunteers[$i]["volunteer_id"];
				  $hour = $volunteers[$i]["hour"];
				  $driver = $volunteers[$i]["driver"];
				  if ( $driver == 'true')
				  {		
					$d++;
				    $treeID = $volunteers[$i]["tree_id"];
					$pound =  $volunteers[$i]["pound"];
					$distributionID = $volunteers[$i]["distribution_id"];
					$dbh->exec ("Insert into drivings(event_id, tree_id, volunteer_id, distribution_id, pound) Values ($event_id,$treeID,$volunteerID,$distributionID, $pound)");	
				  }
					$dbh->exec ("Insert into volunteer_events(event_id, volunteer_id, hour, driver) Values ($event_id, $volunteerID, $hour, $d)");
				  
				  
				}
				$dbh->commit ();                     # success
			   }
			   catch (PDOException $e)
			   {
				 print ("Transaction failed: " . $e->getMessage () . "\n");
				 $dbh->rollback ();                   # failure
			   }
		}
		
		
		break;
		
	case 'remove_event':
		$event_id = $_REQUEST['id'];
		$hostname =  MYSQL_SERVER;
		$dbname =  MYSQL_DB;
		$username = MYSQL_USER;
		$password = MYSQL_PASS;
		try {
			$dbh = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}		
		
		if ($dbh != null)
		{
			$dbh->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			   try
			   {				
				$dbh->beginTransaction ();           # start the transaction
				// Delete old info
				$dbh->exec ("Delete From drivings Where event_id=$event_id");
				$dbh->exec ("Delete From volunteer_events Where event_id=$event_id");
				$dbh->exec ("Delete From harvests Where event_id=$event_id");		
				$dbh->exec ("Delete From events Where id=$event_id");		
				
				$dbh->commit ();                     # success
			   }
			   catch (PDOException $e)
			   {
				 print ("Transaction failed: " . $e->getMessage () . "\n");
				 $dbh->rollback ();                   # failure
			   }
		}
		break;
	case 'update_donation':
		global $db;
		global $data;
		$id = $_REQUEST['id'];
		$donation = $_REQUEST['donation'];
		$donor = $_REQUEST['donor'];
		$value = $_REQUEST['value'];
		$date = $_REQUEST['date'];
		$sql = "Update donations Set donation = '$donation', donor = '$donor', value =$value, date ='$date' where id=$id";	
		$r = $db->q($sql);
		break;
		
	case 'add_donation':
		global $db;
		global $data;
		$id = $_REQUEST['id'];
		$donation = $_REQUEST['donation'];
		$donor = $_REQUEST['donor'];
		$value = $_REQUEST['value'];
		$date = $_REQUEST['date'];
		$sql = "Insert into donations(donation, donor, value, date) Values('$donation','$donor', $value, '$date')";	
		$r = $db->q($sql);
		break;
		
	case 'remove_donation':
		global $db;
		global $data;
		$id = $_REQUEST['id'];
		$sql = "DELETE FROM donations
				WHERE id=$id";
		$r = $db->q($sql);
		getError($r);
		break;
	default:
		$data['status'] = 404; // Not found
		$data['message'] = "Unknown ajax command: $cmd";
}
echo json_encode($data);

?>
