<?php

// Load the account system
require_once('accountSystem.php');

// Log the user out
logUserOut();

// Redirect to login page
header( 'Location: http://acm.uconn.edu/php/members/login.php' );


?>
