<?php
    require_once('forumSystem.php');
    require_once('accountSystem.php');

    $category = $_POST['category'];
    $startFrom = $_POST['startFrom'];

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
    $count = 0;
    $startEcho = false;
    $topics = '';
    $status = 'more';
    $amountToLoad = 10;
    foreach($files as $file){
        if (!$startEcho && $count == $startFrom){
            $startEcho = true;
            $count = 0;
        }

        if ($startEcho){
            if ($count >= $amountToLoad) break;
            $filePath = $categoriesLocation.$category."/".$file;
            $post = openFile($category, $file);

            if (!$post){
                echo "Error opening topic: ".$file;
                exit();
            }

            $topics .= '<li class="category-post">';
                $topics .= '<span class="post-title">'.htmlentities($post->title).'</span>';
                $topics .= '<span class="post-time">';
                    $topics .= '<span class="label label-success">'.getChatTime(filemtime($filePath)).'</span>';
                    $topics .= '<span class="label label-info">'.getChatDate(filemtime($filePath)).'</span>';
                $topics .= '</span>';
                $topics .= '<span class="post-author">By: <a href="#configModal" onclick="loadAccountData(\'' . hashEmail($post->email) . '\', true)" data-toggle="modal">'.htmlentities($post->first).' '.htmlentities($post->last).'</a></span>';
                $topics .= '<span class="post-filename">'.$file.'</span>';
                if (checkAdmin()) $topics .= '<button class="btn btn-danger post-delete-btn">Delete Topic</button>';
                else $topics .= "<div style='clear: both;'></div>";
                $topics .= '<img class="userImageList" src="http://acm.uconn.edu/accountImages/'.getUserImage(hashEmail($post->email)).'" width="50" height="50" />';
            $topics .= '</li>';
        }
        $count++;
    }

    if ($count < $amountToLoad){
        $status = 'done';
    }

    echo '{"status":"'.escapeJsonString($status).'","topics":"'.escapeJsonString($topics).'"}';
?>