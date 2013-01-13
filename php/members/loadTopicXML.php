<?php
    require_once('forumSystem.php');

    $filename = $_POST['filename'];
    $category = $_POST['category'];

    $post = openFile($category, $filename);

    if (!$post){
        echo 'Error opening topic: '.$filename;
        exit();
    }
    
    // Pretty print for xml
    echo $post->asXML();
?>