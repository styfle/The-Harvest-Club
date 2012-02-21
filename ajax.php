<?php

require_once('include/Database.inc.php');
require_once('include/Mail.inc.php');
// TODO add authentication

header('Content-type: application/json');

$cmd = $_REQUEST['cmd'];
$data = array('status'=>200); // default to OK
// See http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html

function contains($haystack, $needle) {
	return stripos($haystack, $needle) !== false;
}

function updateVolunteer($exists) {
	global $db;
	global $data;
	$id = $_REQUEST['id'];						
	$firstName = $_REQUEST['firstname'];		
	$middleName = $_REQUEST['middlename'];
	$lastName = $_REQUEST['lastname'];
	$phone = $_REQUEST['phone'];
	$email = $_REQUEST['email'];
	$street = $_REQUEST['street'];
	$city = $_REQUEST['city'];
	$state = $_REQUEST['state'];
	$zip = $_REQUEST['zip'];
	$note =  $_REQUEST['note'];
	$priv_id = $_REQUEST['privilege_id'];
	
	if ($exists) { // volunteer exists so just update
		$sql = "Update volunteers Set first_name='".$firstName."', middle_name='".$middleName."',last_name='".$lastName."', phone='".$phone."', email='".$email."', street='".$street."', city='".$city."', state='".$state."' ,zip='".$zip."', notes='".$note."' where id=".$id;						
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
		
		// TODO: Check if priv changed
		$sql = "SELECT p.id, p.name, v.password, v.first_name, v.last_name FROM volunteers v LEFT JOIN privileges p ON v.privilege_id = p.id WHERE v.id = $id";
		$r = $db->q($sql);
		$row = $r->getRow();
		
		if ($priv_id != $row[0]) { // privs have changed
			$sql = "UPDATE volunteers SET privilege_id=$priv_id"; // new priv
			$message = "$firstName $lastName,\r\nYour privileges have changed. You are now a $privName!\r\n";
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
		$sql = "INSERT INTO volunteers (first_name, middle_name, last_name, phone, email, active, street, city, state, zip, privilege_id) VALUES
		('$firstName', '$middleName', '$lastName', '$phone', '$email', '1', '$street', '$city', '$state', '$zip', '1')";
		$r = $db->q($sql);
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
		$data['datatable']['aoColumns'][] = array('sTitle'=>'Oh no');
		$data['datatable']['aaData'][] = array('No results found.');
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
		if ($k == 'id' || $k == 'password') {
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
		$sql = "SELECT g.*, pt.name AS property_type, pr.name AS property_relationship FROM growers g, property_types pt, property_relationships pr WHERE g.id = pt.id AND g.id = pr.id;";
		getTable($sql);
		break;
	case 'get_trees':
		$data['id'] = 3;
		$data['title'] = 'Trees';
		$sql = "SELECT g.id, g.last_name AS grower_lname, tt.name AS tree, gt.varietal, mh.month_id, gt.number, gt.notes, gt.chemicaled FROM grower_trees gt, month_harvests mh, tree_types tt, growers g WHERE g.id = gt.grower_id AND gt.grower_id = mh.grower_id AND gt.tree_id = mh.tree_type_id AND gt.varietal = mh.varietal AND gt.tree_id=tt.id;";
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
		$note =  $_REQUEST['note'];
		$sql = "Update distributions Set name='".$name."', phone='".$phone."', email='".$email."', street='".$street."', city='".$city."', state='".$state."',zip='".$zip."', notes='".$note."' where id=".$id;				
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
		
		$source = $_REQUEST['source'];
		$notes =  $_REQUEST['notes'];
		
		
		$property_type= $_REQUEST['property_type'];
		$property_relationship = $_REQUEST['property_relationship'];		
		$sql = "Update growers Set first_name='".$firstname."', middle_name ='".$middlename."', last_name='".$lastname."', phone='".$phone."', email='".$email."', street='".$street."', city='".$city."', state='".$state."',zip='".$zip."', tools='".$tools."', source='".$source."', notes='".$notes."', property_type_id ='".$property_type."', property_relationship_id ='".$property_relationship."' where id=".$id;				
		$r = $db->q($sql);
		break;
	case 'update_volunteer':
		updateVolunteer(true);
		break;
	case 'add_volunteer':
		updateVolunteer(false);
		break;
	case 'send_grower_email':
		date_default_timezone_set("America/Los_Angeles");
		$data['date'] = date('F d, Y');
		//$data['name'] = getName();
		//$data['address'] = getAddress();
		//$data['city'] = getCity();
		//$data['state'] = getState();
		//$data['zip'] = getZip();
		//$data['fruit'] = getFruit();
		break;
	default:
		$data['status'] = 404; // Not found
		$data['message'] = "Unknown ajax command: $cmd";
}
echo json_encode($data);

?>
