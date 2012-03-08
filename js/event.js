var optionSelect = '<option value="" disabled="disabled" selected="selected"></option>';
function options(data) {
		var s = optionSelect; // first option is always select...
		for (var i=0; i<data.length; i++) {
			var o = data[i];
			s += '<option value="'+o.id+'">'+o.name+'</option>';
		}
		return s;
	}

function addTreeRow(tableID) {

	var table = document.getElementById(tableID);
	
	if(table.rows.length==0)
	{
	 var row = table.insertRow(0);
	 var cell1 = row.insertCell(0);
	 var label1 = document.createElement("label");
	 label1.style.width = "2em"
	 var txt1=document.createTextNode('');
	 label1.appendChild(txt1);
	 cell1.appendChild(label1);
	 
	 var cell2 = row.insertCell(1);
	 var label2 = document.createElement("label");
	 label2.style.width = "5em";
	 var txt2=document.createTextNode('Tree');
	 label2.style.fontWeight = 'bold';
	 label2.appendChild(txt2);
	 cell2.appendChild(label2);
	 
	 var cell3 = row.insertCell(2);
	 var label3 = document.createElement("label");
	 label3.style.width = "5em";
	 label3.style.fontWeight = 'bold';
	 var txt3=document.createTextNode('No.');
	 label3.appendChild(txt3);
	 cell3.appendChild(label3);
	 
	 var cell4 = row.insertCell(3);
	 var label4 = document.createElement("label");
	 label4.style.width = "5em";
	 label4.style.fontWeight = 'bold';
	 var txt4=document.createTextNode('Pound');
	 label4.appendChild(txt4);
	 cell4.appendChild(label4);
	}

	var rowCount = table.rows.length;
	var row = table.insertRow(rowCount);

	var cell1 = row.insertCell(0);
	var element1 = document.createElement("input");
	element1.type = "checkbox";
	cell1.appendChild(element1);

	var cell2 = row.insertCell(1);
	var element2 = document.createElement("select");
	element2.innerHTML = (options(treeNames));
	cell2.appendChild(element2);
	
	
	
	var cell3 = row.insertCell(2);
	var element3 = document.createElement("input");
	element3.type = "text";
	element3.style.width = "4em"
	cell3.appendChild(element3);
	
	var cell4 = row.insertCell(3);
	var element4 = document.createElement("input");
	element4.type = "text";
	element4.style.width = "4em"
	cell4.appendChild(element4);
	

}

function deleteTreeRow(tableID) {
	try {
	var table = document.getElementById(tableID);
	var rowCount = table.rows.length;

	for(var i=0; i<rowCount; i++) {
		var row = table.rows[i];
		var chkbox = row.cells[0].childNodes[0];
		if(null != chkbox && true == chkbox.checked) {
			table.deleteRow(i);
			rowCount--;
			i--;
		}

	}
	}catch(e) {
		alert(e);
	}
}
		
function addVolunteerRow(tableID) {

	        var table = document.getElementById(tableID);
			
			 
			
			var rowCount = table.rows.length;

            var row = table.insertRow(rowCount);
			// New table
			var c = row.insertCell(0);
            var tbl = document.createElement("table");
			tbl.style.backgroundColor = "#E3E4FA";
            c.appendChild(tbl);
			
			var row = tbl.insertRow(0);
			 var cell1 = row.insertCell(0);
			 var label1 = document.createElement("label");
			 label1.style.width = "2em"
			 var txt1=document.createTextNode('');
			 label1.appendChild(txt1);
			 cell1.appendChild(label1);
			 
			 var cell2 = row.insertCell(1);
			 var label2 = document.createElement("label");
			 label2.style.width = "5em";
			 var txt2=document.createTextNode('Volunteer');
			 label2.style.fontWeight = 'bold';
			 label2.appendChild(txt2);
			 cell2.appendChild(label2);			 
			 
			 
			 var cell3 = row.insertCell(2);
			 var label3 = document.createElement("label");
			 label3.style.width = "5em";
			 label3.style.fontWeight = 'bold';
			 var txt3=document.createTextNode('Hours');
			 label3.appendChild(txt3);
			 cell3.appendChild(label3);
			 
			 var cell4 = row.insertCell(3);
			 var label4 = document.createElement("label");
			 label4.style.width = "5em";
			 label4.style.fontWeight = 'bold';
			 var txt4=document.createTextNode('Driver');
			 label4.appendChild(txt4);
			 cell4.appendChild(label4);
			 
			var row1 = tbl.insertRow(1);
		    var cell1 = row1.insertCell(0);
            var element1 = document.createElement("input");
            element1.type = "checkbox";
            cell1.appendChild(element1);
			
			element1.focus();
 
            var cell2 = row1.insertCell(1);
			var element2 = document.createElement("select");
			element2.innerHTML = (options(volunteerNames));
			cell2.appendChild(element2);
				
            var cell3 = row1.insertCell(2);
            var element3 = document.createElement("input");
            element3.type = "text";
			element3.style.width = "4em"
            cell3.appendChild(element3);
			
			var cell4 = row1.insertCell(3);
            var element4 = document.createElement("input");
            element4.type = "checkbox";
			element4.style.width = "4em"
            cell4.appendChild(element4);			
					
			
			element4.checked = false;
			element4.onclick = function() { 
			  if (element4.checked)
			  {			  
			  			
				var row2 = tbl.insertRow(2);
				var cell5 = row2.insertCell(0);
				var element5 = document.createElement("input");
				element5.type = "checkbox";
				cell5.appendChild(element5);
				element5.style.visibility="hidden";
				  
				var cell6 = row2.insertCell(1);
				var label1 = document.createElement("label");
				label1.style.width = "5em";
				label1.style.color = 'blue';
				label1.style.font='Arial';
				var txt1=document.createTextNode('Tree Types');
				label1.appendChild(txt1);
				cell6.appendChild(label1);
				
				var cell7 = row2.insertCell(2);
				var buttonnode= document.createElement('input');
				buttonnode.setAttribute('type','button');
				buttonnode.setAttribute('name','button'+1);
				buttonnode.setAttribute('value','+');
				buttonnode.onclick = function()
									{
										var rowCount = tbl.rows.length;
										var r = tbl.insertRow(rowCount);
										
										var c1 = r.insertCell(0);									
										var e1 = document.createElement("input");
										e1.type = "checkbox";
										c1.appendChild(e1);
										e1.focus();
										
										var c2 = r.insertCell(1);
										var e2 = document.createElement("select");
										e2.innerHTML = (options(treeNames));
										c2.appendChild(e2);
										
										var c3 = r.insertCell(2);
										var e3 = document.createElement("input");
										e3.type = "text";
										e3.style.width = "4em";
										e3.placeholder = "Pounds";
										c3.appendChild(e3);
										
										var c4 = r.insertCell(3);									
										var e4 = document.createElement("label");
										var txt=document.createTextNode('------>');
										e4.style.textAlign="center";
										e4.appendChild(txt);		
										c4.appendChild(e4);
										
										var c5 = r.insertCell(4);
										var e5 = document.createElement("select");
										e5.innerHTML = (options(distributionNames));
										c5.appendChild(e5);
											
										
									};
				cell7.appendChild(buttonnode);
				
				var cell8 = row2.insertCell(3);
				var buttonnode2= document.createElement('input');
				buttonnode2.setAttribute('type','button');
				buttonnode2.setAttribute('name','button'+2);
				buttonnode2.setAttribute('value','-');
				buttonnode2.onclick = function()
										{
											var rowCount = tbl.rows.length;
											for(var i=3; i<rowCount; i++) {
												var row = tbl.rows[i];
												var chkbox = row.cells[0].childNodes[0];
												if(null != chkbox && true == chkbox.checked) {
													tbl.deleteRow(i);
													rowCount--;
													i--;
												}
											}
										
										};
				cell8.appendChild(buttonnode2);
			  }
			  else
			  {



			    var rowCount = tbl.rows.length;
				for(var i=2; i<rowCount; i++) {
					var row = tbl.rows[i];
					var chkbox = row.cells[0].childNodes[0];
					chkbox.checked = true;
				}
				deleteTreeTypeRow(tbl);
			  }
			};


			
 
        }
 
        function deleteVolunteerRow(tableID) {
            try {
            var table = document.getElementById(tableID);
            var rowCount = table.rows.length;

            for(var i=0; i<rowCount; i++) {
                var r = table.rows[i];
				var tbl = r.cells[0].childNodes[0];
                var chkbox = tbl.rows[1].cells[0].childNodes[0];
                if(null != chkbox && true == chkbox.checked) {
                    table.deleteRow(i);
                    rowCount--;
                    i--;
                }
 
            }
            }catch(e) {
                alert(e);
            }
        }
		
		function deleteTreeTypeRow(table) {
            try {
            var rowCount = table.rows.length;
			for(var i=2; i<rowCount; i++) {
            var row = table.rows[i];
            var chkbox = row.cells[0].childNodes[0];
                if(null != chkbox && true == chkbox.checked) {
                    table.deleteRow(i);
                    rowCount--;
                    i--;
                }
 
            }
            }catch(e) {
                alert(e);
            }
        }
function loadGrowerToForm(grower_id)
	{
		growerPhone.length = 0;
		growerAddress.length = 0;
		growerCity.length = 0;
		$.ajax( {
						'dataType': 'json', 
						'type': 'GET', 
						'url': 'ajax.php?cmd=get_grower_name', 
						'success': function (data) {
							var str = '<select id="event-grower-name" name="event-grower-name">';
							str += '<option value=0 selected="selected">Select a grower...</option>';
							if( data.datatable != null) 							
								for ( var i=0, len = data.datatable.aaData.length; i< len; ++i )
								{
									str += '<option value="'+data.datatable.aaData[i][0]+'">'+data.datatable.aaData[i][1]+'</option>';
									growerPhone.push(data.datatable.aaData[i][2]);
									growerAddress.push(data.datatable.aaData[i][3]);
									growerCity.push(data.datatable.aaData[i][4]);
								}
								str += '</select>';	
								$('#event-grower').append(str);
								getGrower(grower_id);
						},
						'error': function (e) {
							alert('Ajax Error!\n' + e.responseText);
						}
					});
	}
	
	function getGrower(grower_id){
		$('#event-grower-name').val(grower_id).attr('selected',true);
		$('#event7').val(growerCity[(grower_id-1)]);
		$('#event8').val(growerPhone[(grower_id-1)]);
		$('#event9').val(growerAddress[(grower_id-1)]);
		
	}
	
	function loadVolunteerToForm(formName, captain_id)
	{
		$.ajax( {
						'dataType': 'json', 
						'type': 'GET', 
						'url': 'ajax.php?cmd=get_volunteer_name', 
						'success': function (data) {
							var str = '<select id="event-captain-name" name="event-captain-name">';							
							str += '<option value=0 selected="selected"> Select a Captain...</option>';							
							if( data.datatable != null) 							
								for ( var i=0, len = data.datatable.aaData.length; i< len; ++i )
								//if ( (i+1) == row[4])
									str += '<option value="'+data.datatable.aaData[i][0]+'">'+data.datatable.aaData[i][1]+'</option>';
								//else
								//	str += '<option value="'+data.datatable.aaData[i][0]+'">'+data.datatable.aaData[i][1]+'</option>';
								str += '</select>';		

								formName.append(str);
								getCaptain(captain_id);
						},
						'error': function (e) {
							alert('Ajax Error!\n' + e.responseText);
						}
					});	
	}
	
	function getCaptain(captain_id)
	{	
	  $('#event-captain-name').val(captain_id).attr('selected',true);	
	}
	
	function deleteAllTreeRows()
	{
		var table = document.getElementById("eventTree");
		var rowCount = table.rows.length;	
 		for(var i=0; i<rowCount; i++) 
		{
            table.deleteRow(i);
			rowCount--;
			i--;
		}
		loadTreeType = 0;
	}
	
	function deleteAllVolunteerRows()
	{
		var table = document.getElementById("eventVolunteer");
		var rowCount = table.rows.length;	
 		for(var i=0; i<rowCount; i++) 
		{
            table.deleteRow(i);
			rowCount--;
			i--;
		}
		loadVolunteer = 0;
	}
	
	function loadTree(id, event_id)
	{
		$.ajax( {
							'dataType': 'json', 
							'type': 'GET', 
							'url': 'ajax.php?cmd=get_tree_name&grower_id='+ id, 
							'success': function (data) {
								var str = '<select id="event-grower-name" name="event-grower-name">';
								if( data.datatable != null) 	
								{								
									for ( var i=0, len = data.datatable.aaData.length; i< len; ++i )
									{
										var v = {
											"id": data.datatable.aaData[i][0],
											"name": data.datatable.aaData[i][1]
										};
										treeNames.push(v);								
									}
								
									getTreeType(id,event_id);
								}
										
							},
							'error': function (e) {
								alert('Ajax Error!\n' + e.responseText);
							}
						});
		//console.log(treeNames);
	}
	
	function getTreeType(grower_id, event_id){
		$.ajax( {
						'dataType': 'json', 
						'type': 'GET', 
						'url': 'ajax.php?cmd=get_event_tree&id='+grower_id+'&event_id='+event_id, 
						'success': function (data) {
							var table = document.getElementById("eventTree");
							if( data.datatable != null) 							
								for ( var i=0, len = data.datatable.aaData.length; i< len; ++i )
									{
										addTreeRow('eventTree');
										table.rows[i+1].cells[1].childNodes[0].value = data.datatable.aaData[i][0];
										table.rows[i+1].cells[2].childNodes[0].value = data.datatable.aaData[i][1];	
										table.rows[i+1].cells[3].childNodes[0].value = data.datatable.aaData[i][2];										
									}
						},
						'error': function (e) {
							alert('Ajax Error!\n' + e.responseText);
						}
					});	
	}
	
	function loadVolunteerName(event_id){
		$.ajax( {
				'dataType': 'json', 
				'type': 'GET', 
				'url': 'ajax.php?cmd=get_volunteer_name', 
				'success': function (data) {
					//var str = '<select id="event-volunteer-name" name="event-volunteer-name">';
					if( data.datatable != null) 	
					{								
						for ( var i=0, len = data.datatable.aaData.length; i< len; ++i )
						{
							var v = {
								"id": data.datatable.aaData[i][0],
								"name": data.datatable.aaData[i][1]
							};
							volunteerNames.push(v);								
						}
					
						getEventVolunteer(event_id);
					}
							
				},
				'error': function (e) {
					alert('Ajax Error!\n' + e.responseText);
				}
			});
	}
	
	function getEventVolunteer(event_id){
		$.ajax( {
						'dataType': 'json', 
						'type': 'GET', 
						'url': 'ajax.php?cmd=get_event_volunteer_name&event_id='+event_id, 
						'success': function (data) {
							//console.log(data);
							var tbl = document.getElementById("eventVolunteer");		
							if( data.datatable != null) 							
								for ( var i=0, len = data.datatable.aaData.length; i< len; ++i )
									{
										addVolunteerRow('eventVolunteer');
										var r = tbl.rows[i];
										var table = r.cells[0].childNodes[0];										
										table.rows[1].cells[1].childNodes[0].value = data.datatable.aaData[i][0];	
										table.rows[1].cells[2].childNodes[0].value = data.datatable.aaData[i][3];	
										if (data.datatable.aaData[i][2] == 1)
										{
											table.rows[1].cells[3].childNodes[0].checked = true;
											var row2 = table.insertRow(2);
											
											var cell5 = row2.insertCell(0);
											var element5 = document.createElement("input");
											element5.type = "checkbox";
											cell5.appendChild(element5);
											element5.style.visibility="hidden";
											  
											var cell6 = row2.insertCell(1);
											var label1 = document.createElement("label");
											label1.style.width = "5em";
											label1.style.color = 'blue';
											label1.style.font='Arial';
											var txt1=document.createTextNode('Tree Types');
											label1.appendChild(txt1);
											cell6.appendChild(label1);
											
											var cell7 = row2.insertCell(2);
											var buttonnode= document.createElement('input');
											buttonnode.setAttribute('type','button');
											buttonnode.setAttribute('name','button'+1);
											buttonnode.setAttribute('value','+');
											buttonnode.onclick = function()
																{
																	var rowCount = table.rows.length;
																	var r = table.insertRow(rowCount);
																	
																	var c1 = r.insertCell(0);									
																	var e1 = document.createElement("input");
																	e1.type = "checkbox";
																	c1.appendChild(e1);
																	e1.focus();
																	
																	var c2 = r.insertCell(1);
																	var e2 = document.createElement("select");
																	e2.innerHTML = (options(treeNames));
																	c2.appendChild(e2);
																	
																	var c3 = r.insertCell(2);
																	var e3 = document.createElement("input");
																	e3.type = "text";
																	e3.style.width = "4em";
																	e3.placeholder = "Pounds";
																	c3.appendChild(e3);
																	
																	var c4 = r.insertCell(3);									
																	var e4 = document.createElement("label");
																	var txt=document.createTextNode('------>');
																	e4.style.textAlign="center";
																	e4.appendChild(txt);		
																	c4.appendChild(e4);
																	
																	var c5 = r.insertCell(4);
																	var e5 = document.createElement("select");
																	e5.innerHTML = (options(distributionNames));
																	c5.appendChild(e5);
																		
																	
																};
											cell7.appendChild(buttonnode);
											
											var cell8 = row2.insertCell(3);
											var buttonnode2= document.createElement('input');
											buttonnode2.setAttribute('type','button');
											buttonnode2.setAttribute('name','button'+2);
											buttonnode2.setAttribute('value','-');
											buttonnode2.onclick = function()
																	{
																		var rowCount = table.rows.length;
																		for(var i=3; i<rowCount; i++) {
																			var row = table.rows[i];
																			var chkbox = row.cells[0].childNodes[0];
																			if(null != chkbox && true == chkbox.checked) {
																				table.deleteRow(i);
																				rowCount--;
																				i--;
																			}
																		}
																	
																	};
											cell8.appendChild(buttonnode2);											
										    getDriverData(data.datatable.aaData[i][0], table);										
										}
									}
						},
						'error': function (e) {
							alert('Ajax Error!\n' + e.responseText);
						}
					});	
	}
	
	function getDriverData(volunteer_id, tbl){
		$.ajax( {
						'dataType': 'json', 
						'type': 'GET', 
						'url': 'ajax.php?cmd=get_driver&id='+volunteer_id, 
						'success': function (data) {							
							
							if( data.datatable != null) 							
								for ( var i=0, len = data.datatable.aaData.length; i< len; ++i )
									{											
										var r = tbl.insertRow(i+3);
										
										var c1 = r.insertCell(0);									
										var e1 = document.createElement("input");
										e1.type = "checkbox";
										c1.appendChild(e1);
										e1.focus();
										
										var c2 = r.insertCell(1);
										var e2 = document.createElement("select");
										e2.innerHTML = (options(treeNames));
										c2.appendChild(e2);
										
										var c3 = r.insertCell(2);
										var e3 = document.createElement("input");
										e3.type = "text";
										e3.style.width = "4em";
										e3.placeholder = "Pounds";
										c3.appendChild(e3);
										
										var c4 = r.insertCell(3);									
										var e4 = document.createElement("label");
										var txt=document.createTextNode('------>');
										e4.style.textAlign="center";
										e4.appendChild(txt);		
										c4.appendChild(e4);
										
										var c5 = r.insertCell(4);
										var e5 = document.createElement("select");
										e5.innerHTML = (options(distributionNames));
										c5.appendChild(e5);							
										tbl.rows[i+3].cells[1].childNodes[0].value = data.datatable.aaData[i][1];
										tbl.rows[i+3].cells[2].childNodes[0].value = data.datatable.aaData[i][4];
										tbl.rows[i+3].cells[4].childNodes[0].value = data.datatable.aaData[i][3];
									}
							
							
						},
						'error': function (e) {
							alert('Ajax Error!\n' + e.responseText);
						}
					});
	}
	
	function loadDistributionName(){
		$.ajax( {
				'dataType': 'json', 
				'type': 'GET', 
				'url': 'ajax.php?cmd=get_distribution_name', 
				'success': function (data) {
					var str = '<select id="event-distribution-name" name="event-distribution-name">';
					if( data.datatable != null) 	
					{								
						for ( var i=0, len = data.datatable.aaData.length; i< len; ++i )
						{
							var v = {
								"id": data.datatable.aaData[i][0],
								"name": data.datatable.aaData[i][1]
							};
							distributionNames.push(v);								
						}
					
						//getEventVolunteer(event_id);
					}
							
				},
				'error': function (e) {
					alert('Ajax Error!\n' + e.responseText);
				}
			});
	}
	
	function updateEvent(){
		var event_id =  row[1];				
		var event_date =  $('#event4').val();		
		var event_time =  $('#event5').val();
		var event_notes =  $('#event6').val();
		var grower_id =  $('#event-grower option:selected').val();
		var captain_id = $('#event-captain option:selected').val();
		var tree_type = [];
		var volunteers = [];
		
		var table = document.getElementById("eventTree");
		var rowCount = table.rows.length;
		for(var i=1; i<rowCount; i++)
			if((table.rows[i].cells[1].childNodes[0].value !="") && (table.rows[i].cells[2].childNodes[0].value !="") && (table.rows[i].cells[3].childNodes[0].value !=""))
			{
				var data = {
							"tree_id": table.rows[i].cells[1].childNodes[0].value,
							"number": table.rows[i].cells[2].childNodes[0].value,
							"pound": table.rows[i].cells[3].childNodes[0].value
							};
				
				tree_type.push(data); 
			}
		
		var table = document.getElementById("eventVolunteer");
		var rowCount = table.rows.length;
		for(var i=0; i<rowCount; i++) 
			{
				var r = table.rows[i];
				var tbl = r.cells[0].childNodes[0];
                var drv = tbl.rows[1].cells[3].childNodes[0].checked;
				if((tbl.rows[1].cells[1].childNodes[0].value !="") && (tbl.rows[1].cells[2].childNodes[0].value !=""))
				if (drv == true) 
				{
					var distributedTree = [];
					for(var j=3; j< tbl.rows.length; j++)
					{
						var data = {					
									"tree_id": tbl.rows[j].cells[1].childNodes[0].value,
									"pound": tbl.rows[j].cells[2].childNodes[0].value,
									"distribution_id": tbl.rows[j].cells[4].childNodes[0].value
									};
						
						distributedTree.push(data); 
					}
					var data = {
									"volunteer_id": tbl.rows[1].cells[1].childNodes[0].value,
									"hour": tbl.rows[1].cells[2].childNodes[0].value,
									"driver": tbl.rows[1].cells[3].childNodes[0].checked,
									"distributedTree": distributedTree,									
								};
						
						volunteers.push(data); 
				}
				else // driver not checked
					{
						var data = {
									"volunteer_id": tbl.rows[1].cells[1].childNodes[0].value,
									"hour": tbl.rows[1].cells[2].childNodes[0].value,
									"driver": tbl.rows[1].cells[3].childNodes[0].checked,									
									};
						
						volunteers.push(data); 
					
					}
			}			
		
		
		var data = {
					event_id: event_id,					
					event_date: event_date,
					event_time: event_time,
					event_notes: event_notes,					
					grower_id : grower_id,
					captain_id : captain_id,
					treeType : tree_type,
					volunteers : volunteers
					};
					
		$.ajax( {
						type: 'post', 
						url: 'ajax.php?cmd=update_event', 
						data: data,
						'success': function (data) {
							setInfo('Information Updated');
							$('#edit-dialog').dialog('close');
						},
						'error': function (e) {
							alert('Ajax Error!\n' + e.responseText);
						}
					});	
		
	}
	
	function createNewEvent(){			
		var event_date =  $('#event4').val();
		var event_time =  $('#event5').val();
		var event_notes =  $('#event6').val();
		var grower_id =  $('#event-grower option:selected').val();
		var captain_id = $('#event-captain option:selected').val();
		var tree_type = [];
		var volunteers = [];
		
		var table = document.getElementById("eventTree");
		var rowCount = table.rows.length;
		for(var i=1; i<rowCount; i++)
			if((table.rows[i].cells[1].childNodes[0].value !="") && (table.rows[i].cells[2].childNodes[0].value !="") && (table.rows[i].cells[3].childNodes[0].value !=""))
			{
				var data = {
							"tree_id": table.rows[i].cells[1].childNodes[0].value,
							"number": table.rows[i].cells[2].childNodes[0].value,
							"pound": table.rows[i].cells[3].childNodes[0].value
							};
				
				tree_type.push(data); 
			}
		
		var table = document.getElementById("eventVolunteer");
		var rowCount = table.rows.length;
		for(var i=0; i<rowCount; i++) 
			{
				var r = table.rows[i];
				var tbl = r.cells[0].childNodes[0];
                var drv = tbl.rows[1].cells[3].childNodes[0].checked;
				//if((table.rows[i].cells[1].childNodes[0].value !="") && (table.rows[i].cells[2].childNodes[0].value !=""))
				if (drv == true) 
				{
					var distributedTree = [];
					for(var j=3; j< tbl.rows.length; j++)
					{
						var data = {					
									"tree_id": tbl.rows[j].cells[1].childNodes[0].value,
									"pound": tbl.rows[j].cells[2].childNodes[0].value,
									"distribution_id": tbl.rows[j].cells[4].childNodes[0].value
									};
						
						distributedTree.push(data); 
					}
					var data = {
									"volunteer_id": tbl.rows[1].cells[1].childNodes[0].value,
									"hour": tbl.rows[1].cells[2].childNodes[0].value,
									"driver": tbl.rows[1].cells[3].childNodes[0].checked,
									"distributedTree": distributedTree,									
								};
						
						volunteers.push(data); 
				}
				else // driver not checked
					{
						var data = {
									"volunteer_id": tbl.rows[1].cells[1].childNodes[0].value,
									"hour": tbl.rows[1].cells[2].childNodes[0].value,
									"driver": tbl.rows[1].cells[3].childNodes[0].checked,									
									};
						
						volunteers.push(data); 
					
					}
			}			
		
		
		
		var data = {
					event_id: event_id,					
					event_date: event_date,
					event_time: event_time,
					event_notes: event_notes,					
					grower_id : grower_id,
					captain_id : captain_id,
					treeType : tree_type,
					volunteers : volunteers
					};
		$.ajax( {
						type: 'post', 
						url: 'ajax.php?cmd=create_event', 
						data: data,
						'success': function (data) {
							setInfo('Information Updated');
							$('#edit-dialog').dialog('close');
						},
						'error': function (e) {
							alert('Ajax Error!\n' + e.responseText);
						}
					});	
	
	}
	
	function checkEventForm(){	
		
		if ($('#event4').val() =="")
		{
			alert("Date can't be empty!");
			return -1;
		}
		
		var table = document.getElementById("eventTree");
		var rowCount = table.rows.length;
		for(var i=1; i<rowCount; i++)
		{
			if(table.rows[i].cells[1].childNodes[0].value =="")
			{
				alert("Tree Type can't be empty!");
				return -1;
			}
			
			if(table.rows[i].cells[2].childNodes[0].value =="")
			{
				alert("Tree Pound can't be empty!");
				return -1;
			}
			
			if (isNaN(parseFloat(table.rows[i].cells[2].childNodes[0].value)))
			{
				alert("Tree Pound must be a number!");
				return -1;
			}
			
			if (parseFloat(table.rows[i].cells[2].childNodes[0].value) < 0)
			{
				alert("Tree Pound must be greater than zero!");
				return -1;
			}
		}
		/*
		var table = document.getElementById("eventVolunteer");
		var rowCount = table.rows.length;
		for(var i=1; i<rowCount; i++) 
		{
			if((table.rows[i].cells[1].childNodes[0].value ==""))
			{
				alert("Volunteer Name can't be empty!");
				return -1;
			}
			
			if((table.rows[i].cells[2].childNodes[0].value ==""))
			{
				alert("Hours can't be empty!");
				return -1;
			}
			
			if (isNaN(parseFloat(table.rows[i].cells[2].childNodes[0].value)))
			{
				alert("Hours must be a number!");
				return -1;
			}
			
			if (parseFloat(table.rows[i].cells[2].childNodes[0].value) < 0)
			{
				alert("Hours must be greater than zero!");
				return -1;
			}
			
		
			if (table.rows[i].cells[3].childNodes[0].checked)
			{
			
				if(table.rows[i].cells[4].childNodes[0].value =="")
				{
					alert("Tree Type can't be empty!");
					return -1;
				}
				
				
				if(table.rows[i].cells[5].childNodes[0].value =="")
				{
					alert("Tree Pound can't be empty!");
					return -1;
				}
				
				if (isNaN(parseFloat(table.rows[i].cells[5].childNodes[0].value)))
				{
					alert("Tree Pound must be a number!");
					return -1;
				}
				
				if (parseFloat(table.rows[i].cells[5].childNodes[0].value) < 0)
				{
					alert("Tree Pound must be greater than zero!");
					return -1;
				}
				
				if(table.rows[i].cells[6].childNodes[0].value =="")
				{
					alert("Distribution can't be empty!");
					return -1;
				}
			}
			
		}
		*/	
		return 0;
	}
	
	function loadAllEventForm(event_id, grower_id, captain_id)
	{		
		deleteAllTreeRows();
		treeNames.length = 0;
		deleteAllVolunteerRows();
		loadDistribution = 0;
		switchForm('event');		
			
						
						
		if (loadDistribution == 0)
		{
			distributionNames.length = 0;
			loadDistributionName();
			loadDistribution++;
		}
			
		if(loadGrower == 0)
		{
			loadGrowerToForm(grower_id);
			loadGrower++;
		}
		else
			getGrower(grower_id);
		
		if (loadCaptain ==0)
		{
			loadVolunteerToForm($('#event-captain'), captain_id);
			loadCaptain++;
		}
		else
			getCaptain(captain_id);
			
		if (loadTreeType ==0)
		{
			loadTree(grower_id,event_id);
			loadTreeType++;
		}
		else
			getTreeType(grower_id, event_id);
		
		if (loadVolunteer == 0)
		{
			volunteerNames.length = 0;
			loadVolunteerName(event_id);
			loadVolunteer++;
		}
		else
			getEventVolunteer(event_id);
		
		
		$('#event4').not('.hasDatePicker').datepicker({dateFormat: 'yy-mm-dd'});		

		
		
	}

// This  is for distribution form

function pad2(n) {
	n += ''; //cast to str
	while (n.length < 2)
		n = '0' + n;
	return n;
}
	
function initHours() {	
	var t = [];
	for (var i=0;i<24; ++i) {
		var n = pad2(i);
		t.push({
			"id": n,
			"name": n
		});
	}

	var hString=options(t);
	for (var i=24;i< 60; ++i) {
		t.push({
			"id": i,
			"name": i
		});
	}

	var mString=options(t);

	for (var i = 1; i<8; i++) {
		var selectTab = document.getElementById('distributionHour'+i+'-OpenHour');
		selectTab.innerHTML = (hString);
		selectTab = document.getElementById('distributionHour'+i+'-CloseHour');
		selectTab.innerHTML = (hString);
		selectTab = document.getElementById('distributionHour'+i+'-OpenMin');
		selectTab.innerHTML = (mString);
		selectTab = document.getElementById('distributionHour'+i+'-CloseMin');
		selectTab.innerHTML = (mString);
	}
}

	
 
