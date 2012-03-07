<?php
require_once('include/auth.inc.php');
require_once('include/Database.inc.php');

if (!isLoggedIn()) { // if we're not logged in
	header('Location: login.php'); // redirect to login page
	exit();
}

if (isExpired()) { // if session expired
	header('Location: logout.php'); // redirect to logout page
	exit();
}

updateLastReq(); // loading page means user is active

// try to get current user permissions
$r = $db->q("SELECT p.*
		FROM volunteers v
		LEFT JOIN privileges p
		ON v.privilege_id = p.id
		WHERE v.id=$_SESSION[id]"
);

if (!$r->isValid())
	die('<h1>Error 500</h1>An error occurred while checking your privileges. I cannot allow you to proceed.</p>');

// global containing all this user's privileges
$PRIV = $r->buildArray();
$PRIV = array_key_exists(0, $PRIV) ? $PRIV[0] : null;

if (!$PRIV)
	die('<h1>Error 500</h1>An error occurred while checking your privileges. I cannot allow you to proceed.</p>');

?>
<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>		<html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>		<html class="no-js lt-ie9" lang="en"> <![endif]-->
<!-- Consider adding a manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">

	<!-- Use the .htaccess and remove these lines to avoid edge case issues. h5bp.com/b/378 -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php echo PAGE_TITLE; ?></title>
	<meta name="description" content="">

	<!-- Mobile viewport optimized: h5bp.com/viewport -->
	<meta name="viewport" content="width=device-width,initial-scale=1">

	<!-- Place favicon.ico and apple-touch-icon.png in the root directory: mathiasbynens.be/notes/touch-icons -->
	<link rel="shortcut icon" type="image/ico" href="favicon.ico" />

	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/demo_table_jui.css">
	<link rel="stylesheet" href="css/themes/smoothness/jquery-ui-1.8.4.custom.css">
	
	<!-- More ideas for your <head> here: h5bp.com/d/head-Tips -->

	<!-- Modernizr enables HTML5 elements & feature detects for optimal performance. -->
	<script type="text/javascript" src="js/modernizr-2.0.6.min.js"></script>
	<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.8.17.custom.min.js"></script>
	<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="js/script.js"></script>
	<script type="text/javascript" src="js/event.js"></script>
</head>

<body>
<div id="container">
	<header>
		<h1>
			<?php echo PAGE_TITLE; ?> - <span id="page_title">Loading...</span>
			<span id="me">
				<a href="logout.php">Logout</a> as
				<?php echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name']; ?>
			</span>
		</h1>
		<div id="quote">"Share the Bounty"</div>

		<div id="status" class="invisible">
			<span id="status-icon" class="ui-icon ui-icon-info"></span>
			<span id="status-text">Welcome to your new CPanel!</span>
		</div><!-- end status -->

		<div class="toolbar">
			<span id="toolbar" class="css_right ui-widget-header ui-corner-all">
				<button id="add-button">Add</button>
				<button id="del-button">Delete</button>
				<button id="email-button">Email</button>
				<button id="export-button" >Export</button>
			</span>
		</div><!-- End toolbar -->

		<form>
			<div id="nav" class="css_left"> 
				<input type="radio" id="get_notifications" name="radio" checked="checked" /><label for="get_notifications">Home</label>
				<?php if ($PRIV['view_volunteer']) { ?>
				<input type="radio" id="get_volunteers" name="radio" /><label for="get_volunteers">Volunteers</label>
				<?php } if ($PRIV['view_grower']) { ?>
				<input type="radio" id="get_growers" name="radio" /><label for="get_growers">Growers</label>
				<?php } if ($PRIV['view_grower']) { ?>
				<input type="radio" id="get_trees" name="radio" /><label for="get_trees">Trees</label>
				<?php } if ($PRIV['view_distrib']) { ?>
				<input type="radio" id="get_distribs" name="radio" /><label for="get_distribs">Distribution Sites</label>
				<?php } if ($PRIV['view_event']) { ?>
				<input type="radio" id="get_events" name="radio" /><label for="get_events">Events</label>
				<?php } if ($PRIV['view_donor']) { ?>
				<input type="radio" id="get_donors" name="radio" /><label for="get_donors">Donors</label>
				<?php } ?>
			</div>
		</form>
	</header>
	
	<div id="main" role="main">
		<table id="dt" cellpadding="0" cellspacing="0" border="0" class="display">
			<!-- table is filled dynamically -->
			<thead><tr><th>Loading...</th></tr></thead>
			<tbody><tr><td>Please wait while the table loads. It should only take a second.</td></tr></tbody>
		</table>
	</div> <!-- end main -->
	
	<footer id="footer">
		The Harvest Club &copy; 
		<?php 
			date_default_timezone_set('America/Los_Angeles');
			echo date('Y');
		?>
	</footer>
	
	
	
	
	<script type="text/javascript" src="js/event.js"></script>
	<script type="text/javascript" charset="utf-8">

	// PRIVILEGES (astheic only)
	var priv = <?php echo json_encode($PRIV); ?>;
	for (var o in priv)
		priv[o] = (priv[o] === '1'); // convert to bools for quick checks

	// GLOBAL FUNCTIONS (probably move to separate file)
	
	function reloadTable(cmd) {
		$.ajax({
			'dataType': 'json', 
			'type': 'GET', 
			'url': 'ajax.php?cmd=' + cmd, 
			'success': function (data) {
				if (!validResponse(data))
					return false;
				if (!data.datatable || !data.datatable.aoColumns || !data.datatable.aaData)
					return alert('There is no column and row data!');
				
				// destroy datatable on each click
				dt.fnDestroy(); // destroy
				
				// clear out data in table head and body
				$('#dt thead').html('');
				$('#dt tbody').html('');
				
				dt = $('#dt').dataTable({
					'bJQueryUI': true, // style using jQuery UI
					'sPaginationType': 'full_numbers', // full pagination
					'bProcessing': true, // show loading bar text
					'bAutoWidth': false, // auto column size
					'aaSorting': [], // disable initial sort
					"aLengthMenu": [[10, 25, 50, 100, -1], // sort length
									[10, 25, 50, 100, "All"]], // sort name
					'aoColumns': data.datatable.aoColumns,
					'aaData': data.datatable.aaData,
					//"sScrollX": "100%",
					//"bScrollCollapse": true
				});

				currentTable = data.id; // set current table after it is populated
				$('#page_title').text(data.title); // set page title
				switch (currentTable)
				{
					case 0:
						showAddDelEmailExport(0,0,0,0);
					break;

					case 1: // volunteers
						showAddDelEmailExport(priv.edit_volunteer, priv.del_volunteer, priv.send_email, priv.exp_volunteer);
						if(privilegeID == 1) {
							// $('#dt tbody tr').each(function() {
								// tempId = dt.fnGetData(this)[14];
								// if(tempId != privilegeID)
									// dt.fnDeleteRow(this);
							// });
							cmd = "get_pending_volunteers";
							reloadTable(cmd);
						}
						privilegeID = 0;
					break;

					case 2: // growers
						showAddDelEmailExport(priv.edit_grower, priv.del_grower, priv.send_email, priv.exp_grower);
						if(pending == 1) {
							// $('#dt tbody tr').each(function() {
								// tempId = dt.fnGetData(this)[15];
								// if(tempId != pending)
									// dt.fnDeleteRow(this);
							// });
							cmd = "get_pending_growers";
							reloadTable(cmd);							
						}
						pending = 0;
					break;

					case 3: // Trees
						showAddDelEmailExport(priv.edit_grower, priv.del_grower, 0, 0); // tree has no email, no export						
						if(growerID != 0){
							cmd = "get_trees_from&growerID="+growerID;
							growerID = 0;
							reloadTable(cmd);							
						}						
					break;

					case 4: // distribution sites
						showAddDelEmailExport(priv.edit_distrib, priv.del_distrib, priv.send_email, priv.exp_distrib);
					break;

					case 5: // events
						showAddDelEmailExport(priv.edit_event, priv.del_event, 0, 0); // no email, no export
					break;

					case 6: // donations
						showAddDelEmailExport(priv.edit_donor, priv.del_donor, 0, priv.exp_donor); // no email
					break;

				}


			},
			'error': ajaxError
		}); // end ajax
	}

	function contains(str, i) {
		if (str)
			return str.toLowerCase().indexOf(i) != -1;
		else
			return false;
	}

	function hideStatus() {
		$('#status').addClass('invisible');
	}

	function setInfo(message) {
		$('#status-text').text(message);

		$('#status')
			.removeClass() // remove all classes
			.addClass('ui-state-highlight ui-corner-all');


		$('#status-icon')
			.removeClass() // remove all classes
			.addClass('ui-icon ui-icon-info');

		setTimeout(hideStatus, 5000); // hide after 5 sec
	}

	function setError(message) {
		$('#status-text').text(message);

		$('#status')
			.removeClass() // remove all classes
			.addClass('ui-state-error ui-corner-all');


		$('#status-icon')
			.removeClass() // remove all classes
			.addClass('ui-icon ui-icon-alert');

		setTimeout(hideStatus, 5000); // hide after 5 sec
	}

	// show form and hide all other forms
	function switchForm(id) {
		$('#volunteer').addClass('hidden');
		$('#grower').addClass('hidden');
		$('#tree').addClass('hidden');
		$('#distribution').addClass('hidden');
		$('#email').addClass('hidden');
		$('#event').addClass('hidden');
		$('#donation').addClass('hidden');

		$('#'+id).removeClass('hidden'); // show form
	}

	// check ajax response for our standard format
	function validResponse(data) {
		if (!data || !data.status) { // bad data
			setError('Error: Corrupt data returned from server!');
			alert('Error: Corrupt data returned from server!');
			return false;
		}
		if (data.status != 200) { // not ok so display server message
			setError(data.message);
			alert('Status '+ data.status + '\n' + data.message);

			if (data.status == 401) // unauthorized
				window.location.href = window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/') + 1) + 'logout.php';

			return false;
		}
		return true;
	}

	// Generic Ajax Error
	function ajaxError(e) {
		alert('Ajax error (internet issue) occurred.\n' + e.responseText);
	}

	// show or hide 4 buttons at the top right
	function showAddDelEmailExport(add, del, eml, exp) {
		if(add)	$('#add-button').removeClass('hidden');
		else	$('#add-button').addClass('hidden');

		if(del)	$('#del-button').removeClass('hidden');
		else	$('#del-button').addClass('hidden');

		if(eml)	$('#email-button').removeClass('hidden');
		else	$('#email-button').addClass('hidden');

		if(exp)	$('#export-button').removeClass('hidden');
		else	$('#export-button').addClass('hidden');
	}

	// GLOBAL VARIABLES

	var dt; // global datatable variable
	var currentTable = 0; // global id of current data table
	var forms = ['volunteer', 'grower', 'tree', 'distribution'];
	var growerID = 0;
	var pending = 0;
	var privilegeID = 0;
	var aPos;
	var row;
	//----- These are the variables for event
	var loadGrower=0;
	var loadCaptain = 0;
	var loadVolunteer = 0;
	var loadTreeType = 0;
	var loadDistribution = 0;
	var treeNames = [];
	var volunteerNames = [];
	var distributionNames = [];
	var growerPhone =[];
	var growerAddress =[];
	var growerCity =[];
	var grower_id,event_id, captain_id;
	////
	
	var saveButton = {
		text: 'Save',
		click: function() {
			switch (currentTable)
			{
				case 0:		// Notifications
					break;
					
				case 1:		//Volunteers Tab
					
					//Update DB
					var para = $('#volunteer').serialize();
					$.ajax({							
						'type': 'GET',
						'url': 'ajax.php?cmd=update_volunteer&'+para,
						'success': function (data) {
							if (!validResponse(data))
								return false;
							setInfo('Information Updated');
							$('#edit-dialog').dialog('close');
							for(var i = 1; i < row.length; i++) {
								if($('#volunteer'+i).val() == undefined)							
									row[i]='';
								else;									
									row[i]=$('#volunteer'+i).val();								
							}

							dt.fnUpdate(row, aPos, 0);	//Update Table -- Independent from updating db!
						},
						'error': ajaxError
					});
					
														
					break;
				
				case 2:
					$('#grower19').val($('#grower17 option:selected').text());			
					$('#grower20').val($('#grower18 option:selected').text());	
							
					//Update DB					
					var para = $('#grower').serialize();
					$.ajax({							
						'type': 'GET',
						'url': 'ajax.php?cmd=update_grower&'+para,
						'success': function (data) {
							if (!validResponse(data))
								return false;
							setInfo('Information Updated');
							$('#edit-dialog').dialog('close');
							for(var i = 2; i < row.length; i++){
								if($('#grower'+i).val() == undefined)							
									row[i]='';
								else
									row[i]=$('#grower'+i).val();						
							}	
						if($('#grower15').val()==1)				
							row[16]='Pending';
						else
							row[16]='Approved';							
							dt.fnUpdate( row, aPos, 0 );	//Update Table -- Independent from updating db!							
						},
						'error': ajaxError
					});
					break;
				case 3:
					//Update text fields in table row -- 
							$('#tree2').val($('#tree3 option:selected').text());	//Update Owner name		
							$('#tree5').val($('#tree4 option:selected').text());	//Update Tree type
							$('#tree11').val($('#tree10 option:selected').text());	//Update Height name															
							if($('#tree8 option:selected').val()==1)				//Update Chemical option
								$('#tree9').val('Yes');
							else
								$('#tree9').val('No');
					//Update DB
					var para = $('#tree').serialize();
					$.ajax({							
						'type': 'GET',
						'url': 'ajax.php?cmd=update_tree&'+para,
						'success': function (data) {
							if (!validResponse(data))
								return false;
							setInfo('Information Updated');
							$('#edit-dialog').dialog('close');
							for(var i = 1; i < row.length; i++){					//Update Other fields
								if($('#tree'+i).val() == undefined)							
									row[i]='';
								else
									row[i]=$('#tree'+i).val();
							}
							dt.fnUpdate(row, aPos, 0);								//Update Table							
						},
						'error': ajaxError
					});
					break;
					
				case 4:
					var para = $('#distribution').serialize();															
					
					//Update DB
					$.ajax({							
						'type': 'GET',
						'url': 'ajax.php?cmd=update_distribution&'+para,
						'success': function (data) {
							if (!validResponse(data))
								return false;
							setInfo('Information Updated');
							$('#edit-dialog').dialog('close');
							for(var i = 1; i < row.length; i++){
								if($('#distribution'+i).val() == undefined)							
									row[i]='';
								else
									row[i]=$('#distribution'+i).val();								
							}
							dt.fnUpdate(row, aPos, 0);	//Update Table -- Independent from updating db!	
						},
						'error': ajaxError
						});							
					break;
					
				case 5:  //event
					if (checkEventForm() != -1) {
						row[2] = $('#event2').val();
						row[3] = $('#event-grower-name').val();
						row[4] = $('#event-volunteer-name').val();
						row[5] =  $('#event5').val();
						
						updateEvent();
						dt.fnUpdate(row, aPos, 0);
					}
					break;
				case 6:	// donation
					var para = $('#donation').serialize();
					//Update DB
					$.ajax({							
						'type': 'GET',
						'url': 'ajax.php?cmd=update_donation&'+para,
						'success': function (data) {
							if (!validResponse(data))
								return false;
							setInfo('Information Updated');
							$('#edit-dialog').dialog('close');
							for(var i = 1; i < row.length; i++){
								if($('#donation'+i).val() == undefined)							
									row[i]='';
								else
									row[i]=$('#donations'+i).val();								
							}
							dt.fnUpdate(row, aPos, 0);
						},
						'error': ajaxError
					});
					break;
			}		
		}
	};

	var addButton = {
		text: 'Add',
		click: function() {
			switch (currentTable)
			{
				case 0:		//				
					break;
					
				case 1:		//Volunteers Tab
					var required = $('#volunteer input[required="required"]');
					for(var i=0; i<required.length; i++)
					{
						if (required[i].value == '')
							return alert(required[i].name + ' is required!');
					}
					
					//Update DB
					var para = $('#volunteer').serialize();
					$.ajax({							
						'type': 'GET',
						'url': 'ajax.php?cmd=add_volunteer&'+para,
						'success': function (data) {
							if (!validResponse(data))
								return false;
							setInfo('Information Added');
							$('#edit-dialog').dialog('close');
							reloadTable("get_volunteers");									
						},
						'error': ajaxError
					});
					break;
				
				case 2:						
					var required = $('#grower input[required="required"]');
					for(var i=0; i<required.length; i++)
					{
						if (required[i].value == '')
							return alert(required[i].name + ' is required!');
					}
					
					var para = $('#grower').serialize();
					$.ajax({							
						'type': 'GET',
						'url': 'ajax.php?cmd=add_grower&'+para,
						'success': function (data) {
							if (!validResponse(data))
								return false;
							setInfo('Information Added');
							$('#edit-dialog').dialog('close');
							reloadTable("get_growers");									
						},
						'error': ajaxError
					});
					
					break;
				case 3:
					var required = $('#tree input[required="required"]');
					for(var i=0; i<required.length; i++)
					{
						if (required[i].value == '')
							return alert(required[i].name + ' is required!');
					}
					
					var para = $('#tree').serialize();
					$.ajax({							
						'type': 'GET',
						'url': 'ajax.php?cmd=add_tree&'+para,
						'success': function (data) {
							if (!validResponse(data))
								return false;
							setInfo('Information Added');
							$('#edit-dialog').dialog('close');
							reloadTable("get_trees");
						},
						'error': ajaxError
					});
					break;
					
				case 4:
					var para = $('#distribution').serialize();
					$.ajax({							
						'type': 'GET',
						'url': 'ajax.php?cmd=add_distribution&'+para,
						'success': function (data) {
							if (!validResponse(data))
								return false;
							setInfo('Information Added');
							$('#edit-dialog').dialog('close');
							reloadTable("get_distribs");
						},
						'error': ajaxError
					});
														
					break;
					
				case 5:  // event
					if (checkEventForm() != -1)
					{
						createNewEvent();
						reloadTable("get_events");									
					}
					break;
					
				case 6:
					var para = $('#donation').serialize();
					$.ajax({							
						'type': 'GET',
						'url': 'ajax.php?cmd=add_donation&'+para,
						'success': function (data) {
							if (!validResponse(data))
								return false;
							setInfo('Information Updated');
							$('#edit-dialog').dialog('close');
							reloadTable("get_donors");
						},
						'error': ajaxError
					});
					break;
			}		
		}
	};
	
	var sendEmailButton = {
		text: 'Send Email',
		click: function() {
			var para = $('#email').serialize();
			$.ajax({							
				'dataType': 'json', 
				'type': 'GET',
				'url': 'ajax.php?cmd=send_email&'+para,
				'success': function (data) {
					if (!validResponse(data))
						return false;
					var bcc = $('#email [name=bcc]')[0];
					setInfo('Email sent to ' + bcc.value.split(',').length + ' user(s).');
					$('#edit-dialog').dialog('close');
				},
				'error': ajaxError
			});
		}
	};
	
	var cancelButton = {
		text: 'Cancel',
		click: function() {
			$(this).dialog('close');
		}
	}; 

	
	$(function() {
		$("#add-button").button({
			label: "Add New Record",
			icons: { primary: "ui-icon-plusthick" },
			text: false
		}).click(function() {
			// Clear all forms
			for(var i = 0; i < forms.length; i++)
			{
				$('#'+forms[i]).find(':input').each(function()
				{
					switch(this.type) {
						case 'tel':
						case 'text':
						case 'password':
						case 'textarea':
						case 'select-one':
						case 'select-multiple':
							$(this).val('');
							break;
						case 'radio':
						case 'checkbox':
								this.checked = false;
						case 'button': // Don't change value(name) for buttons								
							break;
						default:
							$(this).val('');
							break;
					}
				});
			}
			
			//Display the form you want, hide everything else
			switch (currentTable)
			{
				case 0: // notifications
					setError('Sorry but there is no reason to add a record to this table. It is strictly informational.');
					return;
				case 1: //volunteer
					switchForm('volunteer');
					$('#pending').hide();						
					for (var i = 1; i < 18; i++)
						$('#volunteer' + i).prop('disabled', false);
					for ( var i=1; i< 6; i++ )
						$('#volunteerRole'+i).prop('disabled', false);
					for ( var i=1; i< 8; ++i )
						$('#volunteerDay'+i).prop('disabled', false);		
				break;
				
				case 2: // grower
					switchForm('grower');
					$('#pending2').hide();
					for (var i = 1; i < 21; i++)
							$('#grower' + i).prop('disabled', false);
				break;
				
				case 3: // tree
					switchForm('tree');
				break;
				
				case 4: // distribution
					switchForm('distribution');
					for (var i = 0; i < 9; i++)
						$('#donations'+i).val('');
					initHours();
					
                break;
						
				case 5: // event
					$('#event2').val('');
					$('#event5').val('');
					loadAllEventForm(0,1,1);
                break;
				
				case 6: // donation
					for (var i = 0; i < 6; i++)
						$('#donations'+i).val('');
					switchForm('donation');
					$('#donations5').not('.hasDatePicker').datepicker({dateFormat: 'yy-mm-dd'});
				break;
			}			

			$('#edit-dialog').dialog("option", "buttons", [addButton, cancelButton]);
			$('#edit-dialog').dialog({ title: 'Add Record' });
			$('#edit-dialog').dialog('open');
			
		});

		$("#del-button").button({
			label: "Delete Selected",
			icons: { primary: "ui-icon-trash" },
			text: false
		}).click(function()	{
			var deleteList = [];

			switch (currentTable)
			{
				case 0: // notifications
					setError('Sorry but there is no reason to remove a record to this table. It is strictly informational.');
					return;
				case 1: //volunteer
					$('input[name=select-row]:checked').each(function(){
						deleteList.push($(this).parent().parent());
					});
					if(deleteList.length > 0)
					{
						//pop up confirmation window
						var x = window.confirm("Are you sure you want to delete "+deleteList.length+" items?");
						if(x)
						{
							var deleted = 0;
							$('input[name=select-row]:checked').each(function()
							{
								var row = $(this).parent().parent();
								var data = dt.fnGetData(row[0]);
								var id = data[1];
								var firstname = data[2];
								var lastname = data[4];
								
								$.ajax({							
									'type': 'GET',
									'url': 'ajax.php?cmd=remove_volunteer&id='+id,
									'success': function (data) {
										if (!validResponse(data))
											return false;
										deleted++;
										dt.fnDeleteRow(row[0]);
										setInfo('Deleted ' + deleted + ' volunteers.');
									},
									'error': ajaxError
								});
							});

						}
					}
				break;
				
				case 2: // grower
					$('input[name=select-row]:checked').each(function(){
						deleteList.push($(this).parent().parent());
					});
					if(deleteList.length > 0)
					{
						//pop up confirmation window
						var x = window.confirm("Are you sure you want to delete "+deleteList.length+" items");
						if(x){
							var deleted = 0;
							$('input[name=select-row]:checked').each(function(){
								var row = $(this).parent().parent();
								var data = dt.fnGetData(row[0]);
								var id = data[1];
								var firstname = data[2];
								var lastname = data[4];
								//TODO Ajax needs to be sent at the end
								$.ajax({							
									'type': 'GET',
									'url': 'ajax.php?cmd=remove_grower&id='+id,
									'success': function (data) {
										if (!validResponse(data))
											return false;										
										deleted++;
										dt.fnDeleteRow(row[0]);
										setInfo('Deleted ' + deleted + ' items');										
									},
									'error': ajaxError
								});
							});	
						}
					}						
				break;
				
				case 3: // tree
					$('input[name=select-row]:checked').each(function(){
						deleteList.push($(this).parent().parent());
					});
					if(deleteList.length > 0){
						//pop up confirmation window
						var x = window.confirm("Are you sure you want to delete "+deleteList.length+" items");
						if(x){
							var deleted = 0;
							$('input[name=select-row]:checked').each(function(){
								var row = $(this).parent().parent();
								var data = dt.fnGetData(row[0]);
								var id = data[1];
								deleteList.push(id);						
							//TODO Ajax needs to be sent at the end
								$.ajax({							
									'type': 'GET',
									'url': 'ajax.php?cmd=remove_tree&id='+id,
									'success': function (data) {								
										if (!validResponse(data))
											return false;									
										deleted++;
										dt.fnDeleteRow(row[0]);
										setInfo('Deleted ' + deleted + ' items');
										console.log(data);
									},
									'error': ajaxError
								});
							});
						}
					}					
				break;
				
				case 4: // distribution
					$('input[name=select-row]:checked').each(function(){
						var row = $(this).parent().parent();
						var data = dt.fnGetData(row[0]);
						var id = data[1];
						deleteList.push(id);
						//TODO Ajax needs to be sent at the end
						$.ajax({							
							'type': 'GET',
							'url': 'ajax.php?cmd=remove_distribution&id='+id,
							'success': function (data) {								
								
							},
							'error': ajaxError
						});
						row.remove();
					});					
				break;
						
				case 5: // event
					$('input[name=select-row]:checked').each(function(){
						var row = $(this).parent().parent();
						var data = dt.fnGetData(row[0]);
						var id = data[1];	
						deleteList.push(id);						
						//TODO Ajax needs to be sent at the end
						$.ajax({							
							'type': 'GET',
							'url': 'ajax.php?cmd=remove_event&id='+id,
							'success': function (data) {								
								
							},
							'error': ajaxError
						});
						row.remove();
					});
                break;
				
				case 6: // donation
						$('input[name=select-row]:checked').each(function(){
						var row = $(this).parent().parent();
						var data = dt.fnGetData(row[0]);
						var id = data[1];
						deleteList.push(id);
						//TODO Ajax needs to be sent at the end
						$.ajax({							
							'type': 'GET',
							'url': 'ajax.php?cmd=remove_donation&id='+id,
							'success': function (data) {								
								
							},
							'error': ajaxError
						});
						row.remove();
					});					
				break;
			}			
		});

		$('#email-button').button({
			label: 'Email Selected',
			icons: { primary: 'ui-icon-mail-closed' },
			text: false
		}).click(function() {
			var emailList = [];
			var iEmail = -1;
			var cols = dt.fnSettings().aoColumns;
			for (var i=0; i<cols.length; i++) {
				if (contains(cols[i].sTitle, 'email')) {
					iEmail = i;
					break;
				}
			}
			$('input[name=select-row]:checked').each(function(){
				if (iEmail == -1)
					return false;
				var row = $(this).parent().parent();
				var data = dt.fnGetData(row[0]);
				var emailAddr = data[iEmail];
				if (emailAddr != '') // check if empty
					emailList.push(emailAddr);
			}); // :checked end
			if (iEmail == -1) {
				setError('There is no email address in this table. How do you expect to send email?');
				return false;
			}
			if (emailList.length == 0) {
				setInfo('Select 1 or more checkboxes to choose email recipients.');
				return false;
			}
			switchForm('email');
			$('#email [name=bcc]').val(emailList.join(','));
			$('#email .rcount').text(emailList.length);
			$('#edit-dialog').dialog("option", "buttons", [sendEmailButton, cancelButton]);
			$('#edit-dialog').dialog({ title: 'Email Selected Users' });
			$('#edit-dialog').dialog('open') // show dialog
		}); // .click() end
		
		$("#export-button").button({
			label: "Export Selected",
			icons: {
				//primary: "ui-icon-disk",
				primary: "ui-icon-arrowthickstop-1-s"
			},
			text: false
		}).click(function() {
			var exportList = [];
			var arrayID = [];
			
			$('input[name=select-row]:checked').each(function(){						
				exportList.push($(this).parent().parent());
			});
			if (exportList.length <= 0) {
				setInfo('Select 1 or more checkboxes to Export.');
				return false;
			}
			else {										
				var x = window.confirm("Are you sure you want to export "+exportList.length+" items?");
				if(x)
				{
					$('input[name=select-row]:checked').each(function(){						
						var row = $(this).parent().parent();
						var data = dt.fnGetData(row[0]);
						var id = data[1];
						arrayID.push(id);
					});												
					//alert("Exporting "+exportList.length+" row(s) of data");
					if(currentTable == 2){					
						var yesButton = {
							text: 'Yes',
							click: function() {	
								$(this).dialog('close');
								window.location.href = window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/') + 1) 
								+ 'export.php?arrayID[]='+arrayID+'&table=3';								
							}
						};
						
						var noButton = {
							text: 'No',
							click: function() {
								//currentTable = 2; //Export ONLY grower info
								$(this).dialog('close');
								window.location.href = window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/') + 1) 
								+ 'export.php?arrayID[]='+arrayID+'&table=2';
							}
						};
						var buttonList = [yesButton, noButton];
						var $exportdialog = $('<div></div>')
								.html("Do you want to export tree information of " +arrayID.length+ " selected grower(s)?")
								.dialog({
									autoOpen: false,
									title: 'Grower Export'
								});
						$exportdialog.dialog("option", "buttons", buttonList);
						$exportdialog.dialog('open');
										
					}
					else{
						window.location.href = window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/') + 1) 
						+ 'export.php?arrayID[]='+arrayID+'&table='+currentTable;
					}
				}
			}
		}); // .click() export end
	});
	
	$(document).ready(function() {
	
		dt = $('#dt').dataTable({
			'bJQueryUI': true, // style using jQuery UI
			'sPaginationType': 'full_numbers', // full pagination
			'bProcessing': true, // show loading bar text
			'bAutoWidth': false, // auto column size
			'aaSorting': [], // disable initial sort
			"sScrollX": "100%",
			//"bScrollCollapse": true
			//'aaData': [],
			//'aoColumns': [],
		});
	
		$('#nav') // set up navigation
			.buttonset() // turn into buttons
			.attr('unselectable', 'on')
			.css({ // disable text selection
				'-ms-user-select':'none',
				'-moz-user-select':'none',
				'-webkit-user-select':'none',
				'user-select':'none',
			})
			.each(function() { // IE only
				this.onselectstart = function() { return false; };
			});

		$('#nav input').click(function() {
			reloadTable(this.id); // button id is the ajax command
		});
		
		
		$('#edit-dialog').dialog({
			autoOpen: false,
			title: 'Edit Record',
			height: 550,
			width: 450,
			modal: true,
			/*close: function() {
				console.log('dialog closed');
			},*/
		});
		
		// after we force a dialog, hidden is handled by jqueryUI
		$('#edit-dialog').removeClass('hidden');
		
		// all rows in the table will open dialog onclick
		// note that .live() is deprecated in favor of .on()
		$(document).on('click', '#dt tbody tr',function(e) {
			row = (dt.fnGetData(this));
			aPos = dt.fnGetPosition( this );
			var buttonList = [cancelButton];
			
			var cols = dt.fnSettings().aoColumns;
			switch (currentTable)
			{
				case 0: //notification
					viewNotifications(row[1]);
				break;

				case 1: //volunteer
					switchForm('volunteer');
					if (priv.edit_volunteer)
						buttonList.unshift(saveButton);
					for (var i = 0; i < row.length; i++)
						$('#volunteer' + i).val(row[i]);					
					
					$.ajax({
						'dataType': 'json',
						'type': 'GET',
						'url': 'ajax.php?cmd=get_volunteer_role&id='+row[1],
						'success': function (data) {
							if (!validResponse(data))
								return false;
							for ( var i=1; i< 5; ++i )   // clear data
							  $('#volunteerRole'+i).prop("checked", false);
							
							if( data.datatable != null) 							
							 for ( var i=0, len = data.datatable.aaData.length; i< len; ++i )
								$('#volunteerRole'+data.datatable.aaData[i][0]).prop("checked", true);									
									
						},
						'error': ajaxError
					});
						
					$.ajax({
						'dataType': 'json',
						'type': 'GET',
						'url': 'ajax.php?cmd=get_volunteer_prefer&id='+row[1],
						'success': function (data) {
							if (!validResponse(data))
								return false;
							for ( var i=1; i< 8; ++i )   // clear data
							  $('#volunteerDay'+i).prop("checked", false);
							
							if( data.datatable != null) 							
							 for ( var i=0, len = data.datatable.aaData.length; i< len; ++i )
								$('#volunteerDay'+data.datatable.aaData[i][0]).prop("checked", true);									
									
						},
						'error': ajaxError
					});

					var iType = -1;
					for (var i=0; i<cols.length; i++) {
						if (contains(cols[i].sTitle, 'privilege_id')) {
							iType = i;
							break;
						}
					}
					
					if (iType != -1) {
						if(row[iType]>1){
							$('#pending').hide();						
							for (var i = 1; i < row.length; i++)
								$('#volunteer' + i).prop('disabled', false);
							for ( var i=1; i< 6; ++i )
								$('#volunteerRole'+i).prop('disabled', false);
							for ( var i=1; i< 8; ++i )
								$('#volunteerDay'+i).prop('disabled', false);
						} else { // assume pending
							$('#pending').show();
							for (var i = 1; i < row.length; i++)
								$('#volunteer' + i).prop('disabled', true);
							for ( var i=1; i< 6; ++i )
								$('#volunteerRole'+i).prop('disabled', true);
							for ( var i=1; i< 8; ++i )
								$('#volunteerDay'+i).prop('disabled', true);
						}
					}
				break;
				
				case 2: // grower
					switchForm('grower');
					if (priv.edit_grower)
						buttonList.unshift(saveButton);
					
					for (var i = 1; i < row.length; i++)
						$('#grower' + i).val(row[i]);

					var iPending = -1;
					for (var i=0; i<cols.length; i++) {
						if (contains(cols[i].sTitle, 'pending_id')) {
							iPending = i;
							break;
						}
					}

					if (iPending != -1) {
						if(row[iPending]==0){
							$('#pending2').hide();						
							for (var i = 1; i < row.length; i++)
								$('#grower' + i).prop('disabled', false);
						} else { // assume approved
							buttonList.shift(saveButton);
							$('#pending2').show();
							for (var i = 1; i < row.length; i++)
								$('#grower' + i).prop('disabled', true);
						}
					}
				break;
				
				case 3: // tree
					switchForm('tree');
					if (priv.edit_grower)
						buttonList.unshift(saveButton);
					for (var i = 1; i < row.length; i++)
						$('#tree' + i).val(row[i]);
					$.ajax({
                        'dataType': 'json',
                        'type': 'GET',
                        'url': 'ajax.php?cmd=get_tree_month&id='+row[1],
                        'success': function (data) {
							if (!validResponse(data))
								return false;
							for ( var i=1; i< 13; ++i )   // clear data
							  $('#tree_month'+i).prop("checked", false);
							
							if( data.datatable != null) 							
					   		 for ( var i=0, len = data.datatable.aaData.length; i< len; ++i )
								$('#tree_month'+data.datatable.aaData[i][0]).prop("checked", true);									
								
							
									
                        },
                        'error': ajaxError
                    });
				break;
				
				case 4: // distribution
					initHours();
					switchForm('distribution');
					if (priv.edit_distrib)
						buttonList.unshift(saveButton);
                    for (var i = 1; i < row.length; i++)
                        $('#distribution' + i).val(row[i]);                                                                            
                    $.ajax({
                        'dataType': 'json',
                        'type': 'GET',
                        'url': 'ajax.php?cmd=get_distribution_times&id='+row[1],
                        'success': function (data) {
							if (!validResponse(data))
								return false;
							for (var i=0; i< 8; ++i)   // clear data
							{
								$('#distributionHour' +i+'-OpenHour').val('');
								$('#distributionHour' +i+'-OpenMin').val('');
								$('#distributionHour' +i+'-CloseHour').val('');
								$('#distributionHour' +i+'-CloseMin').val('');
							}
							if (data.datatable != null) 							
								for ( var i=0, len = data.datatable.aaData.length; i< len; ++i )
								{
									var myData = data.datatable.aaData[i];
									var dateID = myData[1];
									var open   = myData[2].split(":",3);
									var close  = myData[3].split(":",3);										
									$('#distributionHour' +dateID+'-OpenHour').val(open[0]);
									$('#distributionHour' +dateID+'-OpenMin').val(open[1]);
									$('#distributionHour' +dateID+'-CloseHour').val(close[0]);
									$('#distributionHour' +dateID+'-CloseMin').val(close[1]);
								}							
                        },
                        'error': ajaxError
                    });
				break;
			
				case 5: // event				
					if (priv.edit_event)
						buttonList.unshift(saveButton);
					for (var i = 1; i < row.length; i++)
						$('#event' + i).val(row[i]);
					event_id = row[1];	

					grower_id = row[3];	
					captain_id = row[4];
					loadAllEventForm(event_id,grower_id,captain_id);
				break;
				
				case 6: // donation
					switchForm('donation');
					if (priv.edit_donor)
						buttonList.unshift(saveButton);
					for (var i = 1; i < row.length; i++)
                    	$('#donations' + i).val(row[i]);
					$('#donations5').not('.hasDatePicker').datepicker({dateFormat: 'yy-mm-dd'});
				break;	


			}
			
			$('#edit-dialog').dialog("option", "buttons", buttonList);
			$('#edit-dialog').dialog({ title: 'Edit Record' });
			if(currentTable != 0) // don't show dialog for notifications
				$('#edit-dialog').dialog('open') // show dialog
		}); // on.click tr
		
		$(document).on('change', '#event-grower-name', function(e) {
			grower_id = $('#event-grower option:selected').val();
			deleteAllTreeRows();
			deleteAllVolunteerRows()
			grower_id = $(this).val();
			treeNames.length = 0;
			volunteerNames.length = 0;
			loadTree(grower_id, event_id);
			loadVolunteerName(event_id);
			$('#event8').val(growerPhone[grower_id-1]);
			$('#event9').val(growerAddress[grower_id-1]);
			$('#event7').val(growerCity[grower_id-1]);
			
		});
		
		$(document).on('click', 'input[name=select-row]', function(e) {
				e.stopPropagation();
		}); // on.click() checkbox row

		$(document).on('click', 'input[name=select-all]', function(e) {
			var c = this.checked;
			var count = 0;
			$('input[name=select-row]').each(function(i) {
				this.checked = c;
				count++;
			});
			// show info on selection
			if (c)
				$('#dt_info').text('Selected ' + count + ' entries.');
			else
				$('#dt_info').text('Deselected ' + count + ' entries.');

			e.stopPropagation();
		}); // on.click() checkbox all

		// the last thing we do is load the home page (get_notifcations)
		document.getElementById('get_notifications').click();
	}); // document.ready()

	function viewTrees(){
		growerID = $('#grower1').val();					//get ID of grower whose trees are to be shown
		$('#edit-dialog').dialog('close');				//Close pop-up
		document.getElementById('get_trees').click();	//switch to Trees Tab	
	}

	function viewNotifications(row) {
		if (row == 'Pending volunteers') {
			privilegeID = 1;
			document.getElementById('get_volunteers').click();
		}
		if (row == 'Pending growers') {
			pending = 1;
			document.getElementById('get_growers').click();
		}
		//if (row == 'Pending events') {
			//get ID of currently logged in harvest captain
			//display events with missing info where harvest captain == currently logged in person
			//document.getElementById('get_events').click();
		//}
	}

	function approveGrower(){
		growerID = $('#grower1').val();	
		$.ajax({							
			'type': 'GET',
			'url': 'ajax.php?cmd=approve_grower&growerID='+growerID,
			'success': function (data) {
				if (!validResponse(data))
					return false;
				setInfo('Information Updated');
				$('#pending2').hide();
				$('#edit-dialog').dialog('close');
				reloadTable("get_growers");
				/*
				$('#grower15').val(0);
				for (var i = 1; i < row.length; i++)
					$('#grower' + i).prop('disabled', false);
				*/
			},
			'error': ajaxError
		});			
	}
	
	function approveVolunteer(){
		var volunteerID = $('#volunteer1').val();	
		$.ajax({							
			'type': 'GET',
			'url': 'ajax.php?cmd=approve_volunteer&volunteerID='+volunteerID,
			'success': function (data) {
				if (!validResponse(data))
					return false;
				setInfo('Information Updated');
				$('#pending').hide();
				$('#edit-dialog').dialog('close');
				reloadTable("get_volunteers");
				/*
				$('#volunteer14').val(2); // approve from pending to volunteer
				for (var i = 1; i < 18; i++)
					$('#volunteer' + i).prop('disabled', false);
				for ( var i=1; i< 6; i++ )
					$('#volunteerRole'+i).prop('disabled', false);
				for ( var i=1; i< 8; ++i )
					$('#volunteerDay'+i).prop('disabled', false);
				*/
			},
			'error': ajaxError
		});			
	}
	</script>

	<!-- Prompt IE 6 users to install Chrome Frame. -->
	<!--[if lt IE 7 ]>
		<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
		<script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
	<![endif]-->

</div> <!-- container -->

<?php require_once('include/forms.php'); ?>

</body>
</html>
