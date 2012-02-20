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
			<td><label for="volunteer2" >First</label></td>
			<td><label for="volunteer3">Middle</label></td>
			<td><label for="volunteer4">Last</label></td>
		</tr>
		<tr>
			<td><input id="volunteer2" name="firstname" type="text" size="12"/></td>
			<td><input id="volunteer3" name="middlename" type="text" size="4"/></td>
			<td><input id="volunteer4" name="lastname" type="text" size="10"/></td>
		</tr>
		<tr>
			<td colspan="2"><label for="volunteer7">Password</label></td>
			<td><label for="volunteer14">Signed-Up</label></td>
		</tr>
		<tr>
			<td colspan="2"><input type="password" name="password" id="volunteer7" /></td>
			<td><input type="text" name="signedup" id="volunteer14" size="10" /></td>
		</tr>
		<tr>
			<td><label for="volunteer5">Phone</label></td>
			<td colspan="2"><label for="volunteer6">Email</label></td>
		</tr>
		<tr>
			<td><input type="tel" name="phone" id="volunteer5" size="12" /></td>
			<td colspan="2"><input type="text" name="email" id="volunteer6" size="17" /></td>
		</tr>
		<tr>
			<td colspan="3"><label for="volunteer9">Street</label></td>			
		</tr>
		<tr>
			<td colspan="3"><input type="text" name="street" id="volunteer9" size="33"/></td>			
		</tr>
		<tr>
			<td><label for="volunteer10">City</label></td>
			<td><label for="volunteer11">State</label></td>
			<td><label for="volunteer12">Zip</label></td>
		</tr>
		<tr>			
			<td><input type="text" name="city" id="volunteer10" size="12"/></td>
			<td><input type="text" name="state" id="volunteer11" size="2"/></td>
			<td><input type="text" name="zip" id="volunteer12" size="8"/></td>
		</tr>
		<tr>
			<td><label for="volunteer8">Status</label></td>
			<td colspan="2"><label for="volunteer13">Privilege</label></td>
		</tr>
		<tr>
			<td>
				<select id="volunteer8" name="status">
					<option value="1">Active</option>
					<option value="0">Inactive</option>					
				</select>
			</td>
			<td colspan="2">
				<select id="volunteer13" name="privilege"> 
					<option value="1">Volunteer</option>	
					<option value="2">Harvest Captain</option>								
				</select>
			</td>
		</tr>
		</table>	
	
	<div style="margin-top:5px;">
		<div><label for="volunteer15">Notes</label></div>
		<div><textarea name="note" id="volunteer15" rows="5" cols="30"></textarea></div>
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
			<td><label for="grower12" >Hear</label></td>
		</tr>
		<tr>
			<td colspan="3"><input id="grower12" name="hear" type="text" size="20"/></td>			
		</tr>	
		<tr>
			<td colspan="3"><label for="grower14">Property Type</label></td>
		</tr>
		<tr>			
			<td colspan="3">
				<select id="grower14" name="property_type" style="width:200px;"> 
					<option value="1">Residence</option>	
					<option value="2">Open Space/ Vacant lot</option>
					<option value="3">Business</option>	
					<option value="4">Public Property</option>					
					<option value="5">Other</option>
				</select>
			</td >
		</tr>
		<tr>			
			<td colspan="3"><label for="grower15">Property Relationship</label></td>
		</tr>
		<tr>			
			<td colspan="3">
				<select id="grower15" name="property_relationship" style="width:200px;">
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
		<div><textarea name="note" id="grower13" rows="5" cols="30"></textarea></div>
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
				<td><input type="text" value="" size="2" maxlength="2" name="openHour" id ="distributionHour1-OpenHour"/></td>
				<td><label size="1">:</label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="openMin" id ="distributionHour1-OpenMin"/></td>
				
				<td><label >  ---   </label></td>
				<td><input type="text" value="" size="2" maxlength="2" name="closeHour" id ="distributionHour1-CloseHour" /></td>
				<td><label size="1">:</label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="closeMin" id ="distributionHour1-CloseMin" /></td>
				
			</tr>				
			<tr>	
				<td><label for="city">Tuesday </label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="openHour" id ="distributionHour2-OpenHour"/></td>
				<td><label size="1">:</label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="openMin" id ="distributionHour2-OpenMin"/></td>
				
				<td><label >  ---   </label></td>
				<td><input type="text" value="" size="2" maxlength="2" name="closeHour" id ="distributionHour2-CloseHour" /></td>
				<td><label size="1">:</label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="closeMin" id ="distributionHour2-CloseMin" /></td>
				
			</tr>			
			<tr>	
				<td><label for="city">Wednesday </label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="openHour" id ="distributionHour3-OpenHour"/></td>
				<td><label size="1">:</label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="openMin" id ="distributionHour3-OpenMin"/></td>
				
				<td><label >  ---   </label></td>
				<td><input type="text" value="" size="2" maxlength="2" name="closeHour" id ="distributionHour3-CloseHour" /></td>
				<td><label size="1">:</label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="closeMin" id ="distributionHour3-CloseMin" /></td>
				
			</tr>		
			<tr>	
				<td><label for="city">Thursday </label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="openHour" id ="distributionHour4-OpenHour"/></td>
				<td><label size="1">:</label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="openMin" id ="distributionHour4-OpenMin"/></td>
				
				<td><label >  ---   </label></td>
				<td><input type="text" value="" size="2" maxlength="2" name="closeHour" id ="distributionHour4-CloseHour" /></td>
				<td><label size="1">:</label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="closeMin" id ="distributionHour4-CloseMin" /></td>
				
			</tr>		
			<tr>	
				<td><label for="city">Friday </label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="openHour" id ="distributionHour5-OpenHour"/></td>
				<td><label size="1">:</label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="openMin" id ="distributionHour5-OpenMin"/></td>
				
				<td><label >  ---   </label></td>
				<td><input type="text" value="" size="2" maxlength="2" name="closeHour" id ="distributionHour5-CloseHour" /></td>
				<td><label size="1">:</label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="closeMin" id ="distributionHour5-CloseMin" /></td>
				
			</tr>		
			<tr>	
				<td><label for="city">Saturday </label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="openHour" id ="distributionHour6-OpenHour"/></td>
				<td><label size="1">:</label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="openMin" id ="distributionHour6-OpenMin"/></td>
				
				<td><label >  ---   </label></td>
				<td><input type="text" value="" size="2" maxlength="2" name="closeHour" id ="distributionHour6-CloseHour" /></td>
				<td><label size="1">:</label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="closeMin" id ="distributionHour6-CloseMin" /></td>
				
			</tr>		
			<tr>	
				<td><label for="city">Sunday </label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="openHour" id ="distributionHour7-OpenHour"/></td>
				<td><label size="1">:</label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="openMin" id ="distributionHour7-OpenMin"/></td>
				
				<td><label >  ---   </label></td>
				<td><input type="text" value="" size="2" maxlength="2" name="closeHour" id ="distributionHour7-CloseHour" /></td>
				<td><label size="1">:</label></td>			
				<td><input type="text" value="" size="2" maxlength="2" name="closeMin" id ="distributionHour7-CloseMin" /></td>
				
			</tr>		
		</table>
	<div style="margin-top:5px;">
		<div><label for="grower11">Notes</label></div>
		<div><textarea name="note" id="distribution9" rows="5" cols="43"></textarea></div>
	</div>	
	</form>	
</div>	