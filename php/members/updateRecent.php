<?php
    require_once('categorySystem.php');

    // Store all the files with their modified time into an associative array
    $files = array();

    $recentFolder = dir($categoriesLocation."recent");
    while (false !== ($entry = $recentFolder->read())) {
        $filePath = $categoriesLocation."recent/".$entry;
        if (is_file($filePath)){
            $files[filemtime($filePath)] = $entry;
        }
    }
    $recentFolder->close();

    // Sort array by modified time
    krsort($files);

    // Show Title
    echo "<li><a class='heading'>Recently Posted</a></li>";

    // List the files based on their modified time
    foreach($files as $file){
        $post = openFile("recent", $file);
        echo "<li>";
        echo "<a href='#' onClick='return false;' class='recent-post'>".$post->title."</a>";
        echo "<span style='display: none;' class='post-filename'>".$file."</span>";
        echo "<span style='display: none;' class='post-category'>".$post->category."</span>";
        echo "</li>";
    }
?>