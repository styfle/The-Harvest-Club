<?php

require_once('include/Database.inc.php');

$r = $db->q("SELECT * FROM sources;");
if(!$r->isValid())
	die("MySQL Error: " . $db->error());
$sources = $r->buildArray();

$r = $db->q("SELECT * FROM days");
if(!$r->isValid())
	die("MySQL Error: " . $db->error());
$days = $r->buildArray();

$r = $db->q("SELECT * FROM volunteer_types;");
if(!$r->isValid())
	die("MySQL Error: " . $db->error());
$roles = $r->buildArray();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Volunteer Registration</title>
	<script src="js/jquery-1.7.1.min.js"></script>
	<script>
		var sources = <?php echo json_encode($sources); ?>;
		var days = <?php echo json_encode($days); ?>;
		var roles = <?php echo json_encode($roles); ?>;
		var optionSelect = '<option value="" disabled="disabled" selected="selected">Select...</option>';
	</script>
</head>

<body>
<div id="main" role="main">
   	<!--
   	<h1>The Harvest Club</h1>
    <p>Volunteering with The Harvest Club is a fun way to make a difference and make friends in the community. This form provides us with the essential contact information to match you with events. </p>
    <p>Please note that his page will not sign you up to volunteer for a specific event; after submitting this form, remember to visit the Events page and contact the organizer of an event you'd like to participate in.</p>
    <p>Privacy: Information entered here is used solely by The Harvest Club; we do not share, sell, or otherwise distribute your personal information.</p>
    -->
	<h1>Volunteer Registration</h1>
    <p>Please complete the form. * Indicates required fields.</p>
    <form method="post" action="submit-volunteer.php" id="volunteer">
		<h2>Volunteer Information</h2>

		<fieldset>
    		<legend>Name</legend>
    		<label for="firstname">First*</label>
    		<input id="firstname" name="firstname" type="text" placeholder="Peter" required="required" />
			<label for="middlename">Middle</label>
			<input id="middlename" name="middlename" type="text" placeholder="The" />
    		<label for="lastname">Last*</label>
    		<input id="lastname" name="lastname" type="text" placeholder="Anteater" required="required" />
			<br/>
    		<label for="organization">Organization</label>
    		<input id="organization" name="organization" type="text" size="40" placeholder="Donald Bren School of ICS" />
		</fieldset>
		<fieldset>
    		<legend>Contact</legend>
    		<label for="email">Email*</label><input id="email" type="email" name="email" placeholder="peter@uci.edu" required="required" />
    		<label for="phone">Phone*</label><input id="phone" type="tel" name="phone" placeholder="9495551234" pattern="[0-9]{10}" required="required"/>
    	</fieldset>

    	<fieldset>
    		<legend>Address</legend>
			<div>
				<label for="street">Street Address</label>
				<input id="street" type="text" name="street" size="40" placeholder="67 E Peltason Dr" />
			</div>
			<div>
				<label for="street">Address Line 2:</label>
				<input id="street2" type="text" name="street2" size="40" placeholder="" />
			</div> 
			<div>
				<label for="city">City*</label>
				<input type="text" name="city" id="city" placeholder="Irvine"  size="10" required="required" />
				<label for="state">State</label>
				<select id="state" name="state" required="required"> 
					<!-- <option value="" disabled="disabled">Select...</option> --> <!-- TODO: Force option -->
					<option value="AL">Alabama</option> 
					<option value="AK">Alaska</option> 
					<option value="AZ">Arizona</option> 
					<option value="AR">Arkansas</option> 
					<option value="CA" selected="yes">California</option> 
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
				<label for="zip">Zip*</label><input type="text" name="zip" id="zip" placeholder="92617" size="4" pattern="[0-9]{5}" required="required" />
			</div>
		</fieldset> 

		<h3>Volunteer Interests</h3>

		<fieldset>
			<legend>Preferred Roles</legend>
				<div id="roles"></div>
				<script type="text/javascript">
				var container = document.getElementById('roles');
					for (var i=0; i<roles.length; i++) {
						var o = roles[i];
						var checkbox = document.createElement('input');
						checkbox.type = "checkbox";
						checkbox.name = "roles[]";
						checkbox.value = '"'+o.id+'"';
						checkbox.id = "id";

						var label = document.createElement('label')
						label.htmlFor = "id";
						label.appendChild(document.createTextNode(o.type));

						container.appendChild(checkbox);
						container.appendChild(label);
					}
				</script>
		</fieldset>

		<fieldset>
			<legend>Preferred Days</legend>
				<tr>
				<div id="days"></div>
				</tr>
				<script type="text/javascript">
				var container = document.getElementById('days');
					for (var i=0; i<days.length; i++) {
						var o = days[i];
						var checkbox = document.createElement('input');
						checkbox.type = "checkbox";
						checkbox.name = "days[]";
						checkbox.value = '"'+o.id+'"';
						checkbox.id = "id";

						var label = document.createElement('label')
						label.htmlFor = "id";
						label.appendChild(document.createTextNode(o.name));

						container.appendChild(checkbox);
						container.appendChild(label);
					}
				</script>
			
		</fieldset>

		<!--
		<fieldset>
    		<legend>Volunteer Role</legend>
			<p><i>Choose all that apply.</i></p>
			<span>
			<input name="roles[]" type="checkbox" id="harvester" value="1"><label for="harvester">Harvester</label>
			 - volunteers at harvesting events
			</span>
			<br />
			<span>
			<input name="roles[]" type="checkbox" id="havestcaptain" value="2"><label for="harvestcaptain">Harvest Captain</label>
			 - leads a harvest crew
			</span>
			<br />
			<input name="roles[]" type="checkbox" id="driver" value="3"><label for="driver">Driver</label>
			- transports donated food to local food pantries
			<br />
			<span>
			<input name="roles[]" type="checkbox" id="ambassador" value="4"><label for="ambassador">Ambassador</label>
			- canvasses neighborhoods and hands out leaflets to homes with visible fruit trees
			</span>
    	</fieldset>

    		<legend>Preferred Days to Volunteer</legend>
			<p><i>Note: Harvest Events usually takes two hours long and generally take place over the weekends.</i></p>
			<span title="Monday">
			<input name="days[]" type="checkbox" id="monday" value="1"><label for="monday">Mon</label>
			</span>
			&nbsp;
			<span title="Tuesday">
			<input name="days[]" type="checkbox" id="tuesday" value="2"><label for="tuesday">Tue</label>
			</span>
			&nbsp;
			<span title="Wednesday">
			<input name="days[]" type="checkbox" id="wednesday" value="3"><label for="wednesday">Wed</label>
			</span>
			&nbsp;
			<span title="Thursday">
			<input name="days[]" type="checkbox" id="thursday" value="4"><label for="thursday">Thu</label>
			</span>
			&nbsp;
			<span title="Friday">
			<input name="days[]" type="checkbox" id="friday" value="5"><label for="friday">Fri</label>
			</span>
			&nbsp;
			<span title="Saturday">
			<input name="days[]" type="checkbox" id="saturday" value="6"><label for="saturday">Sat</label>
			</span>
			&nbsp;
			<span title="Sunday">
			<input name="days[]" type="checkbox" id="sunday" value="7"><label for="sunday">Sun</label>
			</span>
			&nbsp;
		-->	

<!--
		<h3>For Groups</h3>
		<fieldset>
			<legend>Group Registration</legend>
			<label for="group-number">How many people?</label>
			<input id="group-number" name="group-number" type="number" pattern="[0-9]{10}" min="0" max="99" placeholder="10"/>
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
-->

    	<h3>Misc Information</h3>

		<fieldset>
			<legend>Optional</legend>

			<div>
				<label for="source">How did you hear about us?</label>
				<select id="source" name="source">
				</select>
			</div>

			<br />
			<label>Additional comments:</label><br/>
				<textarea name="comments" type="textarea" cols="50" rows="3" placeholder=""></textarea>
		</fieldset>
				
		<br />
		<br />
		<fieldset>
			<legend>Register</legend>
					<div><input name="Submit" value="Register as Volunteer" type="submit" id = "submit"></div>
		</fieldset>			
    </form>
</div>

<script type="text/javascript">
	// populate drop downs
	$(document).ready(function() {
		$('#source').html(options(sources));
	});

	function options(data) {
		var s = optionSelect;
		for (var i=0; i<data.length; i++) {
			var o = data[i];
			s += '<option value="'+o.id+'">'+o.name+'</option>';
		}
		return s;
	}

</script>

</body>
</html>
