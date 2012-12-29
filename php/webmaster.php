<?php
	$first = $_POST['inputFirst'];
	$last = $_POST['inputLast'];
	$email = $_POST['inputEmail'];
	$major = $_POST['inputMajor'];
	$year = $_POST['inputYear'];
	$pick = $_POST['inputPick'];
	
	if (!empty($first) && !empty($last) && !empty($email) && !empty($major) && !empty($year) && !empty($pick)){
		$myFile = "/u/acm/storage/database/webmaster.csv";
		$fh = fopen($myFile, 'a+') or die("Error: Cannot complete sign up. Please try again.");
		
		$date = date('l jS \of F Y h:i:s A');
		$line = array($first . " " . $last, $email, $major, $year, $pick, $date);

		fputcsv($fh, $line);
		fclose($fh);

		$title = "ACM Webmaster Position";
		$msg = "Success! Thanks for applying and good luck!";
		header('Location: http://acm.uconn.edu/php/confirmation.php?type=success&msg=' . urlencode($msg) . "&title=" . urlencode($title));
	}else{
		$title = "ACM Webmaster Position";
		$msg = "Error! Please fill in all the fields";
		header('Location: http://acm.uconn.edu/php/confirmation.php?type=error&msg=' . urlencode($msg) . "&title=" . urlencode($title));
	}
?>