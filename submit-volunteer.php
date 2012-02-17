<?php

require_once('include/Database.inc.php');

if($_POST['Submit'] == "Register as Volunteer")
{
   $firstname = $_POST['firstname'];
   $lastname = $_POST['lastname'];
   $organization = $_POST['organization'];
   $email = $_POST['email'];
   $phone = $_POST['phone'];
   $street = $_POST['street'];
   $street2 = $_POST['street2'];
   $city = $_POST['city'];
   $state = $_POST['state'];
   $zip = $_POST['zip'];
   if(!empty($_POST['Role']))
   {
	  $roles = $_POST['Role'];
   }
   if(!empty($_POST['Day']))
   {
      $days = $_POST['Day'];
   }
   $group_number = $_POST['group-number'];
   $group_age = $_POST['group-age'];
   $group_availability = $_POST['group-avail'];
   $group_note = $_POST['group-notes'];
   if(!empty($_POST['heardby']))
   {
      $heardby = $_POST['heardby'];
   }
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

   $sql = "INSERT INTO volunteers (first_name, last_name, phone, email, active, street, city, state, zip, privilege_id) VALUES 
   ('$firstname', '$lastname', '$phone', '$email', '1', '$street', '$city', '$state', '$zip', '1')";
   
   if(!mysql_query($sql))
   {
		die('Could not insert your information: ' .mysql_error());
   }
   

}

?>