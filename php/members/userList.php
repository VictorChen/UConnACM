<?php
// Load the account system
require_once('accountSystem.php');

// Redirect to login page if not logged in
if (!checkLoggedIn()) {
    header( 'Location: http://acm.uconn.edu/php/members/login.php' );
}

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Members</title>
		<meta charset="UTF-8">
		<meta name="robots" content="noindex">
		<link rel="stylesheet" href="../../css/style.css" type="text/css" />
		<link rel="stylesheet" href="../../css/bootstrap.css" type="text/css" />
        <link rel="stylesheet" href="../../css/members.css" type="text/css" />
		<link rel="shortcut icon" href="img/icon.ico">
		<script src="../../js/lib/jquery.js"></script>
        <script src="../../js/lib/jquery.tablesorter.min.js"></script>
		<script src="../../js/lib/bootstrap.min.js"></script>
        <script src="../../js/header.js"></script>
		<script src="../../js/footer.js"></script>
		<script src="../../js/googleanalytics.js"></script>
        <script src="../../js/userConfig.js"></script>
        <script>
        <?php if (checkAdmin()) { ?>
            // Delete account functions
            var currentAccountHash = "";
            
            function setCurrentAccount(accountHash) {
                currentAccountHash = accountHash;
            }
            
            function deleteCurrentAccount() {
                $.get('deleteUser.php?hash=' + currentAccountHash, function() {
                    document.location.reload(true);
                });
            }
            
            function loadAccountData(accountHash) {
                document.getElementById('failureMessage').innerHTML = '';
                $.getJSON('getAccountData.php?hash=' + accountHash, function(data) {
                    if (data.success) {
                        document.getElementById('oldEmail').value = data.email;
                        document.getElementById('email').value = data.email;
                        document.getElementById('pass').value = "XXXXXXXXXXXXXXXXXXXX";
                        document.getElementById('firstName').value = data.firstName;
                        document.getElementById('lastName').value = data.lastName;
                        document.getElementById('admin').checked = data.admin;
                    }
                });
            }
            
            function resetConfigForm() {
                document.getElementById('failureMessage').innerHTML = '';
                document.getElementById('oldEmail').value = "";
                document.getElementById('email').value = "";
                document.getElementById('pass').value = "";
                document.getElementById('firstName').value = "";
                document.getElementById('lastName').value = "";
                document.getElementById('admin').checked = "";
            }
        <?php } ?>

        // JQuery procedures
        $(function(){
            displayHeader("../../",4);
            displayFooter("../../");
            $('#accountTable').tablesorter({sortList:[[0,0]]});
        });
        </script>
    </head>
    <body>
        <div class="page-wrapper">
            <div class="content-wrapper">
                <?php include('welcome.php'); ?>
                <div class="container-fluid">
                    <div class="row-fluid">
                        <div class="span2">
                            <?php include("quickLinks.php"); ?>
                        </div>
                        <div class="span10">
                            <?php if (checkAdmin()) { ?>
                                <!-- Modal -->
                                <div id="configModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="configModalLabel" aria-hidden="true">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                                        <h3 id="configModalLabel">User Configuration</h3>
                                    </div>
                                    <div class="modal-body">
                                        <form class="form-horizontal" id="configForm">
                                            <input type="hidden" id="oldEmail" name="oldEmail"/>
                                            <div class="control-group">
                                                <label class="control-label" for="email">Email:</label>
                                                <div class="controls">
                                                    <input type="text" id="email" name="email"/>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="pass">Password:</label>
                                                <div class="controls">
                                                    <input type="password" id="pass" name="pass" onclick="document.getElementById('pass').select()" onblur="passwordBlurCheck()" />
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="firstName">First Name:</label>
                                                <div class="controls">
                                                    <input type="text" id="firstName" name="firstName" />
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="lastName">Last Name:</label>
                                                <div class="controls">
                                                    <input type="text" id="lastName" name="lastName" />
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="admin">Admin:</label>
                                                <div class="controls">
                                                    <input type="checkbox" id="admin" name="admin" value="true" />
                                                </div>
                                            </div>
                                            <label id="failureMessage" style="color: red; font-weight: bold;"/>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                                        <button class="btn btn-primary" onclick="trySubmitConfigForm('configForm', 'failureMessage')">Submit</button>
                                    </div>
                                </div>
                                
                                
                                <div id="deleteModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                                        <h3 id="deleteModalLabel">Delete Confirmation</h3>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete this user?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                                        <button class="btn btn-primary" onclick="deleteCurrentAccount()">Delete</button>
                                    </div>
                                </div>
                                
                                <!-- Modal -->
                                <div id="deleteSelfModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="deleteSelfModalLabel" aria-hidden="true">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                                        <h3 id="deleteSelfModalLabel">Delete Refused</h3>
                                    </div>
                                    <div class="modal-body">
                                        <p>You cannot delete your own account.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                                    </div>
                                </div>
                            <?php } ?>

                            <table id="accountTable" class="table table-bordered table-striped tablesorter">
                                <thead>
                                    <tr>
                                        <th>Email</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Admin</th>
                                        <?php if (checkAdmin()) { ?>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                        <?php } ?>
                                        <th>Profile</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if ($handle = opendir($databaseLocation)) {
                                            while (false !== ($entry = readdir($handle))) {
                                                if ($entry != '.' && $entry != '..') {
                                                    $account = getAccountDataByHash($entry);
                                                    echo '<tr><td>' . $account['email'] . '</td>';
                                                    echo '<td>' . $account['firstName'] . '</td>';
                                                    echo '<td>' . $account['lastName'] . '</td>';
                                                    echo '<td>';
                                                    if ($account['authLevel'] == 1) echo 'Yes';
                                                    else                            echo 'No';
                                                    echo '</td>';
                                                    if (checkAdmin()) {
                                                        echo '<td><a class="btn btn-primary" href="#configModal" onclick="loadAccountData(\'' . $entry . '\')" data-toggle="modal">Edit</a></td>';
                                                        echo '<td>';
                                                        if ($account['email'] == $_SESSION['account']['email']) echo '<a class="btn btn-danger" href="#deleteSelfModal" data-toggle="modal">';
                                                        else echo '<a class="btn btn-danger" href="#deleteModal" onclick="setCurrentAccount(\'' . $entry . '\')" data-toggle="modal">';
                                                        echo 'Delete</a></td>';
                                                    }
                                                    echo '<td><a class="btn" href="#">View Profile</a></td>';
                                                    echo '</tr>';
                                                }
                                            }
                                            closedir($handle);
                                        }
                                    ?>
                                </tbody>
                            </table>
                            <?php if (checkAdmin()) { ?>
                            <a href="#configModal" onclick="resetConfigForm()" class="btn" data-toggle="modal">Create New User</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <footer></footer>
        </div>
    </body>
</html>