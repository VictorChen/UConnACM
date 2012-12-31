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
            function test(){
                alert("test");
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
                        <div class="span8">
                            <div onClick="test();" class="home-category" id="courses-category">Courses</div>
                            <div class="home-category" id="textbooks-category">Textbooks</div>
                            <div class="home-category" id="acm-category">ACM</div>
                            <div class="home-category" id="programming-category">Programming</div>
                            <div class="home-category" id="jobs-category">Jobs / Internships</div>
                            <div class="home-category" id="introduce-category">Introduce Yourself</div>
                            <div class="home-category" id="resources-category">Resources</div>
                            <div class="home-category" id="anything-category">Anything</div>
                        </div>
                        <div class="span2">
                            <span class="heading">Recently Posted:</span>
                            <ul class="nav nav-tabs nav-stacked">
                                <li><a href="#">test</a></li>
                                <li><a href="#">test</a></li>
                                <li><a href="#">test</a></li>
                                <li><a href="#">test</a></li>
                                <li><a href="#">testesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttestt</a></li>
                                <li><a href="#">test</a></li>
                                <li><a href="#">test</a></li>
                                <li><a href="#">test</a></li>
                                <li><a href="#">test</a></li>
                                <li><a href="#">test</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <footer></footer>
        </div>
    </body>
</html>