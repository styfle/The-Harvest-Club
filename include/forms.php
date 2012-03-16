<?php
require_once('include/Database.inc.php');

function options($id, $name, $data, $disabled=false) {
	//$optionSelect = '<option value="" disabled="disabled" selected="selected">Select...</option>';
	$s = "<select id='$id' name='$name' ";
	if ($disabled)
		$s .= ' disabled="disabled"';
	$s .= '>';
	foreach ($data as $o) {
		$s .= "<option value='$o[id]'>$o[name]</option>";
	}
	return "$s</select>";
}

// perform all pre-processing here including db queries
$empty_cell = '<tr><td>&nbsp;</td></tr><!-- empty cell -->';
$r = $db->q("SELECT id, name FROM privileges;");
$privileges = $r->buildArray();

$r = $db->q("SELECT id, name FROM sources;");
$sources = $r->buildArray();

$r = $db->q("SELECT id, name FROM property_relationships;");
$property_relationships = $r->buildArray();

$r = $db->q("SELECT id, name FROM property_types;");
$property_types = $r->buildArray();

$r = $db->q("SELECT id, Concat(first_name,' ',last_name) AS name FROM growers;");
$grower_id = $r->buildArray();

$r = $db->q("SELECT id, name FROM months;");
$months = $r->buildArray();

$r = $db->q("SELECT id, name FROM tree_types;");
$tree_type_id = $r->buildArray();

$r = $db->q("SELECT id, name FROM tree_heights;");
$avgHeight = $r->buildArray();

?>
<!-- all hidden forms go here -->
<div id="edit-dialog" class="hidden">
		
	<!-- Volunteer form -->	
	<form id="volunteer" class="full_width hidden">
		<h3>Volunteer</h3>
		<table>
		<?php if ($PRIV['appr_volunteer']) { ?>
		<tr id="pending">
			<td>*Pending approval</td>
			<td colspan="2"><input type="button" name="approve" value="Approve" onclick="approveVolunteer();"/></td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan="3" class="hidden"><input id="volunteer1" name="id" type="text" size="2"/></td>
		</tr>
		<tr>
			<td colspan="3" class="hidden"><input id="volunteer16" name="password" type="text" size="2"/></td>
		</tr>
		<tr>
			<td colspan="3" class="hidden"><input id="volunteer8" name="signedup" type="text" size="2"/></td>
		</tr>
		<tr>
			<td colspan="3" class="hidden"><input id="volunteer7" name="user_type" type="text" size="2"/></td>
		</tr>
		<tr>
			<td><label for="volunteer2"><b>First Name</b></label></td>
			<td><label for="volunteer11"><b>Middle</b></label></td>
			<td><label for="volunteer3"><b>Last Name</b></label></td>
		</tr>
		<tr>
			<td><input id="volunteer2" name="firstname" type="text" size="21" required="required"/></td>
			<td><input id="volunteer11" name="middlename" type="text" size="8"/></td>
			<td><input id="volunteer3" name="lastname" type="text" size="15" required="required"/></td>
		</tr>
		<tr>
			<td colspan="3"><label for="volunteer12"><b>Organization Name</b></label></td>
		</tr>
			<td colspan="3"><input type="text" name="organization" id="volunteer12" size="52"></td>
		<tr>
			<td><label for="volunteer6"><b>Phone</b></label></td>
			<td colspan="2"><label for="volunteer5"><b>Email</b></label></td>
		</tr>
		<tr>
			<td><input type="tel" name="phone" id="volunteer6" required="required" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" placeholder="(949) 555-1234" /></td>
			<td colspan="2"><input type="email" name="email" id="volunteer5" size="28" required="required"/></td>
		</tr>
		<tr>
			<td colspan="3"><label for="volunteer13"><b>Street</b></label></td>			
		</tr>
		<tr>
			<td colspan="3"><input type="text" name="street" id="volunteer13" size="52"/></td>			
		</tr>
		<tr>
			<td><label for="volunteer4"><b>City</b></label></td>
			<td><label for="volunteer14"><b>State</b></label></td>
			<td><label for="volunteer15"><b>Zip Code</b></label></td>
		</tr>
		<tr>			
			<td><input type="text" name="city" id="volunteer4" size="21" required="required"/></td>
			<td><input type="text" name="state" id="volunteer14" size="8" maxlength="2" pattern="[A-Z]{2}" style="width:50px;" /></td>
			<td><input type="text" name="zip" id="volunteer15" size="15" maxlength="5" required="required" pattern="[0-9]{5}" style="width:90px;" /></td>
		</tr>
		
		<?php echo $empty_cell ?>
		
		<tr>
			<td><label for="volunteer17"><b>Source</b></label></td>
			<td><label for="volunteer9"><b>Active</b></label></td>					
			<td><label for="volunteer18"><b>User Type</b></label></td>					
		</tr>
		<tr>
			<td>
				<?php echo options('volunteer17', 'source_id', $sources, true); ?>
			</td>
			<td>
				<select id="volunteer9" name="active_id">
					<option value="1">Yes</option>
					<option value="0">No</option>					
				</select>
			</td>			
			<td colspan="2">
				<?php echo options('volunteer18', 'privilege_id', $privileges, !$PRIV['change_priv']); ?>
			</td>
		</tr>
		
		<?php echo $empty_cell ?>
	
		<tr>			
			<td><b>Volunteer Role</b></td>
			<td colspan="2"><b>Preferred Days</b></td>
		</tr>
		
		<tr>
			<td>
				<input type="checkbox" name="volunteerRole1" id="volunteerRole1"  size="28" />
				<label for="volunteerRole1">Harvester</label>
			</td>
			<td colspan="2">
				<input type="checkbox" name="volunteerDay1" id="volunteerDay1" size="28" />
				<label for="volunteerDay1">Monday</label>
			</td>
		</tr>
		<tr>
			<td>
				<input type="checkbox" name="volunteerRole2" id="volunteerRole2" size="28" />
				<label for="volunteerRole2">Harvest Captain</label>
			</td>
			<td colspan="2">
				<input type="checkbox" name="volunteerDay2" id="volunteerDay2" size="28" />
				<label for="volunteerDay2">Tuesday</label>
			</td>
		</tr>
		<tr>
			<td>
				<input type="checkbox" name="volunteerRole3" id="volunteerRole3" size="28" />
				<label for="volunteerRole3">Driver</label>
			</td>
			<td colspan="2">
				<input type="checkbox" name="volunteerDay3" id="volunteerDay3" size="28" />
				<label for="volunteerDay3">Wednesday</label>
			</td>
		</tr>
		<tr>
			<td>
				<input type="checkbox" name="volunteerRole4" id="volunteerRole4" size="28" />
				<label for="volunteerRole4">Ambassador</label>
			</td>
			<td colspan="2">
				<input type="checkbox" name="volunteerDay4" id="volunteerDay4" size="28" />
				<label for="volunteerDay4">Thursday</label>
			</td>
		</tr>
		<tr>
			<td>
				<input type="checkbox" name="volunteerRole5" id="volunteerRole5" size="28" />
				<label for="volunteerRole5">Tree Scout</label>
			</td>
			<td colspan="2">
				<input type="checkbox" name="volunteerDay5" id="volunteerDay5" size="28" />
				<label for="volunteerDay5">Friday</label>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="2">
				<input type="checkbox" name="volunteerDay6" id="volunteerDay6" size="28" />
				<label for="volunteerDay6">Saturday</label>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="2">
				<input type="checkbox" name="volunteerDay7" id="volunteerDay7" size="28" />
				<label for="volunteerDay7">Sunday</label>
			</td>
			</tr>
			<tr>
				<td colspan="3"><label for="volunteer10"><b>Notes</b></label></tr>
			</tr>
			<tr>
				<td colspan="3"><textarea name="note" id="volunteer10" rows="5" cols="48"></textarea></td>
			</tr>
			<tr id="statsButton">				
				<td><input type="button" value="View Stats" onclick="viewStats()" /></td>
			</tr>
		</table>
		
		<div id="statsTable">
			<table id="volunteerStats" cellpadding="0" cellspacing="0" border="0" class="display">
				<!-- table is filled dynamically -->
				<thead><tr><th>Loading...</th></tr></thead>
				<tbody><tr><td>Please wait</td></tr></tbody>

			</table>
		</div>	
	</form>
	<!-- Volunteer end -->
	
	<!-- Grower form -->
	<form id="grower" class="full_width hidden">
		<h3>Grower</h3>
		<table id="growerT">	
		<?php if ($PRIV['appr_grower']) { ?>
		<tr id="pending2">
			<td colspan="2">*Pending approval</td>
			<td><input type="button" name="approve" value="Approve" onclick="approveGrower();"/></td>
		</tr>
		<?php } ?>
		<tr id="view-trees">
			<td>Show his/her trees</td>
			<td colspan="2"><input type="button" onclick="viewTrees();" value="View Trees"/></td>
		</tr>		
		
		<tr>
			<td colspan="3" class="hidden"><input id="grower1" name="id" type="text" size="2"/></td>
		</tr>
		<tr>
			<td colspan="3" class="hidden"><input id="grower15" name="pending_id" type="text" size="2"/></td>
		</tr>
		<tr>
			<td colspan="3" class="hidden"><input id="grower19" name="propertyType" type="text" size="2"/></td>
		</tr>
		<tr>
			<td colspan="3" class="hidden"><input id="grower20" name="propertyRelationship" type="text" size="2"/></td>
		</tr>
		<tr>
			<td><label for="grower2"><b>First</b></label></td>
			<td><label for="grower3"><b>Middle</b></label></td>
			<td><label for="grower4"><b>Last</b></label></td>
		</tr>
		<tr>
			<td><input id="grower2" name="firstname" type="text" size="15" required="required"/></td>
			<td><input id="grower3" name="middlename" type="text" size="6"/></td>
			<td><input id="grower4" name="lastname" type="text" size="14" required="required"/></td>
		</tr>		
		<tr>
			<td><label for="grower5"><b>Phone</b></label></td>
			<td colspan="2"><label for="grower6"><b>Email</b></label></td>
		</tr>
		<tr>
			<td><input type="tel" name="phone" id="grower5" required="required" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" placeholder="(949) 555-1234" /></td>
			<td colspan="2"><input type="email" name="email" id="grower6" size="20" /></td>
		</tr>
		<tr>
			<td colspan="3"><label for="grower7"><b>Preference</b></label></td>			
		</tr>
		<tr>
			<td colspan="3"><input type="text" name="preferred" id="grower7" size="33"/></td>			
		</tr>
		
		<tr>
			<td colspan="3"><label for="grower8"><b>Street</b></label></td>			
		</tr>
		<tr>
			<td colspan="3"><input type="text" name="street" id="grower8" size="33" required="required"/></td>			
		</tr>
		<tr>
			<td><label for="grower9"><b>City</b></label></td>
			<td><label for="grower10"><b>State</b></label></td>
			<td><label for="grower11"><b>Zip Code</b></label></td>
		</tr>
		<tr>			
			<td><input type="text" name="city" id="grower9" size="15" required="required"/></td>
			<td><input type="text" name="state" id="grower10" size="8" required="required" maxlength="2" pattern="[A-Z]{2}" style="width:50px" /></td>
			<td><input type="text" name="zip" id="grower11" size="8" required="required" maxlength="5" patter="[0-9]{5}" style="width:90px;" /></td>
		</tr>
		<tr>
			<td><label for="grower13"><b>Source</b></label></td>
			<td colspan="2"><label for="grower12"><b>Tools Available</b></label></td>
		</tr>
		<tr>
			<td><?php echo options('grower13', 'source_id', $sources, true); ?></td>
			<td colspan="2" rowspan="5"><textarea name="tools" id="grower12" rows="5" cols="30"></textarea></td>
		</tr>	
		<tr>
			<td colspan="3"><label for="grower17"><b>Property Type</b></label></td>
		</tr>
		<tr>			
			<td colspan="3">
			<?php echo options('grower17', 'property_type', $property_types); ?>				
			</td>
		</tr>
		<tr>
			<td colspan="3"><label for="grower18"><b>Property Relationship</b></label></td>
		</tr>
		<tr>
			<td colspan="3">
				<?php echo options('grower18', 'property_relationship', $property_relationships); ?>
			</td>
		</tr>		
		<tr>
			<td colspan="3"><label for="grower14"><b>Notes</b></label></tr>
		</tr>
		<tr>
			<td colspan="3"><textarea name="notes" id="grower14" rows="4" cols="30"></textarea></td>
		</tr>
		<tr id="statsButton2">				
				<td><input type="button" value="View Stats" onclick="viewGrowerStats()" /></td>
			</tr>
		</table>
		
		<div id="statsTable2">
			<div class="toolbar1">
			<span id="toolbar1" class="css_right ui-widget-header ui-corner-all">
				<input id="modeButton" type="button" value="View Per Tree" onclick="switchMode()" />				
			</span>
			</div><!-- End toolbar -->
			<table id="growerStats" cellpadding="0" cellspacing="0" border="0" class="display">
				<!-- table is filled dynamically -->
				<thead><tr><th>Loading...</th></tr></thead>
				<tbody><tr><td>Please wait</td></tr></tbody>

			</table>
		</div>		
	</form>
	<!-- Grower end -->
	
	
	<!-- Tree form -->	
	<form id="tree" class="full_width hidden">
		<h3>Tree</h3>
		<table>	
		<tr>
			<td class="hidden">
				<input id="tree1" name="id" type="text" size="2"/>
				<input id="tree2" name="name" type="text" size="2"/>
				<input id="tree5" name="tree_type_name" type="text" size="2"/>
				<input id="tree9" name="chemicaled" type="text" size="2"/>
				<input id="tree11" name="height_name" type="text" size="2"/>
			</td>
		</tr>
		<tr>			
			<td>
				<label for="tree3"><b>Owner</b>
					<input id="view-grower" type="button" onclick="viewGrower();" value="View"/>
				</label>
			</td>
			<td><label for="tree8"><b>Chemical</b></label></td>
			<td><label for="tree10"><b>Height</b></label></td>
		</tr>
		<tr>			
			<td>				
				<?php echo options('tree3', 'grower_id', $grower_id); ?>
			</td>
			<td>
				<select id="tree8" name="chemicaled_id">
					<option value="1">Yes</option>
					<option value="0">No</option>
				</select>
			</td>
			<td><?php echo options('tree10', 'avgHeight_id', $avgHeight); ?></td>
		</tr>

		<?php echo $empty_cell ?>

		<tr>
			<td><label for="tree4"><b>Tree Type</b></label></td>	
			<td><label for="tree6"><b>Varietal</b></label></td>			
			<td><label for="tree7"><b>Number</b></label></td>
		</tr>
		<tr>			
			<td><?php echo options('tree4', 'tree_type_id', $tree_type_id); ?></td>			
			<td><input type="text" name="varietal" id="tree6" /></td>
			<td><input type="number" name="number" id="tree7" required="required" style="width:50px" /></td>
		</tr>
		
		<?php echo $empty_cell ?>
		
		<tr>
			<td colspan="3"><b>Harvest Months</b></td>
		</tr>
		<?php
			echo '<tr>';
			foreach ($months as $m) {
				$id = $m['id'];
				$name = $m['name'];

				echo "<td><input type='checkbox' name='tree_month$id' id='tree_month$id' /> $name</td>";
				if ($id % 3 == 0)
					echo "</tr>\n\n<tr>";
			}
			echo '</tr>';
		?>
			
		</table>	
	</form>	
	<!-- Tree end -->
	
	<!-- Distribution form -->
	<form id="distribution" class="full_width hidden">
		<h3>Distribution Site</h3>
		<table>
		<tr>
			<td colspan="3" class="hidden"><input id="distribution1" name="id" type="text" size="2"/></td>
		</tr>
		<tr>
			<td colspan="3"><label for="distribution2"><b>Name</b></label></td>			
		</tr>
		<tr>
			<td colspan="3"><input id="distribution2" name="name" type="text" size="45"/></td>
		</tr>		
		<tr>
			<td colspan="2"><label for="distribution6"><b>Agency Contact</b></label></td>			
			<td><label for="distribution7"><b>Phone</b></label></td>
		</tr>
		<tr>
			<td colspan="2"><input id="distribution6" name="contact" type="text" size="20"/></td>
			<td><input type="tel" name="phone" id="distribution7" required="required" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" placeholder="(949) 555-1234" /></td>
		</tr>
		<tr>
			<td colspan="2"><label for="distribution10"><b>Secondary Contact</b></label></td>			
			<td><label for="distribution11"><b>Secondary Phone</b></label></td>
		</tr>
		<tr>
			<td colspan="2"><input id="distribution10" name="contact2" type="text" size="20"/></td>
			<td><input type="tel" name="phone2" id="distribution11" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" placeholder="(949) 555-1234" /></td>
		</tr>
		<tr>
			<td colspan="3"><label for="distribution8"><b>Email</b></label></td>
		</tr>
		<tr>
			<td colspan="3"><input type="text" name="email" id="distribution8" size="45" /></td>
		</tr>
		<tr>
			<td colspan="3"><label for="distribution3"><b>Street</b></label></td>			
		</tr>
		<tr>
			<td colspan="3"><input type="text" name="street" id="distribution3" size="45"/></td>			
		</tr>
		<tr>
			<td><label for="distribution4"><b>City</b></label></td>
			<td><label for="distribution9"><b>State</b></label></td>
			<td><label for="distribution5"><b>Zip Code</b></label></td>
		</tr>
		<tr>			
			<td><input type="text" name="city" id="distribution4" required="required" /></td>
			<td><input type="text" name="state" id="distribution9" required="required" maxlength="2" pattern="[A-Z]{2}" style="width:50px" /></td>
			<td><input type="text" name="zip" id="distribution5" require="required" maxlength="5" pattern="[0-9]{5}" style="width:90px;" /></td>
		</tr>
		<tr>
			<td><label for="distribution12"><b>Days/Hours of Availability</b></label></td>
			<td colspan="2"><label for="distribution13"><b>Notes</b></label></td>
		</tr>
		<tr>
			<td><textarea name="daytime" id="distribution12" rows="5" cols="43"></textarea></td>
			<td colspan="2"><textarea name="notes" id="distribution13" rows="5" cols="43"></textarea></td>
		</tr>
		</table>
		
		<table class="hidden">
			<?php echo $empty_cell ?>
			
			<tr>
				<td><b>Availability</b></td>
				<td colspan="3"><b>Time Open</b></td>
				<td>&nbsp;</td>
				<td colspan="3"><b>Time Close</b></td>
			</tr>
			<tr>	
				<td>Monday</td>			
				<td><select name="distributionHour1-OpenHour" id ="distributionHour1-OpenHour"></select></td>
				<td>:</td>
				<td><select name="distributionHour1-OpenMin" id ="distributionHour1-OpenMin"></select></td>
				<td>&mdash;</td>
				<td><select name="distributionHour1-CloseHour" id ="distributionHour1-CloseHour"></select></td>
				<td>:</td>
				<td><select name="distributionHour1-CloseMin" id ="distributionHour1-CloseMin"></select></td>
			</tr>				
			<tr>	
				<td>Tuesday</td>			
				<td><select name="distributionHour2-OpenHour" id ="distributionHour2-OpenHour"></select></td>
				<td>:</td>
				<td><select name="distributionHour2-OpenMin" id ="distributionHour2-OpenMin"></select></td>
				<td>&mdash;</td>
				<td><select name="distributionHour2-CloseHour" id ="distributionHour2-CloseHour"></select></td>
				<td>:</td>			
				<td><select name="distributionHour2-CloseMin" id ="distributionHour2-CloseMin"></select></td>
			</tr>			
			<tr>	
				<td>Wednesday</td>			
				<td><select name="distributionHour3-OpenHour" id ="distributionHour3-OpenHour"></select></td>
				<td>:</td>
				<td><select name="distributionHour3-OpenMin" id ="distributionHour3-OpenMin"></select></td>
				<td>&mdash;</td>
				<td><select name="distributionHour3-CloseHour" id ="distributionHour3-CloseHour"></select></td>
				<td>:</td>
				<td><select name="distributionHour3-CloseMin" id ="distributionHour3-CloseMin"></select></td>
			</tr>		
			<tr>	
				<td>Thursday</td>			
				<td><select name="distributionHour4-OpenHour" id ="distributionHour4-OpenHour"></select></td>
				<td>:</td>
				<td><select name="distributionHour4-OpenMin" id ="distributionHour4-OpenMin"></select></td>
				<td>&mdash;</td>
				<td><select name="distributionHour4-CloseHour" id ="distributionHour4-CloseHour"></select></td>
				<td>:</td>
				<td><select name="distributionHour4-CloseMin" id ="distributionHour4-CloseMin"></select></td>
			</tr>		
			<tr>	
				<td>Friday</td>			
				<td><select name="distributionHour5-OpenHour" id ="distributionHour5-OpenHour"></select></td>
				<td>:</td>
				<td><select name="distributionHour5-OpenMin" id ="distributionHour5-OpenMin"></select></td>
				<td>&mdash;</td>
				<td><select name="distributionHour5-CloseHour" id ="distributionHour5-CloseHour"></select></td>
				<td>:</td>
				<td><select name="distributionHour5-CloseMin" id ="distributionHour5-CloseMin"></select></td>
			</tr>		
			<tr>	
				<td>Saturday</td>			
				<td><select name="distributionHour6-OpenHour" id ="distributionHour6-OpenHour"></select></td>
				<td>:</td>
				<td><select name="distributionHour6-OpenMin" id ="distributionHour6-OpenMin"></select></td>
				<td>&mdash;</td>
				<td><select name="distributionHour6-CloseHour" id ="distributionHour6-CloseHour"></select></td>
				<td>:</td>
				<td><select name="distributionHour6-CloseMin" id ="distributionHour6-CloseMin"></select></td>
			</tr>		
			<tr>	
				<td>Sunday</td>			
				<td><select name="distributionHour7-OpenHour" id ="distributionHour7-OpenHour"></select></td>
				<td>:</td>
				<td><select name="distributionHour7-OpenMin" id ="distributionHour7-OpenMin"></select></td>
				<td>&mdash;</td>
				<td><select name="distributionHour7-CloseHour" id ="distributionHour7-CloseHour"></select></td>
				<td>:</td>
				<td><select name="distributionHour7-CloseMin" id ="distributionHour7-CloseMin"></select></td>
			</tr>
		</table>
	</form>	
	<!-- Distribution end -->

	<!-- Event form -->
	<form id="event" class="full_width hidden">
		<h3>Harvest Event <span id="event-id"></span></h3>
		<table>
			<tr>
				<td> 
					<table>
					<!--
						<tr>
							<td><input id="event1" name="id" type="text" onchange="document.getElementById('event-id').innerText=this.value" /></td>
						</tr>
						-->
						<tr>		

							<td colspan="4" ><label for="event-grower-name"><b>Grower</b></label></td>								
						</tr>
						<tr>
							<td colspan="4" id ="event-grower"></td>										
						</tr>	
												

						<tr>
							<td colspan="7" ><label for="event7"><b>City of Harvest</b></label></td>			
						</tr>
						<tr>
							<td colspan="7" ><input id="event7" name="event-city" type="text" size="27" readonly /></td>
						</tr>

						
						<tr>
							<td colspan="7" ><label for="event8"><b>Grower's Phone Number</b></label></td>			
						</tr>
						<tr>
							<td colspan="7" ><input id="event8" name="event-grower-number" type="text" size="27" readonly /></td>
						</tr>
						
						<tr>
							<td colspan="7" ><label for="event9"><b>Grower's Address</b></label></td>			
						</tr>
						<tr>
							<td colspan="7" ><input id="event9" name="event-grower-address" type="text" size="27" readonly /></td>
						</tr>
						
						<tr>
							<td colspan="7" ><label for="event4"><b>Harvest Date</b></label></td>			
						</tr>
						<tr>
							<td colspan="7" ><input id="event4" name="event-date" type="text" size="27"/></td>
						</tr>
						

						<tr>
							<td colspan="7" ><label for="event5"><b>Harvest Time</b></label></td>			
						</tr>
						<tr>
							<td colspan="7" ><input id="event5" name="event-time" type="text" size="27"/></td>
						</tr>
					</table>
				</td>
				<!--   -->	
				<td> 
					<table>
						<tr>							
							<td colspan="3" ><label for="event-captain"><b>Harvest Captain</b></label></td>	
						</tr>
						<tr>						

							<td colspan="3" id ="event-captain"></td>			
						</tr>	
						
						<tr>
							<td colspan="3"><label for="event6"><b>Notes</b></label></tr>
						</tr>
						<tr>
							<td colspan="3"><textarea name="notes" id="event6" rows="11" cols="23"></textarea></td>
						</tr>
					</table>
				</td>	
			</tr>		
		</table>


		<?php echo $empty_cell ?>

		<table>
			<tr>		
				<td><label for="event-grower-name" ><b>Tree Types</b></label></td>	
				<td>	
				 <INPUT type="button" value="Add" onclick="addTreeRow('eventTree')" /> 
				 <INPUT type="button" value="Remove" onclick="deleteTreeRow('eventTree')" /> 
				</td>
			</tr>
			<table id="eventTree" width="250px"></table>				
		
		</table>
		
		<?php echo $empty_cell ?>
		
		<table>
			<tr>		
				<td ><label for="event-volunteer-name"><b>Volunteers</b></label></td>	
				<td >	
					<INPUT type="button" value="Add" onclick="addVolunteerRow('eventVolunteer')" />
					<INPUT type="button" value="Remove" onclick="deleteVolunteerRow('eventVolunteer')" />
				</td>
			</tr>
			
			<TABLE id="eventVolunteer" width="450px"></TABLE>
		</table>
		
		


	</form>	
	<!-- Event end -->


	<!-- Donation form -->
	<form id="donation" class="full_width hidden">
		<h3>Donation</h3>
		<table>
		<tr>
			<td colspan="3" class="hidden"><input id="donation1" name="id" type="text" size="2"/></td>
		</tr>
		<tr>
			<td colspan="3"><label for="donation2"><b>Donation</b> (What was donated)</label></td>
		</tr>
		<tr>
			<td colspan="3"><input type="text" name="donation" id="donation2" size="33" required = "required"/></td>			
		</tr>
		<tr>
			<td colspan="3"><label for="donation3"><b>Donor</b> (Who donated it)</label></td>		
		</tr>
		<tr>
			<td colspan="3"><input type="text" name="donor" id="donation3" size="33" required = "required"/></td>			
		</tr>
		<tr>
			<td colspan="3"><label for="donation4"><b>Value</b> (Estimated value in dollars)</label></td>
		</tr>
		<tr>
			<td colspan="3"><input type="text" name="value" id="donation4" pattern="[0-9]+(\.[0-9]+)?" /></td>			
		</tr>
		<tr>
			<td colspan="3"><label for="donation5"><b>Date</b> (When was it donated)</label></td>
		</tr>
		<tr>
			<td colspan="3"><input type="text" name="date" id="donation5" size="33" required = "required"/></td>			
		</tr>
		</table>
	</form>
	<!-- Donation end -->

	<!-- Email Form -->
	<form id="email" class="full_width hidden">
		<h3>Send Email</h3>
		<div><b>Recipients</b> (<span class="rcount"></span>)</div>
		<div><textarea name="bcc" type="text" size="50" required="required" style="font-size:0.9em"></textarea></div>
		<br/>
		<div><b>Subject</b></div>
		<div><input name="subject" type="text" size="40" required="required" /></div>
		<br/>
		<div>
			<b>Template</b>&nbsp;
			<select name="template" onchange="changeTemplate(this);">
				<option value="">&lt;&lt;None&gt;&gt;</option>
				<option value="invitation">Harvest Invitation</option>
				<option value="details">Harvest Details</option>
				<!-- option value="reminder">Harvest Reminder</option -->
			</select>
		</div>
		<br/>
		<div><b>Message</b></div>
		<div><textarea name="message" rows="10" cols="50" required="required"></textarea></div>
		<br/>
	</form>
	<!-- Email end -->
</div>
<!-- dialog end -->
