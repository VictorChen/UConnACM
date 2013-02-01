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
            $(function(){
                displayHeader("../../",4);
                displayFooter("../../");

                $("#team-next").click(function(){
                    var numTeams = $.trim($("#team-num").val());
                    function isInteger(n){
                        return Math.floor(n) == n && $.isNumeric(n) && n>0;
                    }
                    if (isInteger(numTeams)){
                        $("#team-build-1").hide("slow");
                        $("#team-build-2").show("slow");
                        $("#errorMsg").slideUp("fast").empty();
                        for (var i=1; i<=numTeams; i++){
                            $("<div style='display: none;' id='teambox"+i+"' class='team-box'><legend class='team-legend'><span class='team-name'>Team "+i+"</span></legend><ol></ol></div>").appendTo("#content-area").fadeIn("slow");
                        }
                    }else{
                        $("#errorMsg").empty().append("Please enter the number of teams!").slideDown("fast");
                    }
                });

                function addName(){
                    var name = $.trim($("#team-name").val());
                    if (name === ""){
                        $("#errorMsg").empty().append("Please enter a name!").slideDown("fast");
                    }else{
                        var boxArray = [];
                        var liCount = 0;
                        var min = Number.POSITIVE_INFINITY;
                        var randomNum;
                        var i;
                        $("#errorMsg").slideUp("fast").empty();
                        $(".team-box").each(function(key,value) {
                            liCount = $(value).find("li").length;
                            if (liCount < min)
                                min = liCount;
                        });
                        $(".team-box").each(function(key,value) {
                            liCount = $(value).find("li").length;
                            if (liCount === min){
                                boxArray.push($(value).attr("id"));
                            }
                        });
                        for (i=0; i<boxArray.length; i++){
                            randomNum = Math.floor(Math.random() * boxArray.length);
                            $("<li style='display: none;'>"+name+"</li>").appendTo($("#"+boxArray[randomNum]).find("ol")).slideDown("fast");
                            break;
                        }
                        $("#team-name").val('').focus();
                    }
                }
                $('#team-name').keypress(function(e){
                    if (e.keyCode == '13') addName();
                });
                $("#team-add").click(addName);
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
                        <div id="content-area" class="span10">
                            <div id="errorMsg" class="alert alert-danger" style="display: none;"></div>
                            <div class="team-input">
                                <div id="team-build-1" class="input-append">
                                    <label for="team-num">Number of Teams:</label>
                                    <input id="team-num" class="input-align" type="number" min="1">
                                    <button id="team-next" class="btn">Next</button>
                                </div>
                                <div id="team-build-2" class="input-append" style="display: none;">
                                    <label for="team-name" >Name:</label>
                                    <input id="team-name" class="input-align" type="text">
                                    <button id="team-add" class="btn btn-primary">Add</button>
                                    <hr>
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