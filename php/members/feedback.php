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
		<title>Member's Area</title>
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
            function validateForm(){
                var feedback = $("#inputSuggestions").val();
                if ($.trim(feedback).length == 0){
                    $("#success-info").slideUp();
                    $("#error-info").slideDown();
                    return false;
                }
                $.ajax({
                    type: "POST",
                    url: "feedback_submit.php",
                    data: {inputSuggestions: feedback}
                }).done(function(msg) {
                    $("#inputSuggestions").val('');
                    $("#error-info").slideUp();
                    $("#success-info").slideDown();
                });
            }

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
                            <div id="success-info" class="alert alert-success" style="display: none;">
                                Success! Thanks for giving us a feedback. We will definitely consider it!
                            </div>
                            <div id="error-info" class="alert alert-error" style="display: none;">
                                Feedback is empty...
                            </div>
							<div class="signup-box well">
								<legend>Feedback</legend>
                                <div class="alert alert-info">
                                    Let us know how we're doing! We're happy to take suggestions from our members to improve ACM. Remember, this is completely anonymous so say anything you want!
                                </div>
								<div class="control-group">
									<label class="control-label" for="inputSuggestions">Leave us a feedback!</label>
									<div class="controls">
										<textarea id="inputSuggestions" name="inputSuggestions"></textarea>
									</div>
								</div>
								<div class="control-group">
									<div class="controls">
										<button onClick="validateForm();" class="btn btn-primary">Submit</button>
									</div>
								</div>
							</div>
                        </div>
                    </div>
                </div>
            </div>
            <footer></footer>
        </div>
    </body>
</html>