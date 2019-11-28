<?php 
require_once('../utilities/constants.php');
require_once('session.php');
$session = Session::getInstance();

// Delete Session variables
session_unset();

// Destroy the Session
$session->destroy();
header('Location: ' .Constants::getBaseURL(). '/index.php');
?>