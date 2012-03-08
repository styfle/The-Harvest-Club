<?php

require_once('include/Database.inc.php');
	
$r = $db->q("SELECT * FROM property_types;");
if(!$r->isValid())
	die("MySQL Error: " . $db->error());
$property_types = $r->buildArray();

$r = $db->q("SELECT * FROM property_relationships;");
if(!$r->isValid())
	die("MySQL Error: " . $db->error());
$property_relationships = $r->buildArray();

$r = $db->q("SELECT * FROM sources;");
if(!$r->isValid())
	die("MySQL Error: " . $db->error());
$sources = $r->buildArray();

$r = $db->q("SELECT * FROM tree_types;");
if(!$r->isValid())
	die("MySQL Error: " . $db->error());
$tree_types = $r->buildArray();

$r = $db->q("SELECT * FROM tree_heights;");
if(!$r->isValid())
	die("MySQL Error: " . $db->error());
$tree_heights = $r->buildArray();

$r = $db->q("SELECT * FROM months;");
if(!$r->isValid())
	die("MySQL Error: " . $db->error());
$months = $r->buildArray();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
	<title>Grower Registration</title>
	<script src="js/jquery-1.7.1.min.js"></script>
	<script>
		var optionSelect = '<option value="" disabled="disabled" selected="selected">Select...</option>';
		var property_types = <?php echo json_encode($property_types); ?>;
		var property_relationships = <?php echo json_encode($property_relationships); ?>;
		var sources = <?php echo json_encode($sources); ?>;
		var tree_types = <?php echo json_encode($tree_types); ?>;
		var tree_heights = <?php echo json_encode($tree_heights); ?>;
		var months = <?php echo json_encode($months); ?>;
	</script>
</head>

<body>
<div id="main" role="main">
	<h1>Grower Registration</h1>
	<p>By completing this form, you will be added to The Harvest Club Grower Database and we&#39;ll be in touch with you to coordinate a harvest.  This form provides us with the essential contact information and your harvest preferences.</p>
    <form id="grower" action="submit-grower.php" method="post">
    	<h2>Grower Information</h2>
    	
    	<fieldset>
    		<legend>Name</legend>
    		<label for="firstname">First*</label>
    		<input id="firstname" name="firstname" type="text" placeholder="Peter" required="required" />
    
			<label for="middlename">Middle</label>
			<input id="middlename" name="middlename" type="text" placeholder="The" />

    		<label for="lastname">Last*</label>
    		<input id="lastname" name="lastname" type="text" placeholder="Anteater" required="required" />
    		<br/>
    	</fieldset>
    	
    	<fieldset>
    		<legend>Contact</legend>
    		<label for="phone">Phone*</label><input id="phone" type="tel" name="phone" placeholder="(949) 555-1234" required="required" />
    		<label for="email">Email</label><input id="email" type="email" name="email" placeholder="peter@uci.edu" />
    		<div>
				<label>Preferred Contact* </label>
				&nbsp;
				<input id="contact-phone" name="prefer" type="radio" value="phone" checked="checked"/><label for="contact-phone">Phone</label>
				&nbsp;
				<input id="contact-email" name="prefer" type="radio" value="email" /><label for="contact-email">Email</label>
			</div>
    	</fieldset>
    	
    	<fieldset>
    		<legend>Address</legend>
			<div>
				<label for="street">Street*</label>
				<input id="street" type="text" name="street" id="street" placeholder="67 E Peltason Dr" required="required" />
				
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
			
			<div>
				<label for="property">Property type*</label>
				<select id="property" name="property" required="required">
				</select>
			</div>
			
			<div>
				<label for="relationship">Relationship to property*</label>
				<select id="relationship" name="relationship" required="required">
				</select>
			</div>
		
		</fieldset> <!-- end Address -->
		
		<h2>Harvesting Tools / Property</h2>
		<fieldset>
			<legend>Optional</legend>
			
			<div>
				<label for="tools">Are there tools available on site for volunteers? If yes, please list:</label>
				<input name="tools" type="text" />
			</div>
			
			<div>
				<label for="source">How did you hear about us?</label>
				<select id="source" name="source">
				</select>
			</div>
			
			<div>
				<label>Anything else you would like us to know?</label><br/>
				<textarea name="notes" type="textarea" cols="50" rows="3" placeholder="Tree health, taste of fruit, accessibility of fruit, parking, etc"></textarea>
			</div>
		</fieldset>
		
		<h2>Tree Information</h2>
		
		<div>
			<label for="type-count">How many different types of trees (or other kinds or produce) would you like to register?</label>
			<select id="type-count" name="type-count" required="required" onchange="changeTreeCount(this);">
				<option value="" disabled="disabled" selected="selected">Select...</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
			</select>
		</div>
		
		<div id="dynamic">
			<!-- Javascript fills this div -->
		</div>

		<fieldset>
			<legend>Register</legend>
			<input id="submit" name="Submit" type="submit" value="Register as Grower" disabled="disabled"/> <!--onclick="this.disabled='disabled';" /-->
		</fieldset>
	</form>
</div>

<script type="text/javascript">
	// populate drop downs
	$(document).ready(function() {
		$('#property').html(options(property_types));
		$('#relationship').html(options(property_relationships));
		$('#source').html(options(sources));
	});
	
	// create select options from key value pairs
	function options(data) {
		var s = optionSelect; // first option is always select...
		for (var i=0; i<data.length; i++) {
			var o = data[i];
			s += '<option value="'+o.id+'">'+o.name+'</option>';
		}
		return s;
	}
	
	
	/* Show and hide tree entry form using jQuery */
	function changeTreeCount(s) {
		var dynamic = document.getElementById('dynamic')
		dynamic.innerHTML = ''; // clear
		var count = (s.options[s.selectedIndex].value);
		
		var array = [];
		
		for (var i=1; i<=count; i++) {
			
			var fieldset = document.createElement('fieldset');
			var legend = document.createElement('legend');
			$(legend).text('Tree Type ' + i);
			
			var type = '<div>Produce other than tree fruits can be registered by selecting "Other" below.</div>';
			type += '<div> <label>Tree Type* (Orange, Apple, etc.)</label> <select name="trees[tree'+i+'][type]" required="required"> ';
			type += options(tree_types);
			type += '</select> </div>'; // <input type="text" placeholder="If other, specify" /></div>';
			
			var varietal = '<div>If you selected a fruit and know the varietal,<br/>';
			varietal += 'OR If you selected "other" please specify below</div>';
			varietal += '<div> <input name="trees[tree'+i+'][varietal]" type="text" size="55" placeholder="Fruit varietal or other fruit type or vegetable" /> </div>';

			var quantity = '<div> <label>Number of trees of this type</label> <input name="trees[tree'+i+'][quantity]" type="number" min="1" /> </div>';
			
			var height = '<div> <label>Tree Height</label> <select name="trees[tree'+i+'][height]">';
			height += options(tree_heights);
			height += '</select> </div>';
			
			var month = '<div> <label>Harvest Months</label> <table><tr>';
			for (var j=0; j<months.length; j++) {
				var o = months[j];
				month += '<td> <input type="checkbox" name="trees[tree'+i+'][month][]" value="'+o.id+'" />' + o.name + '</td>';
				if ((j+1)%4 == 0)
					month += '</tr>';
			}
			month += '</table></div>';
			
			var chemical = '<div> <label>Have chemicals been used on or around your tree(s)?</label> <input type="text" name="trees[tree'+i+'][chemical]" placeholder="If yes, specify" /> </div>';
			
			$(fieldset).append(legend)
				.append(type)
				.append(varietal)
				.append(quantity)
				.append(height)
				.append(month)
				.append(chemical);
			
			array.push(fieldset);
		}
		
		$(dynamic).append(array); // append tree forms
		document.getElementById('submit').disabled = '';
	}
</script>

</body>
</html>
