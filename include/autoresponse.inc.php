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

function eventResponse() {
return<<<EOD
Thank you for registering to harvest with us!   Look for an email from us in the next few days with additional information about the harvest, including the address, time and what to bring.

Please remember to wear long sleeves, long pants, and close-toed shoes.  If you have tools such as ladders, clippers, picker poles, and/or sturdy fruit boxes, please bring them!  They will be put to good use.  If you are a new volunteer, don’t forget to bring a completed Liability Release form (available at http://www.theharvestclub.org/waiver).

Thanks again for volunteering with The Harvest Club!  Happy Harvesting!
EOD;
}

?>