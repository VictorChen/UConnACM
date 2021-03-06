<?php
// Load the account system
require_once('accountSystem.php');

// Don't do anything if the user isn't an admin
if (!checkAdmin()) {
    echo "Not Authorized";
    exit();
}

// Make sure a hash was given
if (!isset($_GET) || !isset($_GET['hash'])) exit();

// Attempt to retrieve the account associated with the given hash
$account = getAccountDataByHash($_GET['hash']);
if ($account === FALSE) exit();

// Make sure a user isn't trying to delete themself
if ($_SESSION['account']['email'] == $account['email']) {
    echo 'Denied';
    exit();
}

deleteAccount($account['email']);

?>
