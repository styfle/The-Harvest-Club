<!-- all hidden forms go here -->
<div id="edit-dialog" class="hidden">
		
	<form id="volunteer" class="hidden">
		<h3>Volunteer</h3>
		<table>
		<tr>
			<td><label for="volunteer1" >First</label></td>
			<td><label for="volunteer2">Middle</label></td>
			<td><label for="volunteer3">Last</label></td>
		</tr>
		<tr>
			<td><input id="volunteer1" name="firstname" type="text" size="12"/></td>
			<td><input id="volunteer2" name="middlename" type="text" size="4"/></td>
			<td><input id="volunteer3" name="lastname" type="text" size="10"/></td>
		</tr>
		<tr>
			<td colspan="2"><label for="volunteer4">Password</label></td>
			<td><label for="volunteer13">Signed-Up</label></td>
		</tr>
		<tr>
			<td colspan="2"><input type="password" name="password" id="volunteer6" /></td>
			<td><input type="text" name="signedup" id="volunteer13" size="10" /></td>
		</tr>
		<tr>
			<td><label for="volunteer6">Phone</label></td>
			<td colspan="2"><label for="volunteer7">Email</label></td>
		</tr>
		<tr>
			<td><input type="tel" name="phone" id="volunteer4" size="12" /></td>
			<td colspan="2"><input type="text" name="email" id="volunteer5" size="17" /></td>
		</tr>
		<tr>
			<td colspan="3"><label for="volunteer8">Street</label></td>			
		</tr>
		<tr>
			<td colspan="3"><input type="text" name="street" id="volunteer8" size="33"/></td>			
		</tr>
		<tr>
			<td><label for="volunteer9">City</label></td>
			<td><label for="volunteer10">State</label></td>
			<td><label for="volunteer11">Zip</label></td>
		</tr>
		<tr>			
			<td><input type="text" name="city" id="volunteer9" size="12"/></td>
			<td><input type="text" name="state" id="volunteer10" size="2"/></td>
			<td><input type="text" name="zip" id="volunteer11" size="8"/></td>
		</tr>
		<tr>
			<td><label for="volunteer7">Status</label></td>
			<td colspan="2"><label for="volunteer12">Privilege</label></td>
		</tr>
		<tr>
			<td>
				<select id="volunteer7" name="status">
					<option value="1">Active</option>
					<option value="0">Inactive</option>					
				</select>
			</td>
			<td colspan="2">
				<select id="volunteer12" name="privilege"> 
					<option value="1">Volunteer</option>	
					<option value="2">Harvest Captain</option>								
				</select>
			</td>
		</tr>
		</table>	
	
	<div style="margin-top:5px;">
		<div><label for="volunteer14">Notes</label></div>
		<div><textarea name="note" id="volunteer14" rows="5" cols="30"></textarea></div>
	</div>	
	</form>
	
	<form id="grower" class="hidden">
		<h3>Grower</h3>
		<table>
		<tr>
			<td><label for="grower1" >First</label></td>
			<td><label for="middlename">Middle</label></td>
			<td><label for="grower2">Last</label></td>
		</tr>
		<tr>
			<td><input id="grower1" name="firstname" type="text" size="12"/></td>
			<td><input id="middlename" name="middlename" type="text" size="4"/></td>
			<td><input id="grower2" name="lastname" type="text" size="10"/></td>
		</tr>		
		<tr>
			<td><label for="grower3">Phone</label></td>
			<td colspan="2"><label for="grower4">Email</label></td>
		</tr>
		<tr>
			<td><input type="tel" name="phone" id="grower3" size="12" /></td>
			<td colspan="2"><input type="text" name="email" id="grower4" size="17" /></td>
		</tr>
		<tr>
			<td colspan="3"><label for="grower5">Street</label></td>			
		</tr>
		<tr>
			<td colspan="3"><input type="text" name="street" id="grower5" size="33"/></td>			
		</tr>
		<tr>
			<td><label for="grower6">City</label></td>
			<td><label for="grower7">State</label></td>
			<td><label for="grower8">Zip</label></td>
		</tr>
		<tr>			
			<td><input type="text" name="city" id="grower6" size="12"/></td>
			<td><input type="text" name="state" id="grower7" size="2"/></td>
			<td><input type="text" name="zip" id="grower8" size="8"/></td>
		</tr>
		<tr>
			<td><label for="grower10" >Hear</label></td>
		</tr>
		<tr>
			<td colspan="3"><input id="grower10" name="hear" type="text" size="20"/></td>			
		</tr>	
		<tr>
			<td colspan="3"><label for="grower12">Property Type</label></td>
		</tr>
		<tr>			
			<td colspan="3">
				<select id="grower12" name="property_type" style="width:200px;"> 
					<option value="1">Residence</option>	
					<option value="2">Open Space/ Vacant lot</option>
					<option value="3">Business</option>	
					<option value="4">Public Property</option>					
					<option value="5">Other</option>
				</select>
			</td >
		</tr>
		<tr>			
			<td colspan="3"><label for="grower13">Property Relationship</label></td>
		</tr>
		<tr>			
			<td colspan="3">
				<select id="grower13" name="property_relationship" style="width:200px;">
					<option value="1">Owner & Occupant</option>	
					<option value="2">Renter</option>
					<option value="3">Renter property owner(landlord)</option>	
					<option value="4">Other</option>													
				</select>
			</td>
		</tr>		
		
		</table>	
	<div style="margin-top:5px;">
		<div><label for="grower9">Tools</label></div>
		<div><textarea name="tools" id="grower9" rows="5" cols="30"></textarea></div>
	</div>	
	<div style="margin-top:5px;">
		<div><label for="grower11">Notes</label></div>
		<div><textarea name="note" id="grower11" rows="5" cols="30"></textarea></div>
	</div>	
	</form>	
</div>	