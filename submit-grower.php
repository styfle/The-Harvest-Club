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
   if(empty($city)) {
      $errorMessage .= "<li>No City!</li>";
   }
   if(empty($email)) {
      $errorMessage .= "<li>No Email!</li>";
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

	foreach($_POST['trees'] as $tree) {
		$quantity = $tree['quantity'];
		$height = $tree['height'];
		$chemical = $tree['chemical'];
		$sql2 = "INSERT INTO grower_trees (grower_id, tree_id, number, avgHeight_id, chemicaled) VALUES (grower_id, tree_id, $quantity, $height, $chemical)";
		echo $sql2;
		echo "<br>";
	}

   $sql1 = "INSERT INTO growers (first_name, last_name, phone, email, street, city, state, zip, property_type_id, property_relationship_id) VALUES 
   ('$firstname', '$lastname', '$phone', '$email', '$street', '$city', '$state', '$zip', $property, $relationship)";
   
   if(!mysql_query($sql1))
   {
		die('Could not insert your information: ' .mysql_error());
   }

	echo "<br>";
	echo $sql1;

}

?>
