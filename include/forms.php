<!-- all hidden forms go here -->
<div id="edit-dialog" class="hidden">
	
	<!--------------- Volunteer form ----------------->	
	<form id="volunteer" class="hidden">
		<h3>Volunteer</h3>
		<table>
		<tr>
			<td colspan="3" style = "display:none"><input id="volunteer1" name="id" type="text" size="2"/></td>
		</tr>
		<tr>
			<td colspan="3" style = "display:none"><input id="volunteer7" name="password" type="text" size="2"/></td>
		</tr>
		<tr>
			<td colspan="3" style = "display:none"><input id="volunteer13" name="privilege" type="text" size="2"/></td>
		</tr>
		<tr>
			<td colspan="3" style = "display:none"><input id="volunteer14" name="signedup" type="text" size="2"/></td>
		</tr>
		<tr>
			<td><label for="volunteer2" ><b>First</b></label></td>
			<td><label for="volunteer3"><b>Middle</b></label></td>
			<td><label for="volunteer4"><b>Last</b></label></td>
		</tr>
		<tr>
			<td><input id="volunteer2" name="firstname" type="text" size="20"/></td>
			<td><input id="volunteer3" name="middlename" type="text" size="10"/></td>
			<td><input id="volunteer4" name="lastname" type="text" size="15"/></td>
		</tr>		
		<tr>
			<td><label for="volunteer5"><b>Phone</b></label></td>
			<td colspan="2"><label for="volunteer6"><b>Email</b></label></td>
		</tr>
		<tr>
			<td><input type="tel" name="phone" id="volunteer5" size="20" /></td>
			<td colspan="2"><input type="text" name="email" id="volunteer6" size="28" /></td>
		</tr>
		<tr>
			<td colspan="3"><label for="volunteer9"><b>Street</b></label></td>			
		</tr>
		<tr>
			<td colspan="3"><input type="text" name="street" id="volunteer9" size="52"/></td>			
		</tr>
		<tr>
			<td><label for="volunteer10"><b>City</b></label></td>
			<td><label for="volunteer11"><b>State</b></label></td>
			<td><label for="volunteer12"><b>Zip</b></label></td>
		</tr>
		<tr>			
			<td><input type="text" name="city" id="volunteer10" size="20"/></td>
			<td><input type="text" name="state" id="volunteer11" size="10" maxlength="2"/></td>
			<td><input type="text" name="zip" id="volunteer12" size="15"/></td>
		</tr>		
		<tr>
			
			<td><label for="volunteer8"><br> <b>Status</b></label></td>					
			<td colspan="2">
				<br>
				<select id="volunteer8" name="status">
					<option value="1">Active</option>
					<option value="0">Inactive</option>					
				</select>
			</td>			
		</tr>		
		<tr>			
			<td colspan="3"><label for="volunteer12"><br><b>Volunteer Role</b></label></td>
		</tr>
		
		<tr>
			<td><label for="volunteer5">Harvester</label></td>
			<td colspan="2"><input type="checkbox" name="volunteerRole1" id="volunteerRole1"  size="28" /></td>
		</tr>
		<tr>
			<td><label for="volunteer5">Harvest Captain</label></td>
			<td colspan="2"><input type="checkbox" name="volunteerRole2" id="volunteerRole2" size="28" /></td>
		</tr>
		<tr>
			<td><label for="volunteer5">Driver</label></td>
			<td colspan="2"><input type="checkbox" name="volunteerRole3" id="volunteerRole3" size="28" /></td>
		</tr>
		<tr>
			<td><label for="volunteer5">Ambassador</label></td>
			<td colspan="2"><input type="checkbox" name="volunteerRole4" id="volunteerRole4" size="28" /></td>
		</tr>
		<tr>
			<td><label for="volunteer5">Tree Scout</label></td>
			<td colspan="2"><input type="checkbox" name="volunteerRole5" id="volunteerRole5" size="28" /></td>
		</tr>
		
		<tr>			
			<td colspan="3"><label for="volunteer12"><br><b>Preferred Days</b></label></td>
		</tr>
		
		<tr>
			<td><label for="volunteer5">Monday</label></td>
			<td colspan="2"><input type="checkbox" name="volunteerDay1" id="volunteerDay1" size="28" /></td>
		</tr>
		<tr>
			<td><label for="volunteer5">Tuesday</label></td>
			<td colspan="2"><input type="checkbox" name="volunteerDay2" id="volunteerDay2" size="28" /></td>
		</tr>
		<tr>
			<td><label for="volunteer5">Wednesday</label></td>
			<td colspan="2"><input type="checkbox" name="volunteerDay3" id="volunteerDay3" size="28" /></td>
		</tr>
		<tr>
			<td><label for="volunteer5">Thursday</label></td>
			<td colspan="2"><input type="checkbox" name="volunteerDay4" id="volunteerDay4" size="28" /></td>
		</tr>
		<tr>
			<td><label for="volunteer5">Friday</label></td>
			<td colspan="2"><input type="checkbox" name="volunteerDay5" id="volunteerDay5" size="28" /></td>
		</tr>
		<tr>
			<td><label for="volunteer5">Saturday</label></td>
			<td colspan="2"><input type="checkbox" name="volunteerDay6" id="volunteerDay6" size="28" /></td>
		</tr>
		<tr>
			<td><label for="volunteer5">Sunday</label></td>
			<td colspan="2"><input type="checkbox" name="volunteerDay7" id="volunteerDay7" size="28" /></td>
		</tr>		
		
		</table>	
	
	<div style="margin-top:5px;">
		<div><label for="volunteer14"><b>Notes</b></label></div>
		<div><textarea name="note" id="volunteer15" rows="5" cols="48"></textarea></div>
	</div>	
	</form>
	
	<!--------------- Grower form ----------------->
	<form id="grower" class="hidden">
		<h3>Grower</h3>
		<table>
		<tr>
			<td colspan="3" style = "display:none"><input id="grower1" name="id" type="text" size="2"/></td>
		</tr>
		<tr>
			<td colspan="3" style = "display:none"><input id="grower14" name="pending" type="text" size="2"/></td>
		</tr>
		<tr>
			<td><label for="grower2" >First</label></td>
			<td><label for="grower3">Middle</label></td>
			<td><label for="grower4">Last</label></td>
		</tr>
		<tr>
			<td><input id="grower2" name="firstname" type="text" size="12"/></td>
			<td><input id="grower3" name="middlename" type="text" size="4"/></td>
			<td><input id="grower4" name="lastname" type="text" size="10"/></td>
		</tr>		
		<tr>
			<td><label for="grower5">Phone</label></td>
			<td colspan="2"><label for="grower6">Email</label></td>
		</tr>
		<tr>
			<td><input type="tel" name="phone" id="grower5" size="12" /></td>
			<td colspan="2"><input type="text" name="email" id="grower6" size="17" /></td>
		</tr>
		<tr>
			<td colspan="3"><label for="grower7">Street</label></td>			
		</tr>
		<tr>
			<td colspan="3"><input type="text" name="street" id="grower7" size="33"/></td>			
		</tr>
		<tr>
			<td><label for="grower8">City</label></td>
			<td><label for="grower9">State</label></td>
			<td><label for="grower10">Zip</label></td>
		</tr>
		<tr>			
			<td><input type="text" name="city" id="grower8" size="12"/></td>
			<td><input type="text" name="state" id="grower9" size="2"/></td>
			<td><input type="text" name="zip" id="grower10" size="8"/></td>
		</tr>
		<tr>
			<td><label for="grower12" >Source</label></td>
		</tr>
		<tr>
			<td colspan="3"><input id="grower12" name="source" type="text" size="20"/></td>			
		</tr>	
		<tr>
			<td colspan="3"><label for="grower15">Property Type</label></td>
		</tr>
		<tr>			
			<td colspan="3">
				<select id="grower15" name="property_type" style="width:200px;"> 
					<option value="1">Residence</option>	
					<option value="2">Open Space/ Vacant lot</option>
					<option value="3">Business</option>	
					<option value="4">Public Property</option>					
					<option value="5">Other</option>
				</select>
			</td >
		</tr>
		<tr>			
			<td colspan="3"><label for="grower16">Property Relationship</label></td>
		</tr>
		<tr>			
			<td colspan="3">
				<select id="grower16" name="property_relationship" style="width:200px;">
					<option value="1">Owner & Occupant</option>	
					<option value="2">Renter</option>
					<option value="3">Renter property owner(landlord)</option>	
					<option value="4">Other</option>													
				</select>
			</td>
		</tr>		
		
		</table>	
	<div style="margin-top:5px;">
		<div><label for="grower11">Tools</label></div>
		<div><textarea name="tools" id="grower11" rows="5" cols="30"></textarea></div>
	</div>	
	<div style="margin-top:5px;">
		<div><label for="grower13">Notes</label></div>
		<div><textarea name="notes" id="grower13" rows="5" cols="30"></textarea></div>
	</div>	
	</form>	
	
	<!--------------- Distribution form ----------------->
	<form id="distribution" class="hidden">
		<h3>Distribution Site</h3>
		<table>
		<tr>
			<td colspan="3" style = "display:none"><input id="distribution1" name="id" type="text" size="2"/></td>
		</tr>
		<tr>
			<td colspan="3"><label for="distribution2" >Name</label></td>			
		</tr>
		<tr>
			<td colspan="3"><input id="distribution2" name="name" type="text" size="45"/></td>
		</tr>		
		<tr>
			<td><label for="distribution3">Phone</label></td>
			<td colspan="2"><label for="distribution4">Email</label></td>
		</tr>
		<tr>
			<td><input type="tel" name="phone" id="distribution3" size="21" /></td>
			<td colspan="2"><input type="text" name="email" id="distribution4" size="20" /></td>
		</tr>
		<tr>
			<td colspan="3"><label for="street">Street</label></td>			
		</tr>
		<tr>
			<td colspan="3"><input type="text" name="street" id="distribution5" size="45"/></td>			
		</tr>
		<tr>
			<td><label for="city">City</label></td>
			<td><label for="state">State</label></td>
			<td><label for="zip">Zip</label></td>
		</tr>
		<tr>			
			<td><input type="text" name="city" id="distribution6" size="20"/></td>
			<td><input type="text" name="state" id="distribution7" size="4"/></td>
			<td><input type="text" name="zip" id="distribution8" size="12"/></td>
		</tr>				
		</table>
		
		<table>
			<br>
			
			<tr>
				<td><label > <b> Hours </b></label></td>
				<td colspan="3"><label > <b> Open </b> </label></td>
				<td><label >   </label></td>
				<td colspan="3"><label > <b>Close </b> </label></td>
			</tr>
			<tr>	
				<td><label for="city">Monday </label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="distributionHour1-OpenHour" id ="distributionHour1-OpenHour"/></td>
				<td><label size="1">:</label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="distributionHour1-OpenMin" id ="distributionHour1-OpenMin"/></td>
				
				<td><label >  ---   </label></td>
				<td><input type="text" value="" size="2" maxlength="2" name="distributionHour1-CloseHour" id ="distributionHour1-CloseHour" /></td>
				<td><label size="1">:</label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="distributionHour1-CloseMin" id ="distributionHour1-CloseMin" /></td>
				
			</tr>				
			<tr>	
				<td><label for="city">Tuesday </label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="distributionHour2-OpenHour" id ="distributionHour2-OpenHour"/></td>
				<td><label size="1">:</label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="distributionHour2-OpenMin" id ="distributionHour2-OpenMin"/></td>
				
				<td><label >  ---   </label></td>
				<td><input type="text" value="" size="2" maxlength="2" name="distributionHour2-CloseHour" id ="distributionHour2-CloseHour" /></td>
				<td><label size="1">:</label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="distributionHour2-CloseMin" id ="distributionHour2-CloseMin" /></td>
				
			</tr>			
			<tr>	
				<td><label for="city">Wednesday </label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="distributionHour3-OpenHour" id ="distributionHour3-OpenHour"/></td>
				<td><label size="1">:</label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="distributionHour3-OpenMin" id ="distributionHour3-OpenMin"/></td>
				
				<td><label >  ---   </label></td>
				<td><input type="text" value="" size="2" maxlength="2" name="distributionHour3-CloseHour" id ="distributionHour3-CloseHour" /></td>
				<td><label size="1">:</label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="distributionHour3-CloseMin" id ="distributionHour3-CloseMin" /></td>
				
			</tr>		
			<tr>	
				<td><label for="city">Thursday </label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="distributionHour4-OpenHour" id ="distributionHour4-OpenHour"/></td>
				<td><label size="1">:</label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="distributionHour4-OpenMin" id ="distributionHour4-OpenMin"/></td>
				
				<td><label >  ---   </label></td>
				<td><input type="text" value="" size="2" maxlength="2" name="distributionHour4-CloseHour" id ="distributionHour4-CloseHour" /></td>
				<td><label size="1">:</label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="distributionHour4-CloseMin" id ="distributionHour4-CloseMin" /></td>
				
			</tr>		
			<tr>	
				<td><label for="city">Friday </label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="distributionHour5-OpenHour" id ="distributionHour5-OpenHour"/></td>
				<td><label size="1">:</label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="distributionHour5-OpenMin" id ="distributionHour5-OpenMin"/></td>
				
				<td><label >  ---   </label></td>
				<td><input type="text" value="" size="2" maxlength="2" name="distributionHour5-CloseHour" id ="distributionHour5-CloseHour" /></td>
				<td><label size="1">:</label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="distributionHour5-CloseMin" id ="distributionHour5-CloseMin" /></td>
				
			</tr>		
			<tr>	
				<td><label for="city">Saturday </label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="distributionHour6-OpenHour" id ="distributionHour6-OpenHour"/></td>
				<td><label size="1">:</label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="distributionHour6-OpenMin" id ="distributionHour6-OpenMin"/></td>
				
				<td><label >  ---   </label></td>
				<td><input type="text" value="" size="2" maxlength="2" name="distributionHour6-CloseHour" id ="distributionHour6-CloseHour" /></td>
				<td><label size="1">:</label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="distributionHour6-CloseMin" id ="distributionHour6-CloseMin" /></td>
				
			</tr>		
			<tr>	
				<td><label for="city">Sunday </label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="distributionHour7-OpenHour" id ="distributionHour7-OpenHour"/></td>
				<td><label size="1">:</label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="distributionHour7-OpenMin" id ="distributionHour7-OpenMin"/></td>
				
				<td><label >  ---   </label></td>
				<td><input type="text" value="" size="2" maxlength="2" name="distributionHour7-CloseHour" id ="distributionHour7-CloseHour" /></td>
				<td><label size="1">:</label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="distributionHour7-CloseMin" id ="distributionHour7-CloseMin" /></td>
				
			</tr>		
		</table>
	<div style="margin-top:5px;">
		<div><label for="grower11">Notes</label></div>
		<div><textarea name="note" id="distribution9" rows="5" cols="43"></textarea></div>
	</div>	
	</form>	
</div>	