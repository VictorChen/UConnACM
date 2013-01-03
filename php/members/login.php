<?php
    // Load the account system
    require_once('accountSystem.php');

    // Process the login if it has been submitted, display the login otherwise
    if (isset($_POST) && isset($_POST['email']) && isset($_POST['pass'])) {

        // Verify the user's password
        if (checkPassword($_POST['email'], $_POST['pass'])) {
            
            // Log the user in if there password is right
            if (logUserIn($_POST['email'])) {
            
                // Redirect to a given page if possible
                header( 'Location: http://acm.uconn.edu/php/members/forum.php' );
            
            } else {
            
                // Display a notice of a major failure
                echo 'Login System Failure';
                
            }
            
        }else{
            $badAttempt = true;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Login</title>
		<meta charset="UTF-8">
		<meta name="robots" content="noindex">
		<link rel="stylesheet" href="../../css/style.css" type="text/css" />
		<link rel="stylesheet" href="../../css/bootstrap.css" type="text/css" />
        <link rel="stylesheet" href="../../css/signup.css" type="text/css" />
		<link rel="shortcut icon" href="img/icon.ico">
		<script src="../../js/lib/jquery.js"></script>
		<script src="../../js/lib/bootstrap.min.js"></script>
        <script src="../../js/header.js"></script>
		<script src="../../js/footer.js"></script>
		<script src="../../js/googleanalytics.js"></script>
        <script type="text/javascript">
            $(function(){
                displayHeader("../../",4);
                displayFooter("../../");
            });
        </script>
    </head>
    <body>
        <div class="page-wrapper entrance">
            <div class="content-wrapper entrance">
                <div class="content-padded well signup-box-members">
                    <form class="form-horizontal" action="login.php" method="post">
                        <legend>Member's Login</legend>
                        <div class="control-group">
                            <label class="control-label" for="email">Email:</label>
                            <div class="controls">
                                <input type="text" id="email" name="email" placeholder="example@example.com" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="pass">Password:</label>
                            <div class="controls">
                                <input type="password" id="pass" name="pass" />
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <button type="submit" class="btn btn-primary">Enter !</button>
                            </div>
                        </div>
                        <?php if ($badAttempt) { ?>
                        <div class="control-group">
                            <div class="controls" style="color: red; font-weight: bold;">
                                Invalid Username or Password.
                            </div>
                        </div>
                        <?php } ?>
                    </form>
                </div>
            </div>
            <footer></footer>
        </div>
    </body>
</html>