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

	<title>The Harvest Club - CPanel</title>
	<meta name="description" content="">

	<!-- Mobile viewport optimized: h5bp.com/viewport -->
	<meta name="viewport" content="width=device-width,initial-scale=1">

	<!-- Place favicon.ico and apple-touch-icon.png in the root directory: mathiasbynens.be/notes/touch-icons -->
	<link rel="shortcut icon" type="image/ico" href="favicon.ico" />

	<link rel="stylesheet" href="css/style.css"> <!-- css reset -->
	<link rel="stylesheet" href="css/demo_page.css">
	<link rel="stylesheet" href="css/demo_table_jui.css">
	<link rel="stylesheet" href="css/themes/smoothness/jquery-ui-1.8.4.custom.css">
	
	<!-- More ideas for your <head> here: h5bp.com/d/head-Tips -->

	<!-- Modernizr enables HTML5 elements & feature detects for optimal performance. -->
	<script type="text/javascript" src="js/modernizr-2.0.6.min.js"></script>
	<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.8.17.custom.min.js"></script>
	<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="js/script.js"></script>
</head>

<body id="dt_example">
<div id="container">
	<header>
		<h1>The Harvest Club - CPanel <span id="page_title" style="float:right">Page Title<span></h1>
		<div id="quote">"The harvest is plentiful but the workers are few"</div>
		<form>
			<div id="nav">
				<input type="radio" id="get_notifications" name="radio" checked="checked" /><label for="get_notifications">Notifications</label>
				<input type="radio" id="get_volunteers" name="radio" /><label for="get_volunteers">Volunteers</label>
				<input type="radio" id="get_growers" name="radio" /><label for="get_growers">Growers</label>
				<input type="radio" id="get_trees" name="radio" /><label for="get_trees">Trees</label>
				<input type="radio" id="get_distribs" name="radio" /><label for="get_distribs">Distribution Sites</label>
				<input type="radio" id="get_events" name="radio" /><label for="get_events">Events</label>
				<input type="radio" id="get_donors" name="radio" /><label for="get_donors">Donors</label>
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
	
	
	
	
	
	
	<script type="text/javascript">
	var dt; // global datatable variable
	var currentTable = 0; // global id of current data table
	
	$(document).ready(function() {
	
		dt = $('#dt').dataTable({
			'bJQueryUI': true, // style using jQuery UI
			'sPaginationType': 'full_numbers', // full pagination
			'bProcessing': true, // show loading bar text
			'bAutoWidth': true, // auto column size
			'aaSorting': [], // disable initial sort
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
			var cmd = this.id; // button id is the ajax command
			var aPos;
			var row;
			
			$.ajax( {
				'dataType': 'json', 
				'type': 'GET', 
				'url': 'ajax.php?cmd=' + cmd, 
				'success': function (data) {
					if (!data || !data.status)
						return alert('Error: Corrupt data returned from server!');
					if (data.status != 200)
						return alert('Status '+ data.status + '\n' + data.message);
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
						'bAutoWidth': true, // auto column size
						'aaSorting': [], // disable initial sort
						"aLengthMenu": [[10, 25, 50, 100, -1], // sort length
										[10, 25, 50, 100, "All"]], // sort name
						'aoColumns': data.datatable.aoColumns,
						'aaData': data.datatable.aaData,
					});
					
					currentTable = data.id; // set current table after it is populated
					$('#page_title').text(data.title); // set page title
				},
				'error': function (e) {
					alert('Ajax Error!\n' + e.responseText);
				}
			});
		});

		
		
		$('#edit-dialog').dialog({
			autoOpen: false,
			title: 'Edit Record',
			height: 550,
			width: 600,
			modal: true,
			close: function() {
				console.log('dialog closed');
			},
			buttons: {
				'Save': function() {
					switch (currentTable)
					{
						case 0:		//				
							break;
							
						case 1:		//Volunteers Tab
							for(var i = 2; i < 16; i++){								
								row[i]=$('#volunteer'+i).val();								
							}
							dt.fnUpdate( row, aPos, 0 );	//Update Table -- Independent from updating db!
							
							//Update DB
							var para = $('#volunteer').serialize();
							$.ajax({							
							'type': 'GET',
							'url': 'ajax.php?cmd=update_volunteer&'+para,
							'success': function (data) {
								alert('Information is updated!');
							                  },
							'error': function(e) {
								alert('Ajax Error!\n' + e.responseText);
								}
							});
							
																
							break;
						
						case 2:	
							for(var i = 2; i < 17; i++){
								row[i]=$('#grower'+i).val();								
							}
							dt.fnUpdate( row, aPos, 0 );	//Update Table -- Independent from updating db!
							
							//Update DB
							var para = $('#grower').serialize();
							$.ajax({							
							'type': 'GET',
							'url': 'ajax.php?cmd=update_grower&'+para,
							'success': function (data) {
								alert('Information is updated!');
								
							                  },
							'error': function(e) {
								alert('Ajax Error!\n' + e.responseText);
								}
							});
							break;
						case 3:
							break;
							
						case 4:
							var para = $('#distribution').serialize();															
							for(var i = 1; i < row.length; i++){
								row[i]=$('#distribution'+i).val();								
							}
							dt.fnUpdate( row, aPos, 0 );	//Update Table -- Independent from updating db!									
							
							//Update DB
							$.ajax({							
							'type': 'GET',
							'url': 'ajax.php?cmd=update_distribution&'+para,
							'success': function (data) {
								alert('Information is updated!');
							},
							'error': function(e) {
								alert('Ajax Error!\n' + e.responseText);
								}
							});							
							break;
					}						
				},
				Cancel: function() {
					$(this).dialog('close');
				}
			} // end buttons
		});
		
		// after we force a dialog, hidden is handled by jqueryUI
		$('#edit-dialog').removeClass('hidden');
		
		// all rows in the table will open dialog onclick
		// note that .live() is deprecated in favor of .on()
		$(document).on('click', '#dt tbody tr',function(e) {
			row = (dt.fnGetData(this));
			aPos = dt.fnGetPosition( this );
			switch (currentTable)
			{
				case 1: //volunteer
				$('#grower').addClass('hidden');
				$('#distribution').addClass('hidden');
				$('#volunteer').removeClass('hidden'); //for css see style.css
				for (var i = 0; i < row.length; i++)
					$('#volunteer' + i).val(row[i]);

				$.ajax({
                        'dataType': 'json',
                        'type': 'GET',
                        'url': 'ajax.php?cmd=get_volunteer_role&id='+row[1],
                        'success': function (data) {
							for ( var i=1; i< 5; ++i )   // clear data
							  $('#volunteerRole'+i).prop("checked", false);
							
							if( data.datatable != null) 							
					   		 for ( var i=0, len = data.datatable.aaData.length; i< len; ++i )
								$('#volunteerRole'+data.datatable.aaData[i][0]).prop("checked", true);									
									
                        },
                        'error': function(e) {
                            alert('Ajax Error!\n' + e.responseText);
                        }
                    });
					
				$.ajax({
                        'dataType': 'json',
                        'type': 'GET',
                        'url': 'ajax.php?cmd=get_volunteer_prefer&id='+row[1],
                        'success': function (data) {
							for ( var i=1; i< 8; ++i )   // clear data
							  $('#volunteerDay'+i).prop("checked", false);
							
							if( data.datatable != null) 							
					   		 for ( var i=0, len = data.datatable.aaData.length; i< len; ++i )
								$('#volunteerDay'+data.datatable.aaData[i][0]).prop("checked", true);									
									
                        },
                        'error': function(e) {
                            alert('Ajax Error!\n' + e.responseText);
                        }
                    });
					
				break;
				
				case 2: // grower
				
				$('#volunteer').addClass('hidden');
				$('#distribution').addClass('hidden');
				$('#grower').removeClass('hidden');
				for (var i = 1; i < row.length; i++)
					$('#grower' + i).val(row[i]);
				break;
				
				case 4: // distribution
                    $('#volunteer').addClass('hidden');
                    $('#grower').addClass('hidden');
                    $('#distribution').removeClass('hidden');
                    for (var i = 1; i < row.length; i++)
                        $('#distribution' + i).val(row[i]);                                                                            
                    $.ajax({
                        'dataType': 'json',
                        'type': 'GET',
                        'url': 'ajax.php?cmd=get_distribution_times&id='+row[1],
                        'success': function (data) {
							for ( var i=0; i< 8; ++i )   // clear data
							{
								$('#distributionHour' +i+'-OpenHour').val('');
								$('#distributionHour' +i+'-OpenMin').val('');
								$('#distributionHour' +i+'-CloseHour').val('');
								$('#distributionHour' +i+'-CloseMin').val('');
							}
							if( data.datatable != null) 							
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
                        'error': function(e) {
                            alert('Ajax Error!\n' + e.responseText);
                        }
                    });
				break;
		
			}			
			
			
			$('#edit-dialog').dialog('open') // show dialog
		}); // on.click tr

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

	}); // document.ready()

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
