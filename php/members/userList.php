<?php
// Load the account system
require_once('accountSystem.php');

// Redirect to login page if not logged in
if (!checkLoggedIn()) {
    header( 'Location: http://acm.uconn.edu/php/members/login.php' );
    exit();
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
		<link rel="shortcut icon" href="../../img/icon.ico">
		<script src="../../js/lib/jquery.js"></script>
        <script src="../../js/lib/jquery.tablesorter.min.js"></script>
		<script src="../../js/lib/bootstrap.min.js"></script>
        <script src="../../js/lib/jquery.form.js"></script>
        <script src="../../js/lib/checkimg.js"></script>
        <script src="../../js/header.js"></script>
		<script src="../../js/footer.js"></script>
		<script src="../../js/googleanalytics.js"></script>
        <script src="../../js/userConfig.js"></script>
        <script>
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
                            <?php if (checkAdmin()) { ?>
                                <a href="#configModal" onclick="resetConfigForm()" class="btn" data-toggle="modal">Create New User</a>
                            <?php } ?>
                            <table id="accountTable" class="table table-bordered table-striped tablesorter">
                                <thead>
                                    <tr>
                                        <th>User Picture</th>
                                        <th>Email</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Admin</th>
                                        <?php if (checkAdmin()) { ?>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                        <?php } else { ?>
                                        <th>Profile</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if ($handle = opendir($databaseLocation)) {
                                            while (false !== ($entry = readdir($handle))) {
                                                if ($entry != '.' && $entry != '..') {
                                                    $account = getAccountDataByHash($entry);
                                                    
                                                    echo '<tr><td><img src="http://acm.uconn.edu/accountImages/'.getUserImage($account['id']).'" width="50" height="50" /></td>';
                                                    echo '<td>' . $account['email'] . '</td>';
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
                                                    } else {
                                                        echo '<td><a class="btn" href="#configModal" onclick="loadAccountData(\'' . $entry . '\', true)" data-toggle="modal">View Profile</a></td>';
                                                    }
                                                    echo '</tr>';
                                                }
                                            }
                                            closedir($handle);
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <footer></footer>
        </div>
    </body>
</html>