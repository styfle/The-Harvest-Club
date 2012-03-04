<?php

include('include/Database.inc.php');
include('include/Mail.inc.php');
include('include/autoresponse.inc.php');


	$firstname = $_POST['firstname'];
	$middlename = $_POST['middlename'];
	$lastname = $_POST['lastname'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	$preferred = $_POST['prefer'];
	$street = $_POST['street'];
	$city = $_POST['city'];
	$state = $_POST['state'];
	$zip = $_POST['zip'];
	$property = $_POST['property'];
	$relationship = $_POST['relationship'];
	$tools = $_POST['tools'];
	if (!isset($_POST['source']))
		$source = 1; // Default to "Others"
	else
		$source = $_POST['source'];
	$notes = $_POST['notes'];
	$trees = $_POST['trees'];

	$errorMessage = "";
	
	if(empty($firstname)) {
		$errorMessage .= "<li>First Name required!</li>";
	}
	if(empty($lastname)) {
		$errorMessage .= "<li>No Last Name required!</li>";
	}
	if(empty($email)) {
		$email = ''; // no longer required
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
	if(empty($preferred)) {
	  $errorMessage .= "<li>No Preferred Contact!</li>";
	}
	if(empty($street)) {
		$errorMessage .= "<li>Street required!</li>";
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
	if(empty($property)) {
	  $errorMessage .= "<li>No Property Type!</li>";
	}
	if(empty($relationship)) {
	  $errorMessage .= "<li>No Property Relationship!</li>";
	}
	if(count($trees)==0)
		$errorMessage .= '<li>No trees. Grower must register one or more trees!</li>';
	if(!empty($errorMessage))
	{
	  die($errorMessage);
	}

	$r = $db->startTransaction();
	if (!$r->isValid())
	  die('Transaction could not start.');


	$sql = "INSERT INTO growers (first_name, middle_name, last_name, phone, email, preferred, street, city, state, zip, tools, property_type_id, property_relationship_id, source_id, notes)
	VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s');";
	$inputs = array($firstname, $middlename, $lastname, $phone, $email, $preferred, $street, $city, $state, $zip, $tools, $property, $relationship, $source, $notes);
	
	$r = $db->q($sql, $inputs);
  
	if (!$r->isValid()) {
	  die($db->error());
	}


	$errorMessage = '';
	$growerID = $db->getInsertId();

	foreach($trees as $tree) {
		$type = $tree['type'];
		if(empty($type)) {
			$errorMessage .= "<li>No Tree Type!</li>";
		}
		$varietal = $tree['varietal'];
		$quantity = $tree['quantity'];
		if(empty($quantity) || !is_numeric($quantity) || $quantity < 1) {
			$quantity = 1; //optional so default to 1
		}
		$height = $tree['height'];
		if(empty($height)) {
			$height = 1; //optional so default to 1
		}
		$months = $tree['month'];
		$chemical = $tree['chemical'];
		if(empty($chemical)) {
			$chemical = 0;
		}


		if(!empty($errorMessage)) {
			die($errorMessage);
		}

		$sql = 'INSERT INTO grower_trees (grower_id, tree_type, varietal, number, avgHeight_id, chemicaled) VALUES';
		$sql .= "('%s','%s','%s','%s','%s','%s');";
		$inputs = array($growerID, $type, $varietal, $quantity, $height, $chemical);

		$r = $db->q($sql, $inputs);
	
		if (!$r->isValid()) {
			echo 'DB error while inserting trees: ' . $db->error() .'<br/>Attempting to rollback...';
			$r = $db->rollback();
			if ($r->isValid())
				echo 'Rollback succeeded!';
			else
				echo 'Rollback failed!';
		}

		$treeID= $db->getInsertId();

		foreach($months as $month) {
			$sql = "INSERT INTO month_harvests (tree_id, month_id) VALUES ('%s','%s');";

			$r = $db->q($sql, array($treeID, $month));

			if (!$r->isValid()) {
				echo 'DB error while inserting harvest months: ' . $db->error() . '<br/>Attempting to rollback...';
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
		if (empty($email)) // if no email supplied then we don't try to send mail
			$sent = false;
		else
			$sent = $mail->send('Registration Confirmed', growerResponse($firstname, $lastname), $email);
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
