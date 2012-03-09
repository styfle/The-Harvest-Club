<?php
// Templates for sending email auto-resonses

$default_phone_number = '949-555-1234'; //TODO change to correct number

function growerResponse($first, $last) {
return<<<EOD
Hello $first $last,

Thank you for registering with The Harvest Club!

By sharing your harvest with us, you are strengthening our community by providing much needed fresh fruit to our neighbors in need. The Harvest Club could not exist without your generosity!

We will contact you during the harvest months you have listed to arrange for a harvest event.

If you do not hear from us, please call us at $default_phone_number or send an email to theharvestclub@gmail.com.

Thank you again, and welcome to The Harvest Club!
EOD;
}

function volunteerResponse($first, $last) {
return<<<EOD
Hello $first $last,

Thank you for registering with The Harvest Club!

With your help, we will harvest fresh, healthy fruits and vegetables for the underserved residents of Orange County.

We will notify you of upcoming harvest by email.  To sign up for an event, simply register on the Harvest Club calendar found at http://www.theharvestclub.org.  Or, send an email to theharvestclub@gmail.com.

Thank you again, and welcome to The Harvest Club!
EOD;
}

/*
function eventResponse() {
return<<<EOD
Thank you for registering to harvest with us!   Look for an email from us in the next few days with additional information about the harvest, including the address, time and what to bring.

Please remember to wear long sleeves, long pants, and close-toed shoes.  If you have tools such as ladders, clippers, picker poles, and/or sturdy fruit boxes, please bring them!  They will be put to good use.  If you are a new volunteer, don’t forget to bring a completed Liability Release form (available at http://www.theharvestclub.org/waiver).

Thanks again for volunteering with The Harvest Club!  Happy Harvesting!
EOD;
}
*/

function invitationEmail($city, $dateStr, $time, $fruit, $grower_f, $grower_l, $me_f, $me_l)) {
	$a = explode('-', $dateStr); // split
	$day_date = date("l F j, Y", mktime(0, 0, 0, $a[1], $a[2], $a[0]));
return<<<EOD
Hello Fellow Harvesters!
Another Harvest Event is coming up in $city on $day_date at $time.

We’ll be harvesting $fruit on the property of $grower_f $grower_l.

To volunteer for this Harvest, please respond to this email.

If you are a new volunteer, please click here to register as a harvester with The Harvest Club and please complete a liability waiver here.

We hope to see you at the harvest!

$me_f $me_l

EOD;
}

function harvestDetailsEmail($vol_f, $vol_l, $street, $city, $state, $zip, $dateStr, $time, $fruit, $grower_f, $grower_l, $captain_f, $captain_l, $captain_phone, $me_f, $me_l)) {
	$a = explode('-', $dateStr); // split
	$day_date = date("l F j, Y", mktime(0, 0, 0, $a[1], $a[2], $a[0]));
return<<<EOD
$vol_f $vol_l,
	
Thank you for registering for our upcoming Harvest Event!
Below are the details for this event:

	Grower Name: $grower_f $grower_l
	Grower Address: $street $city $state, $zip
	Date: $day_date
	Time: $time
	Harvesting:
	Parking:

Your Harvest Captain is $captain_f $captain_l.  You can reach him/her at $captain_phone if you run into any problems on the day of the event.

We advise all harvesters to wear long sleeves and close-toed shoes.  Please bring ladders, clippers, picker poles, and sturdy fruit boxes if you have them.  They will be put to good use!

Thanks again and happy harvesting!

$first $last

EOD;
}
function reminderEmail($vol_f, $vol_l, $street, $city, $state, $zip, $dateStr, $time, $fruit, $grower_f, $grower_l, $captain_f, $captain_l, $captain_phone, $me_f, $me_l)) {
	$a = explode('-', $dateStr); // split
	$day_date = date("l F j, Y", mktime(0, 0, 0, $a[1], $a[2], $a[0]));
return<<<EOD
$vol_f $vol_l,

You are receiving this email because you have registered to volunteer at a Harvest Event with The Harvest Club!  This is a reminder that the Harvest will take place at $time on $day_date in the City of $city.

Your Harvest Captain is $captain_f $captain_l.  You can reach him/her at $captain_phone if you run into any problems on the day of the event.  

Please bring sturdy fruit boxes and tools if available and don't forget to wear long sleeves and close-toed shoes.

We look forward to seeing you soon!

$first $last

EOD;
}

?>
