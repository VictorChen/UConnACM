<?php
	require_once('forumSystem.php');
	require_once('accountSystem.php');

	// Don't do anything if the user isn't an admin
	if (!checkAdmin()) {
	    echo "Not Authorized";
	    exit();
	}

	if (!isset($_POST['filename']) || $_POST['filename'] == '') {
	    echo 'Error, no topic to delete';
	    exit();
	}

	if (!isset($_POST['category']) || $_POST['category'] == '') {
	    echo 'Category error';
	    exit();
	}

	$filename = $_POST['filename'];
    $category = $_POST['category'];
    $filePath = $categoriesLocation.$category."/".$filename;
    $fileRecentPath = $categoriesLocation."recent/".$filename;

	if (!file_exists($filePath)) {
		echo 'Post does not exist: '.$filename." ".$category;
		exit();
	}

	if (!unlink($filePath)){
		echo "Error deleting topic";
		exit();
	}

	if (file_exists($fileRecentPath) && !unlink($fileRecentPath)) {
		echo "Error deleting recent topic";
		exit();
	}

	echo "success";
?>
