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

		$title = "Join ACM";
		$msg = "Success! Thanks for joining and welcome to ACM!";
		header('Location: http://acm.uconn.edu/php/signup_confirm.php?type=success&msg=' . urlencode($msg) . "&title=" . urlencode($title));
	}else{
		$msg = "Error! Please fill in all the fields";
		header('Location: http://acm.uconn.edu/php/signup_confirm.php?type=error&msg=' . urlencode($msg));
	}
?>