<?php
    require_once('forumSystem.php');
    require_once('accountSystem.php');

    if (!checkAdmin()) {
	    echo "Not Authorized";
	    exit();
	}

    $filename = $_POST['filename'];
    $category = $_POST['category'];
    $xml = trim($_POST['xml']);

    if (!isset($_POST['filename']) || $_POST['filename'] == '') {
	    echo 'Error, no topic to delete';
	    exit();
	}

	if (!isset($_POST['category']) || $_POST['category'] == '') {
	    echo 'Category error';
	    exit();
	}

	if (!isset($_POST['xml']) || $_POST['xml'] == '') {
	    echo 'Error: XML is empty';
	    exit();
	}

	$result = simplexml_load_string($xml);
	if ($result === FALSE){
		echo "Invalid XML";
		exit();
	}

	$handle = fopen($categoriesLocation.$category."/".$filename, "w");
	if ($handle === FALSE){
		echo "Error opening topic: ".$filename;
		exit();
	}

	if (fwrite($handle, $xml) === FALSE){
		echo "Error editing topic";
		exit();
	}

	fclose($handle);
	echo "success";
?>