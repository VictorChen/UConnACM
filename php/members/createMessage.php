<?php
    require_once('categorySystem.php');
    require_once('accountSystem.php');

    $filename = $_POST['filename'];
    $message = $_POST['message'];
    $category = $_POST['category'];

    if (!isset($_POST['filename']) || $_POST['filename'] == '') {
	    echo 'Filename error';
	    exit();
	}

	if (!isset($_POST['message']) || $_POST['message'] == '') {
	    echo 'Content is empty';
	    exit();
	}

	if (!isset($_POST['category']) || $_POST['category'] == '') {
	    echo 'Category error';
	    exit();
	}

	// Create the new message xml string
	$xml = "<chat>";
	$xml .= "<email>".escapeXmlString($_SESSION['account']['email'])."</email>";
	$xml .= "<first>".escapeXmlString($_SESSION['account']['firstName'])."</first>";
	$xml .= "<last>".escapeXmlString($_SESSION['account']['lastName'])."</last>";
	$xml .= "<date>".getChatDate(time())."</date>";
	$xml .= "<time>".getChatTime(time())."</time>";
	$xml .= "<message>".escapeXmlString($message)."</message>";
	$xml .= "</chat>";

	// Add new file to recent folder for displaying recently posted topics
	$result = addToRecent($category, $filename);
	if ($result === FALSE){
        echo "Error saving file to recent: ".$filename;
        exit();
    }

    // Append the <chat>...</chat> to the xml file
	$result = appendToXML($category, $filename, $xml);
	if ($result){
		echo "success";
	}else{
		echo "Error saving message";
	}
?>