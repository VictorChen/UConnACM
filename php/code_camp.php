<?php
	if (isset($_POST)){
		$first = $_POST['inputFirst'];
		$last = $_POST['inputLast'];
		$email = $_POST['inputEmail'];
		
		if (!empty($first) && !empty($last) && !empty($email)){
			$myFile = "/u/acm/storage/database/code_camp.csv";
			$fh = fopen($myFile, 'a+') or die("Error: Cannot complete registration. Please try again.");

			$line = $first . " " . $last . "," . $email . "," . date('l jS \of F Y h:i:s A') . "\n";
			fwrite($fh, $line);
			
			fclose($fh);
			
			$title = "UCONN ACM Boston Code Camp";
			$msg = "Success! Stay tune for more information about the field trip!";
			header('Location: http://acm.uconn.edu/php/confirmation.php?type=success&msg=' . urlencode($msg) . "&title=" . urlencode($title));
		}else{
			$title = "UCONN ACM Boston Code Camp";
			$msg = "Error! Please fill in all the fields";
			header('Location: http://acm.uconn.edu/php/confirmation.php?type=error&msg=' . urlencode($msg));
		}
	}
?>