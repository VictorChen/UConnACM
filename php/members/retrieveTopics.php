<?php
    require_once('forumSystem.php');
    require_once('accountSystem.php');

    $category = $_POST['category'];


    $files = array();

    $folder = dir($categoriesLocation.$category);
    while (false !== ($entry = $folder->read())) {
        $filePath = $categoriesLocation.$category."/".$entry;
        if (is_file($filePath)){
            $files[filemtime($filePath)] = $entry;
        }
    }
    $folder->close();

    // Sort array by modified time
    krsort($files);

    // List the files based on their modified time
    foreach($files as $file){
        $filePath = $categoriesLocation.$category."/".$file;
        $post = openFile($category, $file);

        if (!$post){
            echo "Error opening topic: ".$file;
            exit();
        }

        echo '<li class="category-post">';
            echo '<span class="post-title">'.$post->title.'</span>';
            echo '<span class="post-time">';
                echo '<span class="label label-success">'.getChatTime(filemtime($filePath)).'</span>';
                echo '<span class="label label-info">'.getChatDate(filemtime($filePath)).'</span>';
            echo '</span>';
            echo '<span class="post-author">By: <a href="#configModal" onclick="loadAccountData(\'' . hashEmail($post->email) . '\', true)" data-toggle="modal">'.$post->first.' '.$post->last.'</a></span>';
            echo '<span class="post-filename">'.$file.'</span>';
            if (checkAdmin()) echo '<br><button class="btn btn-danger post-delete-btn">Delete</button>';
        echo '</li>';
    }
?>