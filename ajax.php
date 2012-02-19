<?php

require_once('include/Database.inc.php');
// TODO add authentication

header('Content-type: application/json');

$cmd = $_REQUEST['cmd'];
$data = array('status'=>200); // default to OK
// See http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html

function getError($r) {
	global $data;

	if ($r->isValid())
		return 0; // 0 is success

	$data['status'] = 999;
	$data['message'] = 'DB: ' . $e; // show error message
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

switch ($cmd)
{
	case 'get_notifications':
		$data['title'] = 'Notifications';
		$data['content'] = '';		
		break;
	case 'get_volunteers':
		$data['title'] = 'Volunteers';
		$sql = "SELECT * FROM volunteers;";
		getTable($sql);
		break;
	case 'get_growers':
		$data['title'] = 'Growers';
		$sql = "SELECT * FROM growers;";
		getTable($sql);
		break;
	case 'get_distribs':
		$data['title'] = 'Distributions';
		$sql = "SELECT * FROM distributions d;";
		getTable($sql);
		// echo $data['datatable']['aaData'][2][1];
		break;
	case 'get_distribution_times':
		$id = $_REQUEST['id'];
		$data['title'] = 'Hours';
		$sql = "SELECT h.* FROM distributions d, distribution_hours h Where h.distribution_id = d.id And d.id=".$id;				
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
		
		for ($i=1; $i<8 ; $i++ )
		{
			$temp1 = 'oh'.$i;			
			$$temp1 = $_REQUEST[$temp1];		
			$temp2 = 'om'.$i;
			$$temp2 = $_REQUEST[$temp2];
			$temp3 = 'ch'.$i;
			$$temp3 = $_REQUEST[$temp3];
			$temp4 = 'cm'.$i;
			$$temp4 = $_REQUEST[$temp4];		
			
			$sql = "Select * From distribution_hours where distribution_id= ".$id." And day_id = ".$i;			
			$r = $db->q($sql);
			if($r->hasRows())
			{
				if (($$temp1!='') && ($$temp2!='') && ($$temp3!='') && ($$temp4!=''))
				 {
				  $sql = "Update distribution_hours Set open='".$$temp1.":".$$temp2."', close='".$$temp3.":".$$temp4."' Where distribution_id=".$id." And day_id=".$i;						   
				  $db->q($sql);
				 }
				if (($$temp1=='') && ($$temp2=='') && ($$temp3=='') && ($$temp4=='')) 
				{
				  $sql = "Delete From distribution_hours Where distribution_id=".$id." And day_id=".$i;						   
				  $db->q($sql);
				}
			}
			else			
			 if (($$temp1!='') && ($$temp2!='') && ($$temp3!='') && ($$temp4!=''))
			 {
			   $sql = "Insert into distribution_hours(distribution_id, day_id, open, close) values (".$id.",".$i.",'".$$temp1.":".$$temp2."','".$$temp3.":".$$temp4."')";						   
			   $db->q($sql);
			 }
		
		}
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
