<?php
	require_once('forumSystem.php');
    require_once('accountSystem.php');

    $email = $_POST['currentEmail'];
    if ($email == ''){
    	echo "Error: no email";
    	exit();
    }
    if (!checkAdmin() && $email != $_SESSION['account']['email']) {
	    echo "Not Authorized";
	    exit();
	}

	define ('MAX_FILE_SIZE', 1024 * 50); // 50 KB
	define('UPLOAD_DIR', '/u/acm/public_html/accountImages/');

	// Accepted formats
	$permitted = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png');

	$type = $_FILES['image']['type'];
	$size = $_FILES['image']['size'];

	if (!in_array($type, $permitted) || $size <= 0 || $size > MAX_FILE_SIZE){
		echo "File is either too big or is not an image. Max size for image is 50KB";
		exit();
	}
	
	$filename = hashEmail($email).".png";

	switch($_FILES['image']['error']) {
		case UPLOAD_ERR_OK:
			$result = move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_DIR.$filename);
			if ($result === FALSE){
				echo "Error uploading image. Please try again.";
				exit();
			}
			break;
		case UPLOAD_ERR_PARTIAL:
		case UPLOAD_ERR_NO_TMP_DIR:
		case UPLOAD_ERR_CANT_WRITE:
		case UPLOAD_ERR_EXTENSION:
			echo "Error uploading image. Please try again.";
			exit();
		case UPLOAD_ERR_NO_FILE:
			echo "You didn't select a filename to be uploaded.";
			exit();
	}

	echo "success";
?>