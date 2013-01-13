<?php
    require_once('forumSystem.php');
    require_once('accountSystem.php');

    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];

    if (!isset($_POST['title']) || $_POST['title'] == '') {
	    echo 'Please enter a title';
	    exit();
	}

	if (!isset($_POST['content']) || $_POST['content'] == '') {
	    echo 'Content is empty';
	    exit();
	}

	if (!isset($_POST['category']) || $_POST['category'] == '') {
	    echo 'Category error';
	    exit();
	}

	$filename = generateFileName($category);
    $filepath = $categoriesLocation.$category."/".$filename;
    
    if (file_exists($filepath)){
    	echo "Error, Please try again. File Exists".$filepath;
    	exit();
    }

    $handle = fopen($filepath, 'w');
    if ($handle === FALSE){
    	echo "Error creating file: ".$filepath;
    	exit();
    }

    // Write data to xml file
    $xml = "<?xml version='1.0'?>\n";
    $xml .= "<post>\n";
    $xml .= "<title>".escapeXmlString($title)."</title>\n";
    $xml .= "<id>".$_SESSION['account']['id']."</id>\n";
    $xml .= "<date>".getChatDate(time())."</date>\n";
    $xml .= "<time>".getChatTime(time())."</time>\n";
    $xml .= "<category>".$category."</category>\n";
    $xml .= "<content>".escapeXmlString($content)."</content>\n";
    $xml .= "</post>";
	
	$result = fwrite($handle, $xml);
	if ($result === FALSE){
    	echo "Error writing to file: ";
    	exit();
    }

    fclose($handle);

    // Add new file to recent folder for displaying recently posted topics
    $result = addToRecent($category, $filename);
    if ($result === FALSE){
        echo "Error saving file to recent: ".$filepath;
        exit();
    }

    echo "success";
?>