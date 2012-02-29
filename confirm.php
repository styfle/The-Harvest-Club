<?php

include('include/config.inc.php');
include('include/Database.inc.php');

if (isset($_REQUEST['eid']) && isset($_REQUEST['vid']) && isset($_REQUEST['email']) && isset($_REQUEST['response'])) {
	//TODO check if email and vid match for security purposes (not the best but better than nothing)
	if ($_REQUEST['response']) {
		$r = $db->q(
			"INSERT INTO volunteer_events (event_id, volunteer_id) VALUES('%s', '%s');",
			array($_REQUEST['eid'], $_REQUEST['vid'])
		);
	} else {
		$r = $db->q(
			"DELETE FROM volunteer_events WHERE event_id='%s' AND volunteer_id='%s';",
			array($_REQUEST['eid'], $_REQUEST['vid'])
		);
	}
	if (!$r->isValid())
		die('Hey buddy, are you trying to submit the same response twice? Because we only need it once.');// . $db->error());
	
	if ($_REQUEST['response'])
		die('Thank you. Your reponse has been updated to: <b>attending</b>.');
	else
		die('Thank you. Your response has been updated to: <b>not attending</b>.');
} else {
	echo 'Oh no! I didn\'t quite understand what you did there.<br/>Please email/call us and let us know what happened.<br/>', MAIL_REPLYTO;
}

?>
