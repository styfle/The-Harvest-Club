<?php

include('include/Database.inc.php');


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
    if(!ISSET($_POST['source']))
    {
     $source = 1; // Default to "Others";
    }
    else
 	 $source = $_POST['source'];
	$notes = $_POST['notes'];
	$trees = $_POST['trees'];

	$errorMessage = "";
	
	if(empty($firstname)) {
		$errorMessage .= "<li>No First Name!</li>";
	}
	if(empty($lastname)) {
		$errorMessage .= "<li>No Last Name!</li>";
	}
	if(empty($email)) {
		$errorMessage .= "<li>No Email!</li>";
	}
	if(empty($phone)) {
		$errorMessage .= "<li>No Phone!</li>";
	}
	if(!is_numeric($phone)) {
	  $errorMessage .= "<li>Phone Number is Non-Numeric!</li>";
	}
	if(empty($preferred)) {
	  $errorMessage .= "<li>No Preferred Contact!</li>";
	}
	if(empty($street)) {
		$errorMessage .= "<li>No Street!</li>";
	}
	if(empty($city)) {
		$errorMessage .= "<li>No City!</li>";
	}
	if(empty($state)) {
		$errorMessage .= "<li>No State!</li>";
	}
	if(empty($zip)) {
		$errorMessage .= "<li>No Zip!</li>";
	}
	if(!is_numeric($zip)) {
		$errorMessage .= "<li>Zip is Non-Numeric!</li>";
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
		if(empty($quantity)) {
			$errorMessage .= "<li>No Quantity for Trees!</li>";
		}
		if(!is_numeric($quantity)) {
			$errorMessage .= "<li>Tree Quantity must be Numeric!</li>";
		}
		if($quantity < 1) {
			$errorMessage .= "<li>Tree Quantity must be at least 1!</li>";
		}
		$height = $tree['height'];
		if(empty($height)) {
			$errorMessage .= "<li>No Tree Height!</li>";
		}
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

		if(ISSET($tree['month'])) {
			$months = $tree['month'];
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
	}


	
	$r = $db->commit();
	if ($r->isValid())
		echo "$firstname $lastname, Thank you for registering!";
	else {
		echo 'Failed to commit transaction. Attempting to rollback...';
		$r = $db->rollback();
		if ($r->isValid())
			echo 'Rollback succeeded!';
		else
			echo 'Rollback failed!';
	}

?>
