<?php
    require_once('categorySystem.php');
    require_once('accountSystem.php');

    $filename = $_POST['filename'];
    $category = $_POST['category'];

    $post = openFile($category, $filename);

    if (!$post){
        echo '{"error":"Error opening topic"}';
        exit();
    }

    $messages = '';
    foreach ($post->chat as $chat) {
        $hash = hashEmail($chat->email);
        $messages .= '<div class="chat"><span class="chat-time-date">'.$chat->time.', '.$chat->date.'</span><pre><a href="#configModal" onclick="loadAccountData(\'' . $hash . '\', true)" data-toggle="modal">'.$chat->first.' '.$chat->last.'</a>: '.$chat->message.'</pre></div>';
    }

    $content = '<span class="chat-time-date">'.$post->time.', '.$post->date.'</span><pre><a href="#configModal" onclick="loadAccountData(\'' . hashEmail($post->email) . '\', true)" data-toggle="modal">'.$post->first.' '.$post->last.'</a>: '.$post->content.'</pre>';
    
    // Return data as json
    echo '{"title": "'.escapeJsonString($post->title).'","content":"'.escapeJsonString($content).'","messages":"'.escapeJsonString($messages).'"}';
?>