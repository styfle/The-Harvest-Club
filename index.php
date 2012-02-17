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
		<h1>The Harvest Club - CPanel</h1>
		<div id="quote">"The harvest is plentiful but the workers are few"</div>
		<form>
			<div id="nav">
				<input type="radio" id="get_notifications" name="radio" checked="checked" /><label for="get_notifications">Home</label>
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
	
	$(document).ready(function() {
		var currentTable = 'v'; //keeps track if volunteer (v) or grower (g) table is selected.
	
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
			if(cmd == "get_volunteers")
				currentTable = 'v';
			else if(cmd == "get_growers")
				currentTable = 'g';
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
					
					console.log('Successful ajax!');
					dt.fnDestroy(); // destroy datatable
					
					/*
					var html = '<tr>';
					for (var i=0; i<data.datatable.aoColumns.length; i++)
						html += '<th>'+data.datatable.aoColumns[i].sTitle+'</th>';
					html += '</tr>';
					*/
					
					$('#dt thead').html('');
					
					/*html = '';
					for (var i=0; i<data.datatable.aaData.length; i++) {
						html += '<tr>';
						for (var j=0; j<data.datatable.aaData[i].length; j++)
							html += '<td>'+data.datatable.aaData[i][j]+'</td>';
						html += '</tr>';
					}*/
					
					$('#dt tbody').html('');
					
					
					dt = $('#dt').dataTable({
						'bJQueryUI': true, // style using jQuery UI
						'sPaginationType': 'full_numbers', // full pagination
						'bProcessing': true, // show loading bar text
						'bAutoWidth': true, // auto column size
						'aaSorting': [], // disable initial sort
						'aoColumns': data.datatable.aoColumns,
						'aaData': data.datatable.aaData,
					});
				},
				'error': function (e) {
					alert('Error: ' + e);
				}
			});
		});

		
		
		$('#edit-dialog').dialog({
			autoOpen: false,
			title: 'Edit Record',
			height: 530,
			width: 400,
			modal: true,
			close: function() {
				console.log('dialog closed');
			},
			buttons: {
				'Save': function() {
					alert('This feature is not implemented yet!');
				},
				Cancel: function() {
					$(this).dialog('close');
				}
			} // end buttons
		});
		
		// after we force a dialog, hidden is handled by jqueryUI
		$('#edit-dialog').removeClass('hidden');
		
		// all rows in the table will open dialog onclick
		// note that .live() is depracated in favor of .on()
		$(document).on('click', '#dt tbody tr',function(e) {
			var row = (dt.fnGetData(this));
			
			// we don't need this stuff
			if(currentTable == 'v'){
				$('#grower').addClass('hidden');
				$('#volunteer').removeClass('hidden'); //for css see style.css
				for(var i = 0; i < row.length; i++)
					$('#volunteer' + i).val(row[i]);				
					// $('#firstname').val(row[1]);
					// $('#middlename').val(row[2]);
					// $('#lastname').val(row[3]);
					// $('#phone').val(row[4]);
					// $('#email').val(row[5]);
					// $('#password').val(row[6]);
					// $('#street').val(row[8]);
					// $('#city').val(row[9]);
					// $('#state').val(row[10]);			
					// $('#zip').val(row[11]);
					// $('#signedup').val(row[13]);			
					// $('#note').val(row[14]);			
					// $('#status').val(row[7]);
					// $('#privilidge').val(row[12]); 
			}
			else if (currentTable == 'g'){
				$('#volunteer').addClass('hidden');
				$('#grower').removeClass('hidden');
				for(var i = 0; i < row.length; i++)
					$('#grower' + i).val(row[i]);
					// $('#firstname1').val(row[1]);
					// $('#middlename1').val('');
					// $('#lastname1').val(row[2]);
					// $('#phone1').val(row[3]);
					// $('#email1').val(row[4]);
					// $('#street1').val(row[5]);
					// $('#city1').val(row[6]);
					// $('#state1').val(row[7]);			
					// $('#zip1').val(row[8]);
					// $('#tool').val(row[9]);
					// $('#hear').val(row[10]);
					// $('#note').val(row[11]);
					// $('#pti').val(row[12]);
					// $('#pri').val(row[13]);
			}
			
			$('#edit-dialog').dialog('open') // show dialog
		});
	});

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
