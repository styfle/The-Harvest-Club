<?php
require_once('include/Database.inc.php');
require_once('include/auth.inc.php');

if (isset($_REQUEST['email']) && isset($_REQUEST['password'])) {
	$sql = "SELECT v.id,v.email,first_name,last_name,p.can_login
			FROM volunteers v
			LEFT JOIN privileges p
			ON v.privilege_id = p.id
			WHERE email='%s'
			AND password=SHA1('%s');"; // password uses SHA1
	
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
			$_SESSION = array_key_exists(0, $_SESSION) ? $_SESSION[0] : null;
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

	<title><?php echo PAGE_TITLE; ?> - Login</title>
	<meta name="description" content="">

	<!-- Mobile viewport optimized: h5bp.com/viewport -->
	<meta name="viewport" content="width=device-width,initial-scale=1">

	<!-- Place favicon.ico and apple-touch-icon.png in the root directory: mathiasbynens.be/notes/touch-icons -->
	<link rel="shortcut icon" type="image/ico" href="favicon.ico" />

	<link rel="stylesheet" href="css/style.css"> <!-- css reset -->
	<link rel="stylesheet" href="css/themes/smoothness/jquery-ui-1.8.4.custom.css">
	
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
			<?php echo PAGE_TITLE; ?> - <span id="page_title">Login</span>
			<span id="me">Welcome Guest!</span>
		</h1>
		<div id="quote">"<?php echo PAGE_QUOTE; ?>"</div>

	</header>

	<div id="main" role="main">
		<p style="text-align:center;">This page requires user authentication. Please login to continue.</p>
		<form action="" method="post">
			<table style="margin:auto; text-align:center;">
			<tr>
				<td>
					<label for="email">Email Addr:</label>
				</td>
				<td>
					<input type="email" name="email" />
				</td>
			</tr>
			<tr>
				<td>
					<label for="password">Password:</label>
				</td>
				<td>
					<input type="password" name="password" />
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="submit" value="Login" />
				</td>
			</tr>
			</table>
		</form>

		<div id="status" class="<?php if (!isset($m)) echo 'invisible'; ?> ui-state-error"><?php echo ($m) ? $m : 'nothing'; ?></div>

	</div> <!-- end main -->
	
	<footer id="footer">
		The Harvest Club &copy; 
		<?php 
			date_default_timezone_set('America/Los_Angeles');
			echo date('Y');
		?>
	</footer>

</div><!-- container end -->

</body>
</html>
