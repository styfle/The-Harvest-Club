<?php

include('include/Database.inc.php');

   $firstname = $_POST['firstname'];
   $middlename = $_POST['middlename'];
   $lastname = $_POST['lastname'];
   $organization = $_POST['organization'];
   $email = $_POST['email'];
   $phone = $_POST['phone'];
   $street = $_POST['street'];
   $street2 = $_POST['street2'];
   $city = $_POST['city'];
   $state = $_POST['state'];
   $zip = $_POST['zip'];
   //$group_number = $_POST['group-number'];
   //$group_age = $_POST['group-age'];
   //$group_availability = $_POST['group-avail'];
   //$group_note = $_POST['group-notes'];
   if(empty($_POST['source']))
   {
    $source = 7; // Default to "Others";
   }
   else
	$source = $_POST['source'];
   $comments = $_POST['comments'];
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
   if(!empty($errorMessage))
   {
	  die($errorMessage);
   }

/*
   $sql = "INSERT INTO volunteers (first_name, last_name, phone, email, active, street, city, state, zip, privilege_id) VALUES 
   ('$firstname', '$lastname', '$phone', '$email', '1', '$street', '$city', '$state', '$zip', '1')";
   
   if(!mysql_query($sql))
   {
		die('Could not insert your information: ' .mysql_error());
   }
*/

	$sql = "INSERT INTO volunteers (first_name, middle_name, last_name, organization, phone, email, street, city, state, zip, notes, source_id, signed_up) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', CURDATE());";
	$inputs = array($firstname, $middlename, $lastname, $organization, $phone, $email, $street, $city, $state, $zip, $comments, $source);

	$r = $db->q($sql, $inputs);

	if (!$r->isValid()) {
		echo $db->error();
	}

	$volunteerID = mysql_insert_id();
	
	if(!empty($_POST['roles']))
	{
		foreach($_POST['roles'] as $role) {
			$tableinfo = "volunteer_roles (volunteer_id, volunteer_type_id)";
			$valueinfo = "$volunteerID, $role";

<<<<<<< HEAD
	foreach($_POST['roles'] as $role) {
		$sql = "INSERT INTO volunteer_roles (volunteer_id, volunteer_type_id) VALUES ('%s', '%s');";
		$inputs = array($volunteerID, $role);

		$r = $db->q($sql, $inputs);
=======
			$r = $db->q($sql, array($tableinfo, $valueinfo));
>>>>>>> 941e866f7e65e9ae3d5a71bf0374e8cc90c7f61c

			if (!$r->isValid()) {
				echo $db->error();
			}
		}
	}
<<<<<<< HEAD

	foreach($_POST['days'] as $day) {
		$sql = "INSERT INTO volunteer_prefers (volunteer_id, day_id) VALUES ('%s', '%s');";
		$inputs = array($volunteerID, $day);
	
		$r = $db->q($sql, $inputs);
=======
	
	if(!empty($_POST['days']))
	{
		foreach($_POST['days'] as $day) {
			$tableinfo = "volunteer_prefers (volunteer_id, day_id)";
			$valueinfo = "$volunteerID, $day";
>>>>>>> 941e866f7e65e9ae3d5a71bf0374e8cc90c7f61c
		
			$r = $db->q($sql, array($tableinfo, $valueinfo));
			
			if (!$r->isValid()) {
				echo $db->error();
			}
		}
	}
?>
