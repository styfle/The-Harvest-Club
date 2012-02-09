<?php

require_once('include/Database.inc.php');

//echo 'CMD: ' . $_REQUEST['cmd'];

$cmd = $_REQUEST['cmd'];
$data = array('status'=>200);

if ($cmd == 'page1') {
	$data['title'] = 'Page 1';
	$data['content'] = 'Welcome to America!';
} else if ($cmd == 'page2') {
	$data['title'] = 'Page 2';
	$data['content'] = 'Now leaving America!';
	
	
	
} else if ($cmd == 'get_grower_email') {
	date_default_timezone_set("America/Los_Angeles");
	$data['date'] = date('F d, Y');
	//$data['name'] = getName();
	//$data['address'] = getAddress();
	//$data['city'] = getCity();
	//$data['state'] = getState();
	//$data['zip'] = getZip();
	//$data['fruit'] = getFruit();
} else {
	$data['status'] = 404;
	$data['error'] = "Unknown ajax command: $cmd";
}

echo json_encode($data);

?>