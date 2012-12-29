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
		<title><?php echo $_GET["title"] ?></title>
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
            $(function(){
                displayHeader("../../",4);
                displayFooter("../../");
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
                            <?php
								if ($_GET["type"] == "success"){
									echo "<div class='content-padded'><div class='alert alert-success'>" . $_GET["msg"] . "</div><img class='confirm-img' src='http://acm.uconn.edu/img/dog.jpg' /></div>";
								}else{
									$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
									echo "<div class='content-padded'><div class='alert alert-error'>" . $_GET["msg"] . "</div><img class='confirm-img' src='http://acm.uconn.edu/img/dog2.jpg' /><br><a class='btn btn-primary' href='$url'>Go Back</a></div>";
								}
							?>
                        </div>
                    </div>
                </div>
            </div>
            <footer></footer>
        </div>
    </body>
</html>