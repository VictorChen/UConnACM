<?php
    require_once('forumSystem.php');

    $filename = $_POST['filename'];
    $category = $_POST['category'];

    $post = openFile($category, $filename);

    if (!$post){
        echo 'Error opening topic: '.$filename;
        exit();
    }
    
    echo xmlpp($post->asXML());
?>