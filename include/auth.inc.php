<?php
require_once('config.inc.php');
// start new session or get current session
// basically looks for cookie or creates one
session_start();


// determines if user is logged in
function isLoggedIn() {
	return (isset($_SESSION['id']) && $_SESSION['can_login']);
}

// determines if session is expired
function isExpired() {
	return (time() - $_SESSION['time'] > SESSION_MAX_LENGTH);
}

// we do this every 'action' the user performs
function updateLastReq() {
	$_SESSION['time'] = time();
}

?>
