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
	<p>The Harvest Club couldn&#39;t exist without wonderful volunteers like you! Please complete this form so we know how to contact you about upcoming harvest events.</p>
    <p>* Indicates required fields.</p>
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
    		<label for="phone">Phone*</label><input id="phone" type="tel" name="phone" placeholder="(949) 555-1234" required="required" />
    		<label for="email">Email*</label><input id="email" type="email" name="email" placeholder="peter@uci.edu" required="required" />
    	</fieldset>


    	<fieldset>
    		<legend>Address</legend>
			<div>
				<label for="street">Street</label>
				<input id="street" type="text" name="street" id="street" placeholder="67 E Peltason Dr" />
				
				<label for="city">City*</label>
				<input type="text" name="city" id="city" placeholder="Irvine"  size="10" required="required" />
			</div>
			<div>
				<label for="state">State*</label>
				<select id="state" name="state" required="required"> 
					<option value="" disabled="disabled" selected="selected">Select...</option><!-- TODO: Force option -->
					<option value="AL">Alabama</option> 
					<option value="AK">Alaska</option> 
					<option value="AZ">Arizona</option> 
					<option value="AR">Arkansas</option> 
					<option value="CA" selected="selected">California</option> 
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
			
		</fieldset> <!-- end Address -->


		<h3>Volunteer Interests</h3>
		<p>There are many ways to volunteer with The Harvest Club. Please select any of the volunteer roles below that are of interest to you.</p>
		<fieldset>
			<legend>Preferred Roles</legend>
			<i>Hover mouse over each role for a description.</i>
				<div id="roles"></div>
				<script type="text/javascript">
				var container = document.getElementById('roles');
				container.innerHTML = '';
				var role = '<div>';
				for (var i=0; i<roles.length; i++) {
					var o = roles[i];
					role += '<div title = "'+o.description+'">';
					role += '<input type="checkbox" name="roles[]" value="'+o.id+'" />' + o.type;
					role += '</div>';
				}
				role += '</div>';
				$(container).append(role);
				</script>
		</fieldset>

		<fieldset>
			<legend>Preferred Days to Volunteer</legend>
			<i>Note: Harvest Events are usually two hours and generally take place over the weekends.</i>
				<tr>
				<div id="days"></div>
				</tr>
				<script type="text/javascript">
				var container = document.getElementById('days');
				container.innerHTML = '';
				var day = '<div>';
				for (var i=0; i<days.length; i++) {
					var o = days[i];
					day += '<div title = "'+o.name+'">';
					day += '<input type="checkbox" name="days[]" value="'+o.id+'" />' + o.name;
					day += '</div>';
				}
				day += '</div>';
				$(container).append(day);
				</script>
			
		</fieldset>

    	<h3>Optional</h3>

		<fieldset>
			<!--<legend>Optional</legend>-->

			<div>
				<label for="source">How did you hear about The Harvest Club?</label>
				<select id="source" name="source">
				</select>
			</div>
			<label>Additional Comments:</label><br/>
				<textarea name="comments" type="textarea" cols="50" rows="3" placeholder="For group harvesters, please provide the following: age range of volunteers, number of volunteers and availability."></textarea>
		</fieldset>
				
		<br />
		<br />
		<fieldset>
			<i>Privacy: Information entered here is used solely by The Harvest Club. We do not share, sell, or otherwise distribute your personal information.</i>
			<legend>Register</legend>
					<div><input name="Submit" value="Submit" type="submit" id = "submit"></div>
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
		for (var i=1; i<data.length; i++) {
			var o = data[i];
			s += '<option value="'+o.id+'">'+o.name+'</option>';
		}
		s += '<option value="'+data[0].id+'">'+data[0].name+'</option>';
		return s;
	}

</script>

</body>
</html>
