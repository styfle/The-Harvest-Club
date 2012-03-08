<?php

include('include/Database.inc.php');
include('include/Mail.inc.php');
include('include/autoresponse.inc.php');;

	$firstname = $_POST['firstname'];
	$middlename = $_POST['middlename'];
	$lastname = $_POST['lastname'];
	$organization = $_POST['organization'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	$street = $_POST['street'];
	//$street2 = $_POST['street2'];
	$city = $_POST['city'];
	$state = $_POST['state'];
	$zip = $_POST['zip'];
	//$group_number = $_POST['group-number'];
	//$group_age = $_POST['group-age'];
	//$group_availability = $_POST['group-avail'];
	//$group_note = $_POST['group-notes'];
	if(!ISSET($_POST['source']))
		$source = 1; // Default to "Others"
	else
		$source = $_POST['source'];
	$comments = $_POST['comments'];
	$errorMessage = "";
	
	
	if(empty($firstname)) {
		$errorMessage .= "<li>No First Name!</li>";
	}
	if(empty($middlename)) {
		$middlename = "";
	}
	if(empty($lastname)) {
		$errorMessage .= "<li>No Last Name!</li>";
	}
	if(empty($email)) {
		$errorMessage .= "<li>No Email!</li>";
	}
	if(empty($phone)) {
		$errorMessage .= "<li>Phone number required!</li>";
	} else {
		$phone = preg_replace('/\D/', '', $phone); // strip out all chars except numbers
		if (strlen($phone) != 10)
			$errorMessage .= '<li>Phone number must be exactly 10 numbers!</li>';
		else
			$phone = '(' . substr($phone, 0, 3) . ') ' . substr($phone, 3, 3) . '-' . substr($phone, 6, 4); // (949) 555-1234
	}
	if(empty($street)) {
		$street = "";
	}
	if(empty($city)) {
		$errorMessage .= "<li>City required!</li>";
	}
	if(empty($state)) {
		$errorMessage .= "<li>State required!</li>";
	}
	if(empty($zip)) {
		$errorMessage .= "<li>Zip code required!</li>";
	}
	if(!is_numeric($zip) || strlen($zip) != 5) {
		$errorMessage .= "<li>Zip code must be 5 numbers!</li>";
	}
	if(!empty($errorMessage)) {
	  die($errorMessage);
	}


	$r = $db->startTransaction();
	if (!$r->isValid())
	  die('Transaction could not start.');

	 $sql = "INSERT INTO volunteers (first_name, middle_name, last_name, organization, phone, email, street, city, state, zip, notes, source_id, signed_up)
	VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',CURDATE()); ";
	$inputs = array($firstname, $middlename, $lastname, $organization, $phone, $email, $street, $city, $state, $zip, $comments, $source);

	$r = $db->q($sql, $inputs);
	echo($db->error());

	if (!$r->isValid()) {
		echo $db->error();
	}

	$volunteerID = $db->getInsertId();
	
	if(!empty($_POST['roles']))
	{
		foreach($_POST['roles'] as $role) {
			$sql = "INSERT INTO volunteer_roles (volunteer_id, volunteer_type_id) VALUES ('%s', '%s');";
			$inputs = array($volunteerID, $role);

			$r = $db->q($sql, $inputs);
			if (!$r->isValid()) {
				echo $db->error();
				$r = $db->rollback();
				if ($r->isValid())
					echo 'Rollback succeeded!';
				else
					echo 'Rollback failed!';
			}
		}
	}
	
	if(!empty($_POST['days']))
	{
		foreach($_POST['days'] as $day) {
			$sql = "INSERT INTO volunteer_prefers (volunteer_id, day_id) VALUES ('%s', '%s');";
			$inputs = array($volunteerID, $day);
		
			$r = $db->q($sql, $inputs);			
			if (!$r->isValid()) {
				echo $db->error();
				$r = $db->rollback();
				if ($r->isValid())
					echo 'Rollback succeeded!';
				else
					echo 'Rollback failed!';
			}
		}
	}

	$r = $db->commit();
	if ($r->isValid()) {
		$sent = $mail->send('Registration Confirmed', volunteerResponse($firstname, $lastname), $email);
		echo "$firstname $lastname, Thank you for registering!";
		if ($sent)
			echo "<br/>An email has been sent to: $email";
	} else {
		echo 'Failed to commit transaction. Attempting to rollback...';
		$r = $db->rollback();
		if ($r->isValid())
			echo 'Rollback succeeded!';
		else
			echo 'Rollback failed!';
	}

?>
