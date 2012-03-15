<?php
// Templates for sending email auto-resonses

$default_phone_number = '(714) 847-8669'; //TODO change to correct number

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

We will notify you of upcoming harvests by email.

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

function invitationEmail($p) {
return<<<EOD
Hello Fellow Harvester!

Another Harvest Event is coming up in $p[city] on $p[date] at $p[time].
We’ll be harvesting $p[fruit] on the property of $p[grower_f] $p[grower_l].

To volunteer for this Harvest, please respond to this email.
If you are a new volunteer, please complete our liability waiver at http://www.theharvestclub.org

We hope to see you there,

$p[me_f] $p[me_l]

EOD;
}

function harvestDetailsEmail($p) {
return<<<EOD
Thank you for registering for our upcoming Harvest Event!

Below are the details for this event:

    Grower Name: $p[grower_f] $p[grower_l]
    Grower Address: $p[street] $p[city] $p[state], $p[zip]
    Date: $p[date]
    Time: $p[time]
    Harvesting: $p[fruit]

Your Harvest Captain is $p[captain_f] $p[captain_l].  You can reach him/her at $p[captain_phone] if you run into any problems on the day of the event.

We advise all harvesters to wear long sleeves and close-toed shoes.  Please bring ladders, clippers, picker poles, and sturdy fruit boxes if you have them.  They will be put to good use!

Thanks again and happy harvesting!

$p[me_f] $p[me_l]

EOD;
}

function reminderEmail($p) {
return<<<EOD
Hello Fellow Harvesters!

You are receiving this email because you have registered to volunteer at a Harvest Event with The Harvest Club!  This is a reminder that the Harvest will take place at $p[time] on $p[date] in the City of $p[city].

Your Harvest Captain is $p[captain_f] $p[captain_l].  You can reach him/her at $p[captain_phone] if you run into any problems on the day of the event.  

Please bring sturdy fruit boxes and tools if available and don't forget to wear long sleeves and close-toed shoes.

We look forward to seeing you soon!

$p[me_f] $p[me_l]

EOD;
}

?>
