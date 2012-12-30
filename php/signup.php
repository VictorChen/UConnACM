<?php
	$first = $_POST['inputFirst'];
	$last = $_POST['inputLast'];
	$email = $_POST['inputEmail'];
	$major = $_POST['inputMajor'];
	$year = $_POST['inputYear'];
	
	if (!empty($first) && !empty($last) && !empty($email) && !empty($major) && !empty($year)){
		$myFile = "/u/acm/storage/database/signup.csv";
		$fh = fopen($myFile, 'a+') or die("Error: Cannot complete sign up. Please try again.");

		$line = $first . " " . $last . "," . $email . "," . $major . "," . $year . "," . date('l jS \of F Y h:i:s A') . "\n";
		fwrite($fh, $line);
		
		fclose($fh);
	}
?>