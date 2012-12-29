<?php
	$suggestions = $_POST['inputSuggestions'];
	
	if (!empty($suggestions)){
		$myFile = "/u/acm/storage/database/suggestions.csv";
		$fh = fopen($myFile, 'a+') or die("Error: Cannot complete sign up. Please try again.");
		
		$date = date('l jS \of F Y h:i:s A');
		$line = array($suggestions, $date);

		fputcsv($fh, $line);
		fclose($fh);

		$title = "ACM Suggestions Box";
		$msg = "Success! Thanks for giving us a feedback. We will definitely consider it!";
		header('Location: http://acm.uconn.edu/php/members/confirmation.php?type=success&msg=' . urlencode($msg) . "&title=" . urlencode($title));
	}else{
		$title = "ACM Suggestions Box";
		$msg = "Error! Please fill in all the fields";
		header('Location: http://acm.uconn.edu/php/members/confirmation.php?type=error&msg=' . urlencode($msg) . "&title=" . urlencode($title));
	}
?>