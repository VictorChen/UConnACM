<?php
// Load the account system
require_once('accountSystem.php');

// Don't do anything if the user isn't an admin
if (!checkAdmin()) {
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
echo '{"success":true,"email":"' . $account['email'] . '","firstName":"' . $account['firstName'] . '","lastName":"' . $account['lastName'] . '","admin":"';
if ($account['authLevel'] == 1) echo 'true';

// The remaining fields are optional ones but may cause javascript errors if they aren't given
echo '","major":"' . $account['major'] . '","year":"' . $account['year'] . '","aboutMe":"' . $account['aboutMe'] . '"}';

?>
