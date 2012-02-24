		var optionSelect = '<option value="" disabled="disabled" selected="selected">Select...</option>';
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
			 var txt3=document.createTextNode('Pound');
			 label3.appendChild(txt3);
			 cell3.appendChild(label3);
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
			 var txt2=document.createTextNode('Volunteer');
			 label2.style.fontWeight = 'bold';
			 label2.appendChild(txt2);
			 cell2.appendChild(label2);
			 
			 var cell3 = row.insertCell(2);
			 var label3 = document.createElement("label");
			 label3.style.width = "5em";
			 label3.style.fontWeight = 'bold';
			 var txt3=document.createTextNode('Hour');
			 label3.appendChild(txt3);
			 cell3.appendChild(label3);
			 
			 var cell4 = row.insertCell(3);
			 var label4 = document.createElement("label");
			 label4.style.width = "5em";
			 label4.style.fontWeight = 'bold';
			 var txt4=document.createTextNode('Driver');
			 label4.appendChild(txt4);
			 cell4.appendChild(label4);
			 
			 var cell5 = row.insertCell(4);
			 var label5 = document.createElement("label");
			 label5.style.width = "5em";
			 label5.style.fontWeight = 'bold';
			 var txt5=document.createTextNode('Tree Type');
			 label5.appendChild(txt5);
			 cell5.appendChild(label5);
			 
			 var cell6 = row.insertCell(5);
			 var label6 = document.createElement("label");
			 label6.style.fontWeight = 'bold';
			 label6.style.width = "5em";
			 var txt6=document.createTextNode('Pound');
			 label6.appendChild(txt6);
			 cell6.appendChild(label6);
			 
			 var cell7 = row.insertCell(6);
			 var label7 = document.createElement("label");
			 label7.style.width = "5em";
			 label7.style.fontWeight = 'bold';
			 var txt7=document.createTextNode('Distribution');
			 label7.appendChild(txt7);
			 cell7.appendChild(label7);
			 
			}
			 
            var rowCount = table.rows.length;
            var row = table.insertRow(rowCount);
 
            var cell1 = row.insertCell(0);
            var element1 = document.createElement("input");
            element1.type = "checkbox";
            cell1.appendChild(element1);
 
            var cell2 = row.insertCell(1);
			var element2 = document.createElement("select");
			element2.innerHTML = (options(volunteerNames));
			cell2.appendChild(element2);
				
            var cell3 = row.insertCell(2);
            var element3 = document.createElement("input");
            element3.type = "text";
			element3.style.width = "4em"
            cell3.appendChild(element3);
			
			var cell4 = row.insertCell(3);
            var element4 = document.createElement("input");
            element4.type = "checkbox";
			element4.style.width = "4em"
            cell4.appendChild(element4);
			
			var cell5 = row.insertCell(4);
			var element5 = document.createElement("select");
			element5.innerHTML = (options(treeNames));
			cell5.appendChild(element5);
			
			
			var cell6 = row.insertCell(5);
			var element6 = document.createElement("input");
			element6.type = "text";
			element6.style.width = "4em"
			cell6.appendChild(element6);
			
			var cell7 = row.insertCell(6);
			var element7 = document.createElement("select");
			element7.innerHTML = (options(distributionNames));
			cell7.appendChild(element7);
			
			element5.style.visibility="hidden";
			element6.style.visibility="hidden";
			element7.style.visibility="hidden";
			
			
			element4.checked = false;
			element4.onclick = function() { 
			  if (element4.checked)
			  {
				element5.style.visibility="visible";
				element6.style.visibility="visible";
				element7.style.visibility="visible";
			  }
			  else
			  {
				element5.style.visibility="hidden";
				element6.style.visibility="hidden";
				element7.style.visibility="hidden";
			  }
			};


			
 
        }
 
        function deleteVolunteerRow(tableID) {
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
		
		function loadGrowerToForm(grower_id)
	{
		$.ajax( {
						'dataType': 'json', 
						'type': 'GET', 
						'url': 'ajax.php?cmd=get_grower_name', 
						'success': function (data) {
							var str = '<select id="event-grower-name" name="event-grower-name">';
							if( data.datatable != null) 							
								for ( var i=0, len = data.datatable.aaData.length; i< len; ++i )
										str += '<option value="'+data.datatable.aaData[i][0]+'">'+data.datatable.aaData[i][1]+'</option>';
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
	}
	
	function loadVolunteerToForm(formName, captain_id)
	{
		$.ajax( {
						'dataType': 'json', 
						'type': 'GET', 
						'url': 'ajax.php?cmd=get_volunteer_name', 
						'success': function (data) {
							var str = '<select id="event-volunteer-name" name="event-volunteer-name">';
							if( data.datatable != null) 							
								for ( var i=0, len = data.datatable.aaData.length; i< len; ++i )
								//if ( (i+1) == row[4])
									str += '<option value="'+data.datatable.aaData[i][0]+'" selected="selected">'+data.datatable.aaData[i][1]+'</option>';
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
	  $('#event-volunteer-name').val(captain_id).attr('selected',true);	
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
							var table = document.getElementById("eventVolunteer");
							if( data.datatable != null) 							
								for ( var i=0, len = data.datatable.aaData.length; i< len; ++i )
									{
										addVolunteerRow('eventVolunteer');
										table.rows[i+1].cells[1].childNodes[0].value = data.datatable.aaData[i][0];	
										table.rows[i+1].cells[2].childNodes[0].value = data.datatable.aaData[i][3];	
										if (data.datatable.aaData[i][2] == 1)
										{
										  getDriverData(data.datatable.aaData[i][0], table, i);
										  table.rows[i+1].cells[3].childNodes[0].checked = true;
										  table.rows[i+1].cells[4].childNodes[0].style.visibility="visible";
										  table.rows[i+1].cells[5].childNodes[0].style.visibility="visible";
										  table.rows[i+1].cells[6].childNodes[0].style.visibility="visible";
										}
									}
						},
						'error': function (e) {
							alert('Ajax Error!\n' + e.responseText);
						}
					});	
	}
	
	function getDriverData(volunteer_id, table, i){
		$.ajax( {
						'dataType': 'json', 
						'type': 'GET', 
						'url': 'ajax.php?cmd=get_driver&id='+volunteer_id, 
						'success': function (data) {
							//console.log(data);
							table.rows[i+1].cells[4].childNodes[0].value = data.datatable.aaData[0][1];
							table.rows[i+1].cells[5].childNodes[0].value = data.datatable.aaData[0][4];
							table.rows[i+1].cells[6].childNodes[0].value = data.datatable.aaData[0][3];
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
		var event_id =  $('#event1').val();
		var event_name =  $('#event2').val();
		var event_date =  $('#event5').val();
		var grower_id =  $('#event-grower option:selected').val();
		var captain_id = $('#event-captain option:selected').val();
		var tree_type = new Array();
		var volunteers = new Array();
		
		var table = document.getElementById("eventTree");
		var rowCount = table.rows.length;
		for(var i=1; i<rowCount; i++)
			if((table.rows[i].cells[1].childNodes[0].value !="") && (table.rows[i].cells[2].childNodes[0].value !=""))
			{
				var data = {
							"tree_id": table.rows[i].cells[1].childNodes[0].value,
							"pound": table.rows[i].cells[2].childNodes[0].value
							};
				
				tree_type.push(data); 
			}
		
		var table = document.getElementById("eventVolunteer");
		var rowCount = table.rows.length;
		for(var i=1; i<rowCount; i++) 
			if((table.rows[i].cells[1].childNodes[0].value !="") && (table.rows[i].cells[2].childNodes[0].value !=""))
			{
				var data = {
							"volunteer_id": table.rows[i].cells[1].childNodes[0].value,
							"hour": table.rows[i].cells[2].childNodes[0].value,
							"driver": table.rows[i].cells[3].childNodes[0].checked,
							"tree_id": table.rows[i].cells[4].childNodes[0].value,
							"pound": table.rows[i].cells[5].childNodes[0].value,
							"distribution_id": table.rows[i].cells[6].childNodes[0].value
							};
				
				volunteers.push(data); 
			}
		
		var para = ("event_id="+event_id+"&event-name="+event_name+"&event-date="+event_date+"&grower-id="+grower_id+"&captain-id="+captain_id);	
		var data = {
					event_id: event_id,
					event_name: event_name,
					event_date: event_date,
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
							alert('Information is updated!');
						},
						'error': function (e) {
							alert('Ajax Error!\n' + e.responseText);
						}
					});	
	}

	
 
