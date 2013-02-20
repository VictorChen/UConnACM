<?php
	$email = $_POST['email'];
	
	if (!empty($email)){
		$myFile = "/u/acm/storage/database/unsubscribe.csv";
		$fh = fopen($myFile, 'a+') or die("Error: Cannot unsubscribe. Please try again.");
		fwrite($fh, $email."\n");
		fclose($fh);
	}
?>