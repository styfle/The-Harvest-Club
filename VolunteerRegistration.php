<?php

require_once('include/Database.inc.php');

$r = $db->q("SELECT * FROM sources;");
if(!$r->isValid())
	die("MySQL Error: " . $db->error());
$sources = $r->buildArray();


?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>The Harvest Club - Volunteer Registration</title>
    
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script>
		var sources = <?php echo json_encode($sources); ?>;
	</script>

	
    <!--[if IE]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body><div id="main">
    <h1>The Harvest Club</h1>
    <p>Volunteering with The Harvest Club is a fun way to make a difference and make friends in the community. This form provides us with the essential contact information to match you with events. </p>
    <p>Please note that his page will not sign you up to volunteer for a specific event; after submitting this form, remember to visit the Events page and contact the organizer of an event you'd like to participate in.</p>
    <p>Privacy: Information entered here is used solely by The Harvest Club; we do not share, sell, or otherwise distribute your personal information.</p>
    <h2>Volunteer Registration Form</h2>
    <p>Please complete the form. * Indicates required fields.</p>
    <form method="post" action="" id="volunteer">
		<fieldset>
    		<legend>Name</legend>
    		<label for="firstname">First*:</label>
    		<input id="firstname" name="firstname" type="text" placeholder="Peter" required="required" />
    		<label for="lastname">Last*:</label>
    		<input id="lastname" name="lastname" type="text" placeholder="Anteater" required="required" />
    		<br/>
    		<label for="organization">Organization:</label>
    		<input id="organization" name="organization" type="text" size="40" placeholder="Donald Bren School of ICS" />
		</fieldset>
		<fieldset>
    		<legend>Contact</legend>
    		<label for="email">Email*:</label><input id="email" type="email" placeholder="peter@uci.edu" required="required" />
    		<label for="phone">Phone*:</label><input id="phone" type="tel" name="phone" placeholder="9495551234" pattern="[0-9]{10}" required="required"/>
    	</fieldset>
    	<fieldset>
    		<legend>Address</legend>
			<div>
				<label for="street">Street Address:</label>
				<input id="street" type="text" name="street" size="40" placeholder="67 E Peltason Dr" />
			</div>
			<div>
				<label for="street">Address Line 2:</label>
				<input id="street2" type="text" name="street2" size="40" placeholder="" />
			</div>
			<div>
				<label for="city">City*:</label>
				<input type="text" name="city" id="city" placeholder="Irvine"  size="10" required="required" />
				<label for="state">State:</label>
				<select id="state" name="state" required="required"> 
					<option value="" disabled="disabled">Select...</option><!-- TODO: Force option -->
					<option value="AL">Alabama</option> 
					<option value="AK">Alaska</option> 
					<option value="AZ">Arizona</option> 
					<option value="AR">Arkansas</option> 
					<option value="CA">California</option> 
					<option value="CO">Colorado</option> 
					<option value="CT">Connecticut</option> 
					<option value="DE">Delaware</option> 
					<option value="DC">D.C.</option> 
					<option value="FL">Florida</option> 
					<option value="GA">Georgia</option> 
					<option value="HI">Hawaii</option> 
					<option value="ID">Idaho</option> 
					<option value="IL">Illinois</option> 
					<option value="IN">Indiana</option> 
					<option value="IA">Iowa</option> 
					<option value="KS">Kansas</option> 
					<option value="KY">Kentucky</option> 
					<option value="LA">Louisiana</option> 
					<option value="ME">Maine</option> 
					<option value="MD">Maryland</option> 
					<option value="MA">Massachusetts</option> 
					<option value="MI">Michigan</option> 
					<option value="MN">Minnesota</option> 
					<option value="MS">Mississippi</option> 
					<option value="MO">Missouri</option> 
					<option value="MT">Montana</option> 
					<option value="NE">Nebraska</option> 
					<option value="NV">Nevada</option> 
					<option value="NH">New Hampshire</option> 
					<option value="NJ">New Jersey</option> 
					<option value="NM">New Mexico</option> 
					<option value="NY">New York</option> 
					<option value="NC">North Carolina</option> 
					<option value="ND">North Dakota</option> 
					<option value="OH">Ohio</option> 
					<option value="OK">Oklahoma</option> 
					<option value="OR">Oregon</option> 
					<option value="PA">Pennsylvania</option> 
					<option value="RI">Rhode Island</option> 
					<option value="SC">South Carolina</option> 
					<option value="SD">South Dakota</option> 
					<option value="TN">Tennessee</option> 
					<option value="TX">Texas</option> 
					<option value="UT">Utah</option> 
					<option value="VT">Vermont</option> 
					<option value="VA">Virginia</option> 
					<option value="WA">Washington</option> 
					<option value="WV">West Virginia</option> 
					<option value="WI">Wisconsin</option> 
					<option value="WY">Wyoming</option>
				</select>
				<label for="zip">Zip*:</label><input type="text" name="zip" id="zip" placeholder="92617" size="4" pattern="[0-9]{5}" required="required" />
			</div>
		</fieldset> 

    	<h3>Volunteer Interests</h3>
		<fieldset>
    		<legend>Volunteer Role</legend>
			<p><i>Choose all that applies.</i></p>
			<span>
			<input name="harvester" type="checkbox" id="harvester" value="harvester"><label for="harvester">Harvester</label>
			 - volunteers at harvesting events
			</span>
			<br />
			<span>
			<input name="harvestcaptain" type="checkbox" id="havestcaptain" value="harvestcaptain"><label for="harvestcaptain">Harvest Captain</label>
			 - leads a harvest crew
			</span>
			<br />
			<input name="driver" type="checkbox" id="driver" value="driver"><label for="driver">Driver</label>
			- transports donated food to local food pantries
			<br />
			<span>
			<input name="ambassador" type="checkbox" id="ambassador" value="ambassador"><label for="ambassador">Ambassador</label>
			- canvasses neighborhoods and hands out leaflets to homes with visible fruit trees
			</span>
			
    	</fieldset>
		<fieldset>
    		<legend>Preffered Days to Volunteer</legend>
			<p><i>Note: Harvest Events usually takes two hours long and generally take place over the weekends.</i></p>
			<span title="Monday">
			<input name="monday" type="checkbox" id="monday" value="monday"><label for="monday">Mon</label>
			</span>
			&nbsp;
			<span title="Tuesday">
			<input name="tuesday" type="checkbox" id="tuesday" value="tuesday"><label for="tuesday">Tue</label>
			</span>
			&nbsp;
			<span title="Wednesday">
			<input name="wednesday" type="checkbox" id="wednesday" value="wednesday"><label for="wednesday">Wed</label>
			</span>
			&nbsp;
			<span title="Thursday">
			<input name="thursday" type="checkbox" id="thursday" value="thursday"><label for="thursday">Thu</label>
			</span>
			&nbsp;
			<span title="Friday">
			<input name="friday" type="checkbox" id="friday" value="friday"><label for="friday">Fri</label>
			</span>
			&nbsp;
			<span title="Saturday">
			<input name="saturday" type="checkbox" id="saturday" value="saturday"><label for="saturday">Sat</label>
			</span>
			&nbsp;
			<span title="Sunday">
			<input name="sunday" type="checkbox" id="sunday" value="sunday"><label for="sunday">Sun</label>
			</span>
			&nbsp;
			
    	</fieldset>

		<h3>For Groups</h3>
		<fieldset>
			<legend>Group Registration</legend>
			<label for="group-number">How many people?</label>
			<input id="group-number" type="number" pattern="[0-9]{10}" min="0" max="99" placeholder="10"/>
			<br />
			<label for="group-age">What is the age range?</label>
			<input id="group-age" name="group-age" type="text" placeholder="18-23" size="9"/>
			<br />
			<label for="group-avail">When is the group available to harvest?</label>
			<input id="group-avail" name="group-avail" type="text" placeholder="Monday-Sunday" size="15"/>
			<br />
			<label>Any special requirements or dates for harvesting?</label><br/>
				<textarea name="group-notes" type="textarea" cols="50" rows="3" placeholder=""></textarea>
			
		</fieldset>
		
    	<h3>Misc Information</h3>

		<fieldset>
			<legend>Optional</legend>
			<label for="hearby">How did you first hear about The Harvest Club?</label>
			<select id="heardby" name="heardby">
					<option value="" disabled="disabled" selected="selected">Select...</option>
  					<option value="flyer">Flyer</option>
  					<option value="facebook">Facebook</option>
					<option value="twitter">Twitter</option>
					<option value="family">Family or Friend</option>
					<option value="newspaper">Newspaper/Local Magazine</option>
					<option value="website">Website/Search Engine</option>
					<option value="village">Village Harvest</option>
					<option value="other">Other</option>
			</select>
			<br />
			<label>Additional comments:</label><br/>
				<textarea name="comments" type="textarea" cols="50" rows="3" placeholder=""></textarea>
		</fieldset>
				
		<br />
		<br />
		<fieldset>
			<legend>Register</legend>
					<div><input value="Register as Volunteer" type="submit" id = "submit" disabled="disabled"></div>
		</fieldset>			
    </form>
</div></body>
</html>