<?php

/** Account Data Structure as stored in an associative array and should be passed into functions as such
 *  'email'
 *  'password' (post-hashing)
 *  'salt' (must be 22 characters)
 *  'authLevel' (1 for admin, 0 for normal, can be expanded to more levels if needed)
 *  'firstName'
 *  'lastName'
 *  'major'
 *  'year'
 *  'aboutMe'
 */

// Add code to make absolutely sure login data is limited to acm domain
// Note these two locations both need to exist and have full permissions for the system to work
session_save_path('/u/acm/storage/sessions');
$databaseLocation = '/u/acm/storage/accounts/';

// Create or load a session for the current user
session_start();

// Creates a new account as long as one with the same email doesn't already exist
function createAccount($accountData) {
    ignore_user_abort(true);
    global $databaseLocation;
    
    // Do a weak test to make sure the account data is at least valid enough to prevent crashes
    if (!validateAccountData($accountData)) return FALSE;
    
    // Make sure the right file is targeted and that it doesn't already exist
    $targetFile = $databaseLocation . hashEmail($accountData['email']);
    if (file_exists($targetFile)) return FALSE;
    
    // Convert the account data to a numerical array for storage
    $accountData = dissociateAccountData($accountData);
    
    // Open the file
    $handle = fopen($targetFile, 'w');
    if ($handle === FALSE) return FALSE;
    
    // Store the account data and close the file
    $result = fputcsv($handle, $accountData);
    fclose($handle);
    if ($result === FALSE) return FALSE;
    
    ignore_user_abort(false);
    
    return TRUE;
    
}

// Deletes a currently existing account from the database
function deleteAccount($email) {
    global $databaseLocation;
    
    // Make sure the right file is targeted for deletion
    $targetFile = $databaseLocation . hashEmail($email);
    if (!file_exists($targetFile)) return FALSE;
    
    return unlink($targetFile);
}

// Gets an array containing data on a given account if it exists
function getAccountData($email) {
    return getAccountDataByHash(hashEmail($email));
}

// Gets an array containing data on a given account based on a given hash
function getAccountDataByHash($hash) {
    global $databaseLocation;

    // Make sure the right file is found
    $targetFile = $databaseLocation . $hash;
    if (!file_exists($targetFile)) return FALSE;
    
    // Open a handle to the file
    $handle = @fopen($targetFile, 'r');
    if ($handle === FALSE) return FALSE;
    
    // Read the file then close it
    $rawData = fgetcsv($handle);
    fclose($handle);
    if ($rawData === FALSE) return FALSE;
    
    return associateAccountData($rawData);
}

// Modify the properties of an account
function modifyAccount($oldEmail, $accountData) {
    ignore_user_abort(true);
    deleteAccount($oldEmail);
    $result = createAccount($accountData);
    ignore_user_abort(false);
    return $result;
}

// Check the password on an account against a given one to see if it matches
function checkPassword($email, $pass) {
    $accountData = getAccountData($email);
    if ($accountData === FALSE) return FALSE;
    return $accountData['password'] == hashPassword($pass, $accountData['salt']);
}

// Check if the user is currently logged in
function checkLoggedIn() {
    
    //Check if there is an account assigned to the current session
    if (!isset($_SESSION['account'])) return FALSE;
    
    return TRUE;
}

// Check if the user is currently logged in and has admin privilages
function checkAdmin() {
    // Make sure the user is logged in first to pervent any obvious errors
    if (checkLoggedIn() === FALSE) return FALSE;
    
    // Make sure the user has an authorization level of 1
    if ($_SESSION['account']['authLevel'] != 1) return FALSE;
    
    return TRUE;
}

// Note: All account quaries besides the two above can easily be made just by accessing the 'account' array
//       in the session variable.

// Logs the user in to a specific account, logging them out of any other account in the process.
// NOTE: This function does not check passwords. Please use checkPassword to verify the user
//       prior to calling this function.
function logUserIn($email) {
    // Retrieve and verify the presense of the account.
    $userData = getAccountData($email);
    if ($userData === FALSE) return FALSE;
    
    // Assign the account to the current session
    $_SESSION['account'] = $userData;
    return TRUE;
    
}

// Logs the user out of any account
function logUserOut() {
    unset($_SESSION['account']);
}

// ********** UTILITY FUNCTIONS HERE **********

// Creates an alphanumeric salt containing {$charCount} characters
function generateSalt($charCount) {
    $charList = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charListEnd = strlen($charList) - 1;
    $salt = '';
    while ($charCount > 0) {
        $salt .= $charList{mt_rand(0, $charListEnd)};
        $charCount--;
    }
    return $salt;

}

// Hashes an email for lookup in the file system
function hashEmail($email) {
    return strlen($email) . '_' . md5($email);
}

// Hashes the password with the provided 22-character salt
function hashPassword($pass, $salt) {
    $algorithmHeader = '$2a$04$';
    return crypt($pass, $algorithmHeader . $salt);
}

// Makes sure all indexes in an associateively indexed array exist
function validateAccountData($accountData) {
    if (!isset($accountData['email']))        return FALSE;
    if (!isset($accountData['password']))     return FALSE;
    if (!isset($accountData['salt']))         return FALSE;
    if (!isset($accountData['authLevel']))    return FALSE;
    if (!isset($accountData['firstName']))    return FALSE;
    if (!isset($accountData['lastName']))     return FALSE;
    if (!isset($accountData['major']))        return FALSE;
    if (!isset($accountData['year']))         return FALSE;
    if (!isset($accountData['aboutMe']))      return FALSE;
    return TRUE;
}

// Converts a numerically indexed account data array to a associatively indexed one
function associateAccountData($accountData) {
    $result = array();
    
    // Mandatory Fields
    $result['email'] = $accountData[0];
    $result['password'] = $accountData[1];
    $result['salt'] = $accountData[2];
    $result['authLevel'] = $accountData[3];
    $result['firstName'] = $accountData[4];
    $result['lastName'] = $accountData[5];
    
    // Optional Fields
    $result['major'] = $accountData[6];
    $result['year'] = $accountData[7];
    $result['aboutMe'] = $accountData[8];
    
    return $result;
}

// Converts a associatively indexed account data array to a numerically indexed one
function dissociateAccountData($accountData) {
    $result = array();
    
    // Mandatory Fields
    $result[0] = $accountData['email'];
    $result[1] = $accountData['password'];
    $result[2] = $accountData['salt'];
    $result[3] = $accountData['authLevel'];
    $result[4] = $accountData['firstName'];
    $result[5] = $accountData['lastName'];
    
    // Optional Fields
    $result[6] = $accountData['major'];
    $result[7] = $accountData['year'];
    $result[8] = $accountData['aboutMe'];
    
    return $result;
}

// ********** END OF UTILITY FUNCTIONS **********
?>
