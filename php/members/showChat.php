<?php
    require_once('forumSystem.php');
    require_once('accountSystem.php');

    $filename = $_POST['filename'];
    $category = $_POST['category'];
    $startFrom = (int)$_POST['startFrom'];

    $post = openFile($category, $filename);
    if (!$post){
        echo '{"error":"Error opening topic"}';
        exit();
    }

    // Get number of <chat> nodes
    $chatLength = count($post->chat);

    // By default, show last 10 messages
    if (!isset($_POST['startFrom'])) $startFrom = $chatLength-1;

    $messages = '';
    $prevButton;
    $prev = $startFrom - 10;
    if ($prev > -1){
        $prevButton = '<button onClick="loadPrevious('.$prev.');" class="btn btn-inverse btn-large btn-load-previous">Load Previous</button>';
    }else{
        $prev = -1;
    }

    

    // Show previous 10 messages starting from the current message
    for ($i=$startFrom; $i>$prev; $i--){
        $accountData = getAccountDataByID($post->chat[$i]->id);
        $link = '<a href="#configModal" onclick="loadAccountData(\'' . hashEmail($accountData['email']) . '\', true)" data-toggle="modal">'.htmlentities($accountData['firstName']).' '.htmlentities($accountData['lastName']).'</a>';
        if (!$accountData) $link = "[Deleted User]";

        $tempMessage = '<div class="chat" style="display: none;">';
        $tempMessage .= '<img class="userImageChat" src="http://acm.uconn.edu/accountImages/'.getUserImage($accountData['id']).'" width="25" height="25" />';
        $tempMessage .= '<span class="chat-time-date">'.$post->chat[$i]->time.', '.$post->chat[$i]->date.'</span>';
        $tempMessage .= '<pre>'.$link.': '.htmlentities($post->chat[$i]->message).'</pre></div>';
        $messages = $tempMessage.$messages;
    }
    $messages = $prevButton.$messages;

    // Get content of original post
    $accountData = getAccountDataByID($post->id);
    $link = '<a href="#configModal" onclick="loadAccountData(\'' . hashEmail($accountData['email']) . '\', true)" data-toggle="modal">'.htmlentities($accountData['firstName']).' '.htmlentities($accountData['lastName']).'</a>';
    if (!$accountData) $link = "[Deleted User]";
    $content = '<img class="userImageChat" src="http://acm.uconn.edu/accountImages/'.getUserImage($accountData['id']).'" width="25" height="25" />';
    $content .= '<span class="chat-time-date">'.$post->time.', '.$post->date.'</span>';
    $content .= '<pre>'.$link.': '.htmlentities($post->content).'</pre>';
    
    // Return data as json
    echo '{"title": "'.escapeJsonString(htmlentities($post->title)).'","content":"'.escapeJsonString($content).'","messages":"'.escapeJsonString($messages).'"}';
?>