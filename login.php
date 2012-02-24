<?php
require_once('include/Database.inc.php');

session_start();

if (isset($_REQUEST['email']) && isset($_REQUEST['password'])) {
	$sql = "SELECT id,email,first_name,last_name
			FROM volunteers
			WHERE email='%s'
			AND password=SHA2('%s', 256);"; // password uses SHA2-256 (MySQL 5.5+)
	
	$r = $db->q($sql, array(
			$_REQUEST['email'],
			$_REQUEST['password']
		)
	);

	if (!$r->isValid()) {
		$message = 'DB error: ' + $db->error();
	} else {
		$_SESSION = $r->buildArray();
		$_SESSION = $_SESSION[0];
		if (!isset($_SESSION['id']))
			$message = 'Incorrect email/password combination!';
	}
}

// user is logged in if we find an id
if (isset($_SESSION['id']))
	$message = 'You are logged in as ' . $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];

//print_r($_SESSION); // debug
?><!DOCTYPE html>
<html>
<head>
	<title>Login</title>
</head>
<body>
	<div class="message"><?php echo ($message) ? $message : '&nbsp;'; ?></div>
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
