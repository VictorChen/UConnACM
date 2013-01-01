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
            function showCategoryList(category, categoryID){
                $(".home-category").removeClass("transition");
                $(".home-category").hide("slow");
                $("#backbtn").show("slow");
                var title = $("#"+categoryID).text();
                $("#category-title").text(title).show("slow");

                $.ajax({
                    type: "POST",
                    url: "retrieveCategoryPosts.php",
                    data: {category: category}
                }).done(function(results) {
                    $("#category-list").append(results).show("slow");
                });
            }

            function backToCategory(){
                $("#backbtn").hide("slow");
                $("#category-title").hide("slow");
                $("#category-list").hide("slow").empty();
                $(".home-category").show("slow");
                setTimeout(function(){
                    $(".home-category").addClass("transition"); // delay adding transition css until categories are shown
                },1000);
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
                            <button onClick="backToCategory();" id="backbtn" class="btn" style="display: none;"><i class="icon-arrow-left"></i> Back</button>
                            <span id="category-title" style="display: none;"></span>
                            <div onClick="showCategoryList('courses', 'courses-category');" class="home-category transition" id="courses-category">Courses</div>
                            <div onClick="showCategoryList('textbooks', 'textbooks-category');" class="home-category transition" id="textbooks-category">Textbooks</div>
                            <div onClick="showCategoryList('acm', 'acm-category');" class="home-category transition" id="acm-category">ACM</div>
                            <div onClick="showCategoryList('programming', 'programming-category');" class="home-category transition" id="programming-category">Programming</div>
                            <div onClick="showCategoryList('jobs', 'jobs-category');" class="home-category transition" id="jobs-category">Jobs / Internships</div>
                            <div onClick="showCategoryList('introduce', 'introduce-category');" class="home-category transition" id="introduce-category">Introduce Yourself</div>
                            <div onClick="showCategoryList('resources', 'resources-category');" class="home-category transition" id="resources-category">Resources</div>
                            <div onClick="showCategoryList('anything', 'anything-category');" class="home-category transition" id="anything-category">Anything</div>
                            <ul id="category-list" style="display: none;"></ul>
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