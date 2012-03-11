<?php

require_once('include/Database.inc.php');
require_once('include/Mail.inc.php');
require_once('include/auth.inc.php');
require_once('include/autoresponse.inc.php');

header('Content-type: application/json');

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

$cmd = $_REQUEST['cmd']; // get command to perform
$data = array('status'=>200); // default to OK

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

function forbidden() {
	global $data;
	$data['status'] = 403; // forbidden
	$data['message']='Whoa buddy! You do not have permisson to perform this operation.';
	return $data;
}

function contains($haystack, $needle) {
	return stripos($haystack, $needle) !== false;
}

function updateVolunteer($exists) {
	global $db;
	global $data;
	global $mail;
	global $PRIV;
	$id = $_REQUEST['id'];						
	$firstName = $_REQUEST['firstname'];		
	$middleName = $_REQUEST['middlename'];
	$lastName = $_REQUEST['lastname'];
	$organization = $_REQUEST['organization'];
	$phone = $_REQUEST['phone'];
	$email = $_REQUEST['email'];
	$active_id = $_REQUEST['active_id'];
	$street = $_REQUEST['street'];
	$city = $_REQUEST['city'];
	$state = $_REQUEST['state'];
	$zip = $_REQUEST['zip'];
	$notes =  $_REQUEST['note'];
	/* forget about source id
    if(!isset($_REQUEST['source_id']))
		$source_id = 1; // Default to "Others";
    else
		$source_id = $_REQUEST['source_id'];
	*/

    if (isset($_REQUEST['privilege_id']))
		$priv_id = $_REQUEST['privilege_id'];
	else
		$priv_id = null;
	
	if ($exists) { // volunteer exists so just update
		$sql = "Update volunteers Set first_name='$firstName', middle_name='$middleName',last_name='$lastName', organization='$organization', phone='$phone', email='$email', active_id=$active_id, street='$street', city='$city', state='$state',zip='$zip', notes='$notes' where id=$id";						
		$r = $db->q($sql);
		getError($r);
		
		for ($i=1; $i<6 ; $i++) {
			if (isset($_REQUEST["volunteerRole$i"])) { //it is checked
				$sql = "Insert into volunteer_roles(volunteer_id, volunteer_type_id) Values($id,$i)";
				$r = $db->q($sql);
			} else { // it is unchecked
				$sql = "Delete From volunteer_roles Where volunteer_type_id=$i And volunteer_id=$id";					
				$r = $db->q($sql);
			}
		}
			
		for ($i=1; $i<8 ; $i++) {
			if (isset($_REQUEST["volunteerDay$i"])) { //it is checked	   
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
		
		$old_priv_id = $row[0];
		$old_priv_name = $row[1];
		$old_pass = $row[2];
		$old_first = $row[3];
		$old_last = $row[4];
		
		if ($priv_id != null && $priv_id != $old_priv_id) { // privs have changed
			if (!$PRIV['change_priv']) { // make sure this user can modify other users
				$data['status']=403;
				$data['message']='Are you trying to hack us? You cannot change user privileges!';
				return;
			}
			$sql = "UPDATE volunteers SET privilege_id=$priv_id"; // new priv
			$message = "$firstName $lastName,\r\nYour privileges have changed. If you feel this is an error, please contact us.";
			//You are no longer a(n) $old_priv_name!\r\n";
			if (empty($old_pass)) { // no password
				$pass = generatePassword(); // so generate
				$sql .= ", password = SHA1('$pass') "; // and add to update
				$message .=  "\n\rThe following password has been generated for you:\r\n$pass\n\rThis password is required to administrate The Harvest Club.";
			}
			$sql .= " WHERE id=$id"; // only update this volunteer
			$r = $db->q($sql); // execute
			// Send an email
			$subject = 'The Harvest Club - Privileges Changed';
			$mail->send($subject, $message, $email); // use default from/replyto
		}
	
	} else { // adding new volunteer
		$sql = "INSERT INTO volunteers (first_name, middle_name, last_name, organization, phone, email, active_id, street, city, state, zip, privilege_id, notes, signed_up) VALUES
		('$firstName', '$middleName', '$lastName', '$organization', '$phone', '$email', '$active_id', '$street', '$city', '$state', '$zip', '$priv_id', '$notes', CURDATE())";
		$r = $db->q($sql);
		getError($r);

		$id = mysql_insert_id();
		
		for ($i=1; $i<6 ; $i++) {
			if (isset($_REQUEST["volunteerRole$i"])) { //it is checked
				$sql = "Insert into volunteer_roles(volunteer_id, volunteer_type_id) Values($id,$i)";
				$r = $db->q($sql);
			} else { // it is unchecked
				$sql = "Delete From volunteer_roles Where volunteer_type_id=$i And volunteer_id=$id";					
				$r = $db->q($sql);
			}
		}
			
		for ($i=1; $i<8 ; $i++) {
			if (isset($_REQUEST["volunteerDay$i"])) { //it is checked	   
				$sql = "Insert into volunteer_prefers(volunteer_id, day_id) Values($id,$i)";
				$r = $db->q($sql);
			} else { // it is unchecked			
				$sql = "Delete From volunteer_prefers Where day_id=$i And volunteer_id=$id";					
				$r = $db->q($sql);			
			}
		}
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

function updateGrower($exist){
	global $db;
	global $data;
	$id = $_REQUEST['id'];
	$firstname = $_REQUEST['firstname'];
	$middlename = $_REQUEST['middlename'];
	$lastname = $_REQUEST['lastname'];
	$phone = $_REQUEST['phone'];
	$email = $_REQUEST['email'];
	$preferred = $_REQUEST['preferred'];
	$street = $_REQUEST['street'];
	$city = $_REQUEST['city'];
	$state = $_REQUEST['state'];
	$zip = $_REQUEST['zip'];
	$tools = $_REQUEST['tools'];
	$notes =  $_REQUEST['notes'];		
	$property_type= $_REQUEST['property_type'];
	$property_relationship = $_REQUEST['property_relationship'];		
		
	if($exist){
		$sql = "Update growers Set first_name='".$firstname."', middle_name ='".$middlename."', last_name='".$lastname."', phone='".$phone."', email='".$email."', street='".$street."', city='".$city."', state='".$state."',zip='".$zip."', tools='".$tools."', notes='".$notes."', property_type_id ='".$property_type."', property_relationship_id ='".$property_relationship."' where id=".$id;				
		$r = $db->q($sql);	
	}
	else{
		$sql = "INSERT INTO growers(first_name, middle_name, last_name, phone, email, preferred, street, city, state, zip, tools, notes, pending, property_type_id, property_relationship_id)
				VALUES ('$firstname', '$middlename', '$lastname', '$phone', '$email', '$preferred', '$street', '$city', '$state', '$zip', '$tools', '$notes', 1, '$property_type', '$property_relationship')";				
		$r = $db->q($sql);		
		getError($r);
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
		'sWidth' => '30px',
		'bSortable' => false
	);

	// add column data
	foreach ($a[0] as $k => $v) {
		$column = array();
		$column['sTitle'] = $k;
		if ($k == 'id' || $k == 'password' || contains($k, '_id')) {
			$column['bSearchable'] = false;
			$column['bVisible'] = false;
		} else if ($k == 'middle_name' || $k == 'street' || $k == 'state' || $k == 'zip' || contains($k, '_tag') || contains($k, 'property_')) {
			$column['bVisible'] = false; // hide but still searchable
		} else if (contains($k,'notes') || contains($k,'phone') || contains($k,'email') || contains($k,'signed')) {
			$column['sClass'] = 'small';
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

function dateToStr($dateStr) {
	date_default_timezone_set('America/Los_Angeles');
	$a = explode('-', $dateStr); // split
	return date("l F j, Y", mktime(0, 0, 0, $a[1], $a[2], $a[0]));
}

switch ($cmd)
{
	case 'get_notifications':
		// no privs needed, just check user type
		$data['id'] = 0;
		$data['title'] = 'Notifications';
		$sql = "SELECT 'Pending volunteers' AS 'Table', count(*) AS 'Updates' 
				FROM volunteers WHERE privilege_id=1
				UNION
				SELECT 'Active volunteers' AS 'Table', count(*) AS 'Updates'
				FROM volunteers WHERE active_id=1
				UNION
				SELECT 'Pending growers' AS 'Table', count(*) AS 'Updates'
				FROM growers WHERE pending=1;";
		getTable($sql);
		break;
	case 'get_volunteers':
		if (!$PRIV['view_volunteer']) {
			forbidden();
			break;
		}
		$data['id'] = 1;
		$data['title'] = 'Volunteers';
		$sql = "SELECT v.id,							
					   v.first_name as First, 			
					   v.last_name as Last,				
					   v.city as City,
					   v.email as Email,
					   v.phone as Phone,
					   p.name as 'User Type',
					   v.signed_up as 'Signed Up',
					   IF((v.active_id=1),'Active','Inactive') as Active, 
					   v.notes as Notes,
					   v.middle_name as middle_tag,
					   v.organization as organization_tag,
					   v.street as street_tag,
					   v.state as state_tag,
					   v.zip as zip_tag,
					   v.password as password_id,
					   v.source_id,
					   v.privilege_id
				FROM volunteers v 
				LEFT JOIN privileges p ON v.privilege_id = p.id;";
		getTable($sql);
		break;
	case 'get_growers':
		if (!$PRIV['view_grower']) {
			forbidden();
			break;
		}
		$data['id'] = 2;
		$data['title'] = 'Growers';
		$sql = "SELECT g.id, g.first_name AS 'First Name', g.middle_name, g.last_name AS 'Last Name', g.phone AS 'Phone', g.email AS 'Email', g.preferred AS 'Preferred', g.street, g.city AS 'City', g.state, g.zip, g.tools AS tools_id, g.source_id, g.notes AS Notes, g.pending AS pending_id, IF((g.pending=1),'Pending','Approved') AS Pending, g.property_type_id, g.property_relationship_id, pt.name AS property_type, pr.name AS property_relationship
				FROM growers g 	LEFT JOIN property_types pt ON g.property_type_id = pt.id
								LEFT JOIN property_relationships pr ON g.property_relationship_id = pr.id;";
		getTable($sql);
		break;
	case 'get_grower':
		if (!$PRIV['view_grower']) {
			forbidden();
			break;
		}
		$data['id'] = 2;
		$data['title'] = 'Grower';
		$growerID = $_REQUEST['growerID'];
		$sql = "SELECT g.id, g.first_name AS 'First Name', g.middle_name, g.last_name AS 'Last Name', g.phone AS 'Phone', g.email AS 'Email', g.preferred AS 'Preferred', g.street, g.city AS 'City', g.state, g.zip, g.tools AS tools_id, g.source_id, g.notes AS Notes, g.pending AS pending_id, IF((g.pending=1),'Pending','Approved') AS Pending, g.property_type_id, g.property_relationship_id, pt.name AS property_type, pr.name AS property_relationship
				FROM growers g 	LEFT JOIN property_types pt ON g.property_type_id = pt.id
								LEFT JOIN property_relationships pr ON g.property_relationship_id = pr.id
				WHERE g.id = $growerID;";
		getTable($sql);
		break;
	
	case 'get_trees': // same priv as grower
		if (!$PRIV['view_grower']) {
			forbidden();
			break;
		}
		$data['id'] = 3;
		$data['title'] = 'Trees';
		
		$sql = "SELECT gt.id AS tree_id, Concat(g.first_name,' ', g.last_name) AS Owner, g.id AS grower_id , tt.id AS 'tree_type_id', tt.name AS 'Tree type', gt.varietal AS Varietal, gt.number AS Number, gt.chemicaled AS Chemicaled_id, IF((gt.chemicaled=0),'No','Yes') AS Chemicaled, th.id AS avgHeight_id, th.name AS Height, 
					(	SELECT group_concat(m.name)
						FROM	month_harvests mh, months m
						WHERE mh.tree_id = gt.id AND mh.month_id = m.id) month_tag
				FROM grower_trees gt, tree_types tt, growers g, tree_heights th
				WHERE g.id = gt.grower_id AND gt.tree_type=tt.id AND gt.avgHeight_id = th.id;";
		getTable($sql);
		break;
	case 'get_trees_from':	
		if (!$PRIV['view_grower']) {
			forbidden();
			break;
		}
		$data['id'] = 3;
		$data['title'] = 'Trees';
		$growerID = $_REQUEST['growerID'];
		$sql = "SELECT gt.id AS tree_id, Concat(g.first_name,' ', g.last_name) AS Owner, g.id AS grower_id , tt.id AS 'tree_type_id', tt.name AS 'Tree type', gt.varietal AS Varietal, gt.number AS Number, gt.chemicaled AS Chemicaled_id, IF((gt.chemicaled=0),'No','Yes') AS Chemicaled, th.id AS avgHeight_id, th.name AS Height, 
					(	SELECT group_concat(m.name)
						FROM	month_harvests mh, months m
						WHERE mh.tree_id = gt.id AND mh.month_id = m.id) month_tag
				FROM grower_trees gt, tree_types tt, growers g, tree_heights th
				WHERE g.id = gt.grower_id AND g.id = $growerID AND gt.tree_type=tt.id AND gt.avgHeight_id = th.id;";
		getTable($sql);
		break;
	case 'get_active_volunteers':
		if (!$PRIV['view_volunteer']) {
			forbidden();
			break;
		}		
		$data['id'] = 1;
		$data['title'] = 'Volunteers';
		$sql = "SELECT v.id,							
					   v.first_name as First, 			
					   v.last_name as Last,				
					   v.city as City,
					   v.email as Email,
					   v.phone as Phone,
					   p.name as 'User Type',
					   v.signed_up as 'Signed Up',
					   IF((v.active_id=1),'Active','Inactive') as Active, 
					   v.notes as Notes,
					   v.middle_name as middle_tag,
					   v.organization as organization_tag,
					   v.street as street_tag,
					   v.state as state_tag,
					   v.zip as zip_tag,
					   v.password as password_id,
					   v.source_id,
					   v.privilege_id
				FROM volunteers v 
				LEFT JOIN privileges p ON v.privilege_id = p.id
				WHERE v.active_id = 1;";
		getTable($sql);
		break;
	case 'get_pending_volunteers':
		if (!$PRIV['view_volunteer']) {
			forbidden();
			break;
		}		
		$data['id'] = 1;
		$data['title'] = 'Volunteers';
		$sql = "SELECT v.id,							
					   v.first_name as First, 			
					   v.last_name as Last,				
					   v.city as City,
					   v.email as Email,
					   v.phone as Phone,
					   p.name as 'User Type',
					   v.signed_up as 'Signed Up',
					   IF((v.active_id=1),'Active','Inactive') as Active, 
					   v.notes as Notes,
					   v.middle_name as middle_tag,
					   v.organization as organization_tag,
					   v.street as street_tag,
					   v.state as state_tag,
					   v.zip as zip_tag,
					   v.password as password_id,
					   v.source_id,
					   v.privilege_id
				FROM volunteers v 
				LEFT JOIN privileges p ON v.privilege_id = p.id
				WHERE v.privilege_id = 1;";
		getTable($sql);		
		break;
	case 'get_pending_growers':
		if (!$PRIV['view_grower']) {
			forbidden();
			break;
		}
		$data['id'] = 2;
		$data['title'] = 'Growers';
		$sql = "SELECT g.id, g.first_name AS 'First Name', g.middle_name, g.last_name AS 'Last Name', g.phone AS 'Phone', g.email AS 'Email', g.preferred AS 'Preferred', g.street, g.city AS 'City', g.state, g.zip, g.tools AS tools_id, g.source_id, g.notes AS Notes, g.pending AS pending_id, IF((g.pending=1),'Pending','Approved') AS Pending, g.property_type_id, g.property_relationship_id, pt.name AS property_type, pr.name AS property_relationship
				FROM growers g 	LEFT JOIN property_types pt ON g.property_type_id = pt.id
								LEFT JOIN property_relationships pr ON g.property_relationship_id = pr.id
				WHERE g.pending = 1;";
		getTable($sql);
		break;
	break;
	case 'get_distribs':
		if (!$PRIV['view_distrib']) {
			forbidden();
			break;
		}
		$data['id'] = 4;
		$data['title'] = 'Distributions';
		$sql = "SELECT id,
					   name as 'Agency Name',
				       street as 'Street Address',
					   city as City,
					   zip as 'Zip Code',
					   contact as 'Agency Contact',
					   phone as Phone,
					   notes as Notes,
					   email as email_tag,
					   state as state_tag,
					   (	SELECT group_concat(d.name)
						FROM	distribution_hours dh, days d
						WHERE dh.distribution_id = dis.id AND dh.day_id = d.id) day_tag
				FROM distributions dis;";
		getTable($sql);
		break;
	case 'get_distribution_times':
		if (!$PRIV['view_distrib']) {
			forbidden();
			break;
		}
		$id = $_REQUEST['id'];
		$data['title'] = 'Hours';
		$sql = "SELECT h.* FROM distributions d, distribution_hours h WHERE h.distribution_id = d.id AND d.id=$id";				
		getDistribution_Hours($sql);
		break;
	case 'update_distribution':
		if (!$PRIV['edit_distrib']) {
			forbidden();
			break;
		}
		global $db;
		global $data;
		$id = $_REQUEST['id'];
		$name = $_REQUEST['name'];
		$contact = $_REQUEST['contact'];
		$phone = $_REQUEST['phone'];
		$email = $_REQUEST['email'];
		$street = $_REQUEST['street'];
		$city = $_REQUEST['city'];
		$state = $_REQUEST['state'];
		$zip = $_REQUEST['zip'];
		$notes =  $_REQUEST['note'];
		$sql = "Update distributions Set name='$name', contact='$contact', phone='$phone', email='$email', street='$street', city='$city', state='$state',zip='$zip', notes='$notes' where id=$id";				
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
		if (!$PRIV['edit_distrib']) {
			forbidden();
			break;
		}
		global $db;
		global $data;
	
		if (isset($_REQUEST['name']))
			$name = $_REQUEST['name'];
		else $name ="";

		if (isset($_REQUEST['contact']))
			$contact = $_REQUEST['contact'];
		else $contact ="";
		
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
		
		$sql = "Insert into distributions(name, contact, phone, email, street, city, state, zip, notes) Values ('$name', '$contact', '$phone', '$email','$street','$city', '$state','$zip','$notes')";				
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
		if (!$PRIV['del_distrib']) {
			forbidden();
			break;
		}
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
		if (!$PRIV['view_volunteer']) {
			forbidden();
			break;
		}
		$id = $_REQUEST['id'];
		$data['title'] = 'Roles';
		$sql = "SELECT t.id FROM volunteers v, volunteer_roles r , volunteer_types t Where v.id = r.volunteer_id And r.volunteer_type_id = t.id And v.id=".$id;			
		getVolunteer_Roles($sql);
		break;
		
	case 'get_volunteer_prefer':
		if (!$PRIV['view_volunteer']) {
			forbidden();
			break;
		}
		$id = $_REQUEST['id'];
		$data['title'] = 'Prefer';
		$sql = "SELECT d.id FROM volunteers v, volunteer_prefers p , days d Where v.id = p.volunteer_id And p.day_id = d.id And v.id=".$id;			
		getVolunteer_Prefer($sql);
		break;
	case 'update_grower':		
		if (!$PRIV['edit_grower']) {
			forbidden();
			break;
		}
		updateGrower(true);
	break;	
	case 'add_grower':
		if (!$PRIV['edit_grower']) {
			forbidden();
			break;
		}
		updateGrower(false);
	break;
	case 'approve_grower':
		if (!$PRIV['edit_grower']) { //TODO find out if this needs a separate priv
			forbidden();
			break;
		}
		$growerID = $_REQUEST['growerID'];
		$sql = "UPDATE growers SET pending = 0
				WHERE id=".$growerID;
		$r = $db->q($sql);
		getError($r);
	break;
	case 'approve_volunteer':
		if (!$PRIV['edit_volunteer']) { //TODO find out if this needs a separate priv
			forbidden();
			break;
		}
		$volunteerID = $_REQUEST['volunteerID'];
		$sql = "UPDATE volunteers SET privilege_id = 2
				WHERE id=".$volunteerID;
		$r = $db->q($sql);
		getError($r);
	break;
	case 'update_tree':		
		if (!$PRIV['edit_grower']) {
			forbidden();
			break;
		}
		updateTree(true);
		break;	
	case 'add_tree':
		if (!$PRIV['edit_grower']) {
			forbidden();
			break;
		}
		updateTree(false);
		break;
	case 'get_tree_month':
		if (!$PRIV['view_grower']) {
			forbidden();
			break;
		}
		$id = $_REQUEST['id'];
		$data['title'] = 'Months';
		$sql = "SELECT month_id FROM month_harvests mh Where mh.tree_id=".$id;				
		getTree_Months($sql);		
		break;
	case 'update_volunteer':
		if (!$PRIV['edit_volunteer']) {
			forbidden();
			break;
		}
		updateVolunteer(true);
		break;
	case 'add_volunteer':
		if (!$PRIV['edit_volunteer']) {
			forbidden();
			break;
		}
		updateVolunteer(false);
		break;
	case 'remove_volunteer':
		if (!$PRIV['del_volunteer']) {
			forbidden();
			break;
		}
		global $db;
		global $data;
		$id = $_REQUEST['id'];
		$sql = "DELETE FROM volunteers
				WHERE id=$id";
		$r = $db->q($sql);
		getError($r);
		break;
	case 'remove_grower':
		if (!$PRIV['del_grower']) {
			forbidden();
			break;
		}
		global $db;
		global $data;
		$id = $_REQUEST['id'];	
		$sql = "DELETE FROM growers
				WHERE id=$id";		
		$r = $db->q($sql);				
		getError($r);
		break;
	case 'remove_tree':
		if (!$PRIV['del_grower']) {
			forbidden();
			break;
		}
		global $db;
		global $data;
		$id = $_REQUEST['id'];
		$sql = "DELETE FROM grower_trees
				WHERE id=$id";
		$r = $db->q($sql);
		getError($r);
		break;
	case 'send_email':
		if (!$PRIV['send_email']) {
			forbidden();
			break;
		}
		global $data;
		global $mail;
		global $db;

		$my_email = $_SESSION['email'];
		$bcc = $_REQUEST['bcc'];
		$subject = $_REQUEST['subject'];
		$message = $_REQUEST['message'];
		$template = $_REQUEST['template'];
		$event_id = $_REQUEST['event_id'];

		if ($template) { // has template attachment
			$sql="SELECT
				g.first_name AS grower_f,
				g.last_name AS grower_l,
				g.street, g.city, g.state, g.zip,
				e.date, e.time,
				v.first_name AS captain_f,
				v.last_name AS captain_l,
				v.phone AS captain_phone,
				CONCAT(tt.name,' (',t.varietal, ')') AS fruit
				FROM events e, growers g, volunteers v, harvests h, grower_trees t, tree_types tt
				WHERE e.grower_id=g.id
				AND e.captain_id=v.id
				AND h.event_id = e.id
				AND h.tree_id=t.id
				AND t.tree_type=tt.id
				AND e.id =$event_id";				
			$r = $db->q($sql);
			if (!$r->isValid() || !$r->hasRows()) {
				$data['status'] = 432;
				$data['message'] = "No event found with id=$event_id";
				break;
			}
			$params = $r->getAssoc();
			$params['me_f'] = $_SESSION['first_name'];
			$params['me_l'] = $_SESSION['last_name'];
			$params['date'] = dateToStr($params['date']);
			// bug here: might have multiple fruit records
			//print_r($params);
		}

		$delim = "\r\n\r\n";
		if (get_magic_quotes_gpc()) // See http://php.net/manual/en/function.get-magic-quotes-gpc.php
			$message = stripslashes($message);

		switch ($template) {
			case 'invitation':
				$message = invitationEmail($params) .$delim. $message;
				break;
			case 'details':
				$message = harvestDetailsEmail($params) .$delim. $message;
				break;
			case 'reminder':
				$message = reminderEmail($params) .$delim. $message;
				break;
			default:
				break;
		}

		$sent = $mail->sendBulk($subject, $message, $bcc, $my_email);
		if (!$sent) {
			$data['status'] = 500;
			$data['message'] = 'Oh boy. Mail could not be sent! Maybe the server is overloaded?';
		}
		break;
	case 'get_donors':
		if (!$PRIV['view_donor']) {
			forbidden();
			break;
		}
		$data['id'] = 6;
		$data['title'] = 'Donations';
		$sql = "SELECT id, donation, donor, value, date FROM donations";
		getTable($sql);
		break;
		
	///////These are for event
	
	case 'get_events':
		if (!$PRIV['view_event']) {
			forbidden();
			break;
		}
		$data['id'] = 5;
		$data['title'] = 'Events';		
		$sql = "SELECT e.id, e.grower_id, e.captain_id, date(e.date) as Date, Concat(g.first_name,' ',g.middle_name,' ',g.last_name) as Grower, g.city, e.time, e.notes FROM events e, growers g Where e.grower_id = g.id;";

		getTable($sql);
		break;
		
	case 'get_grower_name':
		if (!$PRIV['view_event']) {
			forbidden();
			break;
		}
		$data['id'] = 10;
		$data['title'] = 'Grower-Name';
		$sql = "SELECT id, Concat(first_name,' ',middle_name,' ',last_name), phone, street, city FROM growers ;";
		getName($sql);
		break;	
		
	case 'get_volunteer_name':
		if (!$PRIV['view_event']) {
			forbidden();
			break;
		}
		$data['id'] = 11;
		$data['title'] = 'Volunteer-Name';
		$sql = "SELECT id, Concat(first_name,' ',middle_name,' ',last_name) FROM volunteers ;";
		getName($sql);
		break;	
	
		
	case 'get_tree_name':
		if (!$PRIV['view_event']) {
			forbidden();
			break;
		}
		$id = $_REQUEST['grower_id'];
		$data['id'] = 12;
		$data['title'] = 'Tree-Name';
		$sql = "SELECT gt.id, Concat(tt.name,'-',gt.varietal) FROM tree_types tt, grower_trees gt Where gt.tree_type = tt.id AND gt.grower_id = $id";
		getName($sql);
		break;
		
	case 'get_event_tree':
		if (!$PRIV['view_event']) {
			forbidden();
			break;
		}
		$grower_id = $_REQUEST['id'];
		$event_id = $_REQUEST['event_id'];
		$data['id'] = 13;
		$data['title'] = 'Event Tree';
		$sql = "SELECT gt.id, h.number, h.pound FROM harvests h, grower_trees gt, events e where e.grower_id = gt.grower_id AND e.id = $event_id  And gt.id = h.tree_id And gt.grower_id = $grower_id And h.event_id = $event_id";
		getName($sql);
		break;
	
	case 'get_event_volunteer_name':
		if (!$PRIV['view_event']) {
			forbidden();
			break;
		}
		$event_id = $_REQUEST['event_id'];
		$data['id'] = 14;
		$data['title'] = 'Volunteer-Name';
		$sql = "SELECT v.id, Concat(v.first_name,' ',v.middle_name,' ',v.last_name), ve.driver, ve.hour FROM volunteers v, events e, volunteer_events ve Where v.id = ve.volunteer_id And e.id = ve.event_id And e.id = $event_id;";
		getName($sql);
		break;
		
	case 'get_distribution_name':
		if (!$PRIV['view_event']) {
			forbidden();
			break;
		}
		$data['id'] = 15;
		$data['title'] = 'Distribution-Name';
		$sql = "SELECT id, name FROM distributions ;";
		getName($sql);
		break;
		
	case 'get_driver':
		if (!$PRIV['view_event']) {
			forbidden();
			break;
		}
		$data['id'] = 16;
		$data['title'] = 'Driver-Name';
		$id = $_REQUEST['id'];
		$sql = "SELECT * FROM drivings Where volunteer_id = $id ;";
		getName($sql);
		break;
		
	case 'update_event':
		if (!$PRIV['edit_event']) {
			forbidden();
			break;
		}
		$data['id'] = 16;
		$data['title'] = 'Update Event';
		$rawData = $_POST;
		$event_id = ($rawData["event_id"]);		


		$event_date = ($rawData["event_date"]);		
		$grower_id = ($rawData["grower_id"]);
		$captain_id = ($rawData["captain_id"]);
		
		if (isset($rawData["event_time"])) 
		 $event_time = ($rawData["event_time"]);		
		else
		 $event_time = "";		
		
		if (isset($rawData["event_notes"])) 
		 $event_notes = ($rawData["event_notes"]);		
		else
		 $event_notes = "";	
		
		$tree_type = array();
		$volunteers = array();
		if (isset($rawData["treeType"]))
		 $tree_type = $rawData["treeType"];
		if (isset($rawData["volunteers"])) 
		 $volunteers = $rawData["volunteers"];
		 
		//print_r($rawData);
		
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
				$dbh->exec ("Update events Set time = '$event_time', notes = '$event_notes',  grower_id = $grower_id, captain_id = $captain_id , date ='$event_date' Where id = $event_id");
		

				for( $i=0; $i< count($tree_type); $i++)
				{					
				  $treeID = $tree_type[$i]["tree_id"];
				  $number = $tree_type[$i]["number"];
				  if ($number =="")				  
				    $number = "null";				  
				  $pound = $tree_type[$i]["pound"];
				  if ($pound =="")				  				  
				    $pound = "null";
				  
				  $dbh->exec ("Insert into harvests(event_id, tree_id, number, pound) Values ($event_id,$treeID, $number, $pound)");
				  
				}
				
				for( $i=0; $i< count($volunteers); $i++)
				{
				  $d =0;	
				  $volunteerID = $volunteers[$i]["volunteer_id"];
				  $hour = $volunteers[$i]["hour"];
				  if ($hour =="")
				   $hour ="null"; 
				  $driver = $volunteers[$i]["driver"];
				  if ( $driver == 'true')
				  {		
					$d =1;	
				    $distributedTree = $volunteers[$i]["distributedTree"];
					for( $j=0; $j< count($distributedTree); $j++)
					{
						$treeID = $distributedTree[$j]["tree_id"];
						$pound =  $distributedTree[$j]["pound"];
						if ($pound == "")
							$pound="null";
						
						$distributionID = $distributedTree[$j]["distribution_id"];
						
						$dbh->exec ("Insert into drivings(event_id, tree_id, volunteer_id, distribution_id, pound) Values ($event_id,$treeID,$volunteerID,$distributionID, $pound)");	
					}
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
		if (!$PRIV['edit_event']) {
			forbidden();
			break;
		}
		$data['id'] = 17;
		$data['title'] = 'Create Event';
		$rawData = $_POST;				

		$event_date = ($rawData["event_date"]);		
		$grower_id = ($rawData["grower_id"]);
		$captain_id = ($rawData["captain_id"]);
		
		if (isset($rawData["event_time"])) 
		 $event_time = ($rawData["event_time"]);		
		else
		 $event_time = "";		
		
		if (isset($rawData["event_notes"])) 
		 $event_notes = ($rawData["event_notes"]);		
		else
		 $event_notes = "";		
		 
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
		 
		$sql = "Insert into events(grower_id, captain_id, date, time, notes) Values ($grower_id, $captain_id,'$event_date','$event_time','$event_notes')";							
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
				  $number = $tree_type[$i]["number"];
				  if ($number =="")				  
				    $number = "null";				  
				  $pound = $tree_type[$i]["pound"];
				  if ($pound =="")				  				  
				    $pound = "null";
				  
				  $dbh->exec ("Insert into harvests(event_id, tree_id, number, pound) Values ($event_id,$treeID, $number, $pound)");
				  
				}
				
				for( $i=0; $i< count($volunteers); $i++)
				{
				  $d =0;	
				  $volunteerID = $volunteers[$i]["volunteer_id"];
				  $hour = $volunteers[$i]["hour"];
				  if ($hour =="")
				   $hour ="null"; 
				  $driver = $volunteers[$i]["driver"];
				  if ( $driver == 'true')
				  {		
					$d =1;	
				    $distributedTree = $volunteers[$i]["distributedTree"];
					for( $j=0; $j< count($distributedTree); $j++)
					{
						$treeID = $distributedTree[$j]["tree_id"];
						$pound =  $distributedTree[$j]["pound"];
						$distributionID = $distributedTree[$j]["distribution_id"];
						$dbh->exec ("Insert into drivings(event_id, tree_id, volunteer_id, distribution_id, pound) Values ($event_id,$treeID,$volunteerID,$distributionID, $pound)");	
					}
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
		if (!$PRIV['del_event']) {
			forbidden();
			break;
		}
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
		if (!$PRIV['edit_donor']) {
			forbidden();
			break;
		}
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
		if (!$PRIV['edit_donor']) {
			forbidden();
			break;
		}
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
		if (!$PRIV['del_donor']) {
			forbidden();
			break;
		}
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
