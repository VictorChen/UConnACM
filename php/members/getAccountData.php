<?php
// Load the account system
require_once('accountSystem.php');
require_once('forumSystem.php');

// Don't do anything if the user isn't logged in
if (!checkLoggedIn()) {
    echo "Not Authorized";
    exit();
}

// Make sure a hash was given
if (!isset($_GET) || !isset($_GET['hash'])) {
    echo '{"success":false}';
    exit();
}

// Attempt to retrieve the account associated with the given hash
$account = getAccountDataByHash($_GET['hash']);
if ($account === FALSE) {
    echo '{"success":false}';
    exit();
}

// These fields are mandatory
$result = '{"success":true,"id":"'.$account['id'].'","email":"'.escapeJsonString($account['email']).'","firstName":"'.escapeJsonString($account['firstName']).'","lastName":"'.escapeJsonString($account['lastName']).'","admin":"';
if ($account['authLevel'] == 1) $result .= 'true';

// The remaining fields are optional ones but may cause javascript errors if they aren't given
$result .= '","major":"'.escapeJsonString($account['major']).'","year":"'.escapeJsonString($account['year']).'","aboutMe":"'.escapeJsonString($account['aboutMe']).'"}';

echo $result;

?>
