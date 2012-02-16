<!-- all hidden forms go here -->
<div id="edit-dialog" class="hidden">
		
	<form id="volunteer" class="hidden">
		<h3>Volunteer</h3>
		<table>
		<tr>
			<td><label for="firstname" >First</label></td>
			<td><label for="middlename">Middle</label></td>
			<td><label for="lastname">Last</label></td>
		</tr>
		<tr>
			<td><input id="firstname" name="firstname" type="text" size="15"/></td>
			<td><input id="middlename" name="middlename" type="text" size="4"/></td>
			<td><input id="lastname" name="lastname" type="text" size="10"/></td>
		</tr>
		<tr>
			<td colspan="2"><label for="password">Password</label></td>
			<td><label for="signedup">Signed-Up</label></td>
		</tr>
		<tr>
			<td colspan="2"><input type="password" name="password" id="password" /></td>
			<td><input type="text" name="signedup" id="signedup" size="10" /></td>
		</tr>
		<tr>
			<td><label for="phone">Phone</label></td>
			<td colspan="2"><label for="email">Email</label></td>
		</tr>
		<tr>
			<td><input type="tel" name="phone" id="phone" size="12" /></td>
			<td colspan="2"><input type="text" name="email" id="email" /></td>
		</tr>
		<tr>
			<td><label for="street">Street</label></td>
			<td><label for="city">City</label></td>
			<td><label for="zip">Zip</label></td>
		</tr>
		<tr>
			<td><input type="text" name="street" id="street" size="12"/></td>
			<td><input type="text" name="city" id="city" size="4"/></td>
			<td><input type="text" name="zip" id="zip" size="8"/></td>
		</tr>
		<tr>
			<td><label for="status">Status</label></td>
			<td colspan="2"><label for="privilege">Privilege</label></td>
		</tr>
		<tr>
			<td>
				<select id="status" name="status">
					<option value="1">Active</option>
					<option value="0">Inactive</option>					
				</select>
			</td>
			<td colspan="2">
				<select id="privilege" name="privilege">
					<option value="1">Volunteer</option>	
					<option value="2">Harvest Captain</option>								
				</select>
			</td>
		</tr>
		</table>	
	
	<div style="margin-top:5px;">
		<div><label for="note">Notes</label></div>
		<div><textarea name="note" id="note" rows="10" cols="30"></textarea></div>
	</div>	
	</form>
	
		
	<form id="grower" class="hidden">
		<h3>Grower</h3>
		<div>
			<div><label for="firstname">First Name</label></div>
			<div><input type="text" name="firstname" id="firstname1" /></div>
		</div>
		<div>
			<div><label for="middlename">Middle Name</label></div>
			<div><input type="text" name="middlename" id="middlename1" /></div>
		</div>
		<div>
			<div><label for="lastname">Last Name</label></div>
			<div><input type="text" name="lastname" id="lastname1" /></div>
		</div>
		<div>
			<div><label for="phone">Phone</label></div>
			<div><input type="tel" name="phone" id="phone1" /></div>
		</div>
		<div>
			<div><label for="email">Email</label></div>
			<div><input type="text" name="email" id="email1" /></div>
		</div>
		<div>
			<div><label for="street">Street</label></div>
			<div><input type="text" name="street" id="street1" /></div>
		</div>	
		<div>
			<div><label for="city">City</label></div>
			<div><input type="text" name="city" id="city1" /></div>
		</div>	
		<div>
			<div><label for="zip">Zip</label></div>
			<div><input type="text" name="zip" id="zip1" /></div>
		</div>
		<div>
			<div><label for="tool">Tools</label></div>
			<div><input type="text" name="tool" id="tool" /></div>
		</div>		
		<div>
			<div><label for="hear">Hear</label></div>
			<div><input type="text" name="hear" id="hear" /></div>
		</div>
		<div>
			<div><label for="note">Notes</label></div>
			<div><input type="text" name="note" id="note" /></div>
		</div>	
		<div>
			<div><label for="pti">Property Type ID</label></div>
			<div><input type="text" name="pti" id="pti" /></div>
		</div>
		<div>
			<div><label for="pri">Property Relationship ID</label></div>
			<div><input type="text" name="pri" id="pri" /></div>
		</div>		
		</form>
</div>	