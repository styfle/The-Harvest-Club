<?php
require_once('include/Database.inc.php');
require_once('include/auth.inc.php');

$m = '';

if (isset($_REQUEST['email']) && isset($_REQUEST['password'])) {
	$sql = "SELECT v.id,v.email,first_name,last_name,p.can_login
			FROM volunteers v
			LEFT JOIN privileges p
			ON v.privilege_id = p.id
			WHERE email='%s'
			AND password=SHA2('%s', 256);"; // password uses SHA2-256 (MySQL 5.5+)
	
	$r = $db->q($sql, array(
			$_REQUEST['email'],
			$_REQUEST['password']
		)
	);

	if (!$r->isValid()) {
		$m = 'DB error: ' + $db->error();
	} else {
		if (!$r->hasRows())
			$m = 'Incorrect email/password combination!';
		else {
			$_SESSION = $r->buildArray();
			$_SESSION = $_SESSION[0];
			if (!isset($_SESSION['id']))
				$m = 'Incorrect email/password combination!';
			else if (!$_SESSION['can_login'])
				$m = 'You do not have permission to login!';
		
			$_SESSION['time'] = time();
		}
	}
}


// if logged in, redirect to index.php
if (isLoggedIn()) {
	header('Location: index.php');
	exit();
}


?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">

	<!-- Use the .htaccess and remove these lines to avoid edge case issues. h5bp.com/b/378 -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>Login to <?php echo PAGE_TITLE; ?></title>
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
	<script type="text/javascript" src="js/event.js"></script>

</head>
<body>
	<div id="status" class="ui-state-highlight"><?php echo $m; ?></div><!-- alert user -->
	<form action="" method="post">
		<div>
			<label for="email">Email</label>
			<input type="email" name="email" />
		</div>
		<div>
			<label for="password">Password</label>
			<input type="password" name="password" />
		</div>
		<div>
			<input type="submit" value="Login" />
		</div>
	</form>
</body>
</html>
