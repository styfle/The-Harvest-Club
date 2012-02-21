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
   $source = $_POST['source'];
   $notes = $_POST['notes'];
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
   if(!empty($errorMessage))
   {
	  die($errorMessage);
   }

   $sql = "INSERT INTO %s VALUES (%s);  "; 
   
   $tableinfo = "growers (first_name, middle_name, last_name, phone, email, preferred, street, city, state, zip, tools, property_type_id, property_relationship_id, source, notes)";
   $valueinfo = "'$firstname', '$middlename', '$lastname', '$phone', '$email', '$preferred', '$street', '$city', '$state', '$zip', '$tools', $property, $relationship, $source, '$notes'";
   
   $r = $db->q($sql, array($tableinfo, $valueinfo));
  
   if ($r->isValid()) {
	  echo $db->error();
   }

   $growerID = mysql_insert_id();

	foreach($_POST['trees'] as $tree) {
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
		$months = $tree['month'];
		$chemical = $tree['chemical'];
		if(empty($chemical)) {
			$chemical = 0;
		}

		$tableinfo = "grower_trees (grower_id, tree_type, varietal, number, avgHeight_id, chemicaled)";
		$valueinfo = "$growerID, $type, '$varietal', $quantity, $height, $chemical";

		if(!empty($errorMessage))
		{
			die($errorMessage);
		}

		$r = $db->q($sql, array($tableinfo, $valueinfo));
	
		if ($r->isValid()) {
			echo $db->error();
		}

		$treeID = mysql_insert_id();

		foreach($months as $month) {
			$tableinfo = "month_harvests (tree_id, month_id)";
			$valueinfo = "$treeID, $month";

			$r = $db->q($sql, array($tableinfo, $valueinfo));

			if ($r->isValid()) {
				echo $db->error();
			}
		}
	}

?>
