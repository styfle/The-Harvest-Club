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

if ($cmd == 'get_notifications') {
	$data['title'] = 'Notifications';
	$data['content'] = '';
} else if ($cmd == 'get_volunteers') {
	$data['title'] = 'Volunteers';
	$sql = "SELECT * FROM volunteers;";
	getTable($sql);
} else if ($cmd == 'get_growers') {
	$data['title'] = 'Growers';
	$sql = "SELECT * FROM growers;";
	getTable($sql);
} else if ($cmd == 'send_grower_email') {
	date_default_timezone_set("America/Los_Angeles");
	$data['date'] = date('F d, Y');
	//$data['name'] = getName();
	//$data['address'] = getAddress();
	//$data['city'] = getCity();
	//$data['state'] = getState();
	//$data['zip'] = getZip();
	//$data['fruit'] = getFruit();
} else {
	$data['status'] = 404; // Not found
	$data['message'] = "Unknown ajax command: $cmd";
}

echo json_encode($data);

?>
