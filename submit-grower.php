<?php

require_once('include/Database.inc.php');

if($_POST['submit'] == "Register as Grower")
{
	$firstname = $_POST['firstname'];
   $lastname = $_POST['lastname'];
   $email = $_POST['email'];
   $phone = $_POST['phone'];
   $street = $_POST['street'];
   $city = $_POST['city'];
   $state = $_POST['state'];
   $zip = $_POST['zip'];
   if(!empty($_POST['property']))
   {
	  $property = $_POST['property'];
   }
   if(!empty($_POST['relationship']))
   {
      $relationship = $_POST['relationship'];
   }
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
   if(!empty($errorMessage))
   {
	  die($errorMessage);
   }

   $sql1 = "INSERT INTO growers (first_name, last_name, phone, email, street, city, state, zip, property_type_id, property_relationship_id) VALUES 
   ('$firstname', '$lastname', '$phone', '$email', '$street', '$city', '$state', '$zip', $property, $relationship);";
   
   if(!mysql_query($sql1))
   {
		die('Could not insert your information: ' .mysql_error());
   } else
   {
		echo("Executed: $sql1 <br>");
   }

	$growerID = mysql_insert_id();

	foreach($_POST['trees'] as $tree) {
		$treeID = $tree['type'];
		$varietal = $tree['varietal'];
		$quantity = $tree['quantity'];
		$height = $tree['height'];
		$months = $tree['month'];
		$chemical = $tree['chemical'];
		if(empty($chemical)) {
			$chemical = 0;
		}
		$sql2 = "INSERT INTO grower_trees (grower_id, tree_id, number, avgHeight_id, chemicaled) VALUES ($growerID, $treeID, $quantity, $height, $chemical);";
		if(!mysql_query($sql2))
		{
			die('Could not insert your information: ' .mysql_error());
		} else
		{
			echo("Executed: $sql2 <br>");
		}
	}


}

?>
