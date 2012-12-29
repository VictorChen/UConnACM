<?php
// Load the account system
require_once('accountSystem.php');

// Do some error checking
if (!isset($_POST)) {
    echo 'No form data';
    exit();
}

if (!isset($_POST['email']) || $_POST['email'] == '') {
    echo 'Please enter an email';
    exit();
}

if (!isset($_POST['pass']) || $_POST['pass'] == '') {
    echo 'Please enter a password';
    exit();
}

if (!isset($_POST['firstName']) || $_POST['firstName'] == '') {
    echo 'Please enter a first name';
    exit();
}

if (!isset($_POST['lastName']) || $_POST['lastName'] == '') {
    echo 'Please enter a last name';
    exit();
}

// Make sure the admin isn't trying to strip their own rights
if ((isset($_POST['oldEmail']) && $_POST['oldEmail'] != '') && $_POST['oldEmail'] == $_SESSION['account']['email'] && (!isset($_POST['admin']) || $_POST['admin'] != 'true')) {
    echo "You can't remove admin privilages from your own account";
    exit();
}

// Verify that the given email is not currently in use (except by the account being changed)
if (getAccountData($_POST['email']) !== FALSE) {
    if ((isset($_POST['oldEmail']) && $_POST['oldEmail'] != '')) {
        if ($_POST['oldEmail'] != $_POST['email']) {
            echo "Email is already in use by a different account";
            exit();
        }
    } else {
        echo "Email is already in use by a different account";
        exit();
    }
}

// Create or modify the account
if (isset($_POST['oldEmail']) && $_POST['oldEmail'] != '') {
    $userData = getAccountData($_POST['oldEmail']);
}

if (!isset($userData) || $userData === FALSE) $userData = array();
$userData['email'] = $_POST['email'];
$userData['firstName'] = $_POST['firstName'];
$userData['lastName'] = $_POST['lastName'];
if ($_SESSION['account']['authLevel'] == 1){
    if (isset($_POST['admin']) && $_POST['admin'] == 'true') $userData['authLevel'] = '1';
    else $userData['authLevel'] = '0';
}else{
    $userData['authLevel'] = '0';
}

// Only change the password if it was changed in the form
if ($_POST['pass'] != 'XXXXXXXXXXXXXXXXXXXX') {
    $userData['salt'] = generateSalt(22);
    $userData['password'] = hashPassword($_POST['pass'], $userData['salt']);
}

$result = FALSE;
if (isset($_POST['oldEmail']) && $_POST['oldEmail'] != '') {
    $result = modifyAccount($_POST['oldEmail'], $userData);
} else {
    $result = createAccount($userData);
}
if (!$result) {
    echo 'Account Configuration Failure';
    exit();
}

// If the admin modified their own account update their session data
if ((isset($_POST['oldEmail']) && $_POST['oldEmail'] != '') && $_POST['oldEmail'] == $_SESSION['account']['email']) {
    $_SESSION['account'] = $userData;
}

echo 'SUCCESS';

?>
