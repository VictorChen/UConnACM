<?php
	$suggestions = $_POST['inputSuggestions'];
	
	if (!empty($suggestions)){
		$myFile = "/u/acm/storage/database/suggestions.csv";
		$fh = fopen($myFile, 'a+') or die("Error: Cannot complete sign up. Please try again.");
		
		$date = date('l jS \of F Y h:i:s A');
		$line = array($suggestions, $date);

		fputcsv($fh, $line);
		fclose($fh);
	}
?>