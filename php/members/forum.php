<?php
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
            var currentCategory;
            var currentFilename;

            function transitionToList(){
                var title = $(this).text();
                $("#topicSuccessMessage").slideUp("slow").empty();
                $(".home-category").hide("slow");
                $("#backbtn1").show("slow");
                $("#create-post").show("slow");
                $("#category-title").text(title).show("slow");
                currentCategory = [$(this).attr("value"), title];
                retrieveTopics();
            }

            function retrieveTopics(){
                $.ajax({
                    type: "POST",
                    url: "retrieveTopics.php",
                    data: {category: currentCategory[0]}
                }).done(function(results) {
                    $("#category-list").empty().append(results).show("slow");
                    $('.post-author').click(function(event){
                        event.stopImmediatePropagation();   // Stop chat from showing when user is clicked
                        $("#configModal").modal('show');    // Show the user's profile
                    });
                    $(".post-delete-btn").click(function(){
                        event.stopImmediatePropagation();
                        currentFilename = $(this).parent().find(".post-filename").text();
                        $("#deleteModal").modal('show');
                    });
                    $(".category-post").click(transitionToChat);
                });
            }

            function backToCategory(){
                $("#topicSuccessMessage").slideUp("slow").empty();
                $("#topicFailureMessage").slideUp("slow").empty();
                $("#backbtn1").hide("slow");
                $("#create-post").hide("slow");
                $("#category-title").hide("slow");
                $("#category-list").hide("slow").empty();
                $(".home-category").show("slow");
            }

            function backToCategoryList(){
                $("#topicSuccessMessage").slideUp("slow").empty();
                $("#topicFailureMessage").slideUp("slow").empty();
                $("#backbtn2").hide("slow");
                $("#delete-topic-btn").hide("slow");
                $("#chat-title").hide("slow");
                $("#chat-content").hide("slow");
                $("#chat-box").hide("slow").empty();
                $("#chat-area").hide("slow");
                $("#chat-post-btn").hide("slow");
                $("#backbtn1").show("slow");
                $("#create-post").show("slow");
                $("#category-title").text(currentCategory[1]).show("slow");
                retrieveTopics();
            }

            function transitionToChat(){
                $("#topicSuccessMessage").slideUp("slow").empty();
                $("#topicFailureMessage").slideUp("slow").empty();
                $("#backbtn1").hide("slow");
                $("#create-post").hide("slow");
                $("#category-list").hide("slow").empty();
                $("#backbtn2").show("slow");
                $("#delete-topic-btn").show("slow");
                $("#chat-area").show("slow");
                $("#chat-post-btn").show("slow");
                currentFilename = $(this).find(".post-filename").text();
                showChat();
            }

            function showChat(){
                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: "showChat.php",
                    data: {filename: currentFilename, category: currentCategory[0]}
                }).done(function(results){
                    if (results.error){
                        $("#topicFailureMessage").empty().append(results.error).slideDown("slow");
                    }else{
                        $("#chat-title").empty().append(results.title).show("slow");
                        $("#chat-content").empty().append(results.content).show("slow");
                        $("#chat-box").empty().append(results.messages).show("slow");
                        $("#chat-box").scrollTop($("#chat-box")[0].scrollHeight);
                    }
                });
            }

            function createTopic(){
                $.ajax({
                    type: "POST",
                    url: "createTopic.php",
                    data: {title: $("#topicTitle").val(), content: $("#topicContent").val(), category: currentCategory[0]}
                }).done(function(results) {
                    if (results === "success"){
                        $("#topicSuccessMessage").empty().append("Success! Topic has been posted").slideDown("slow");
                        $("#topicFailureMessage").slideUp("slow").empty();
                        $('#topicModal').modal('hide')
                        $("#category-list").empty();
                        retrieveTopics();
                        updateRecent();
                    }else{
                        $("#modalErrorMessage").empty().append(results).slideDown("slow");
                    }
                });
            }

            function createMessage(){
                $.ajax({
                    type: "POST",
                    url: "createMessage.php",
                    data: {filename: currentFilename, message: $("#chat-area").val(), category: currentCategory[0]}
                }).done(function(results) {
                    if (results === "success"){
                        $("#topicFailureMessage").slideUp("slow").empty();
                        $("#chat-box").empty();
                        $("#chat-area").val('');
                        showChat();
                        updateRecent();
                    }else{
                        $("#topicFailureMessage").empty().append(results).slideDown("slow");
                    }
                });
            }

            function updateRecent(){
                $.ajax({
                    type: "POST",
                    url: "updateRecent.php"
                }).done(function(results){
                    $("#recently-posted").empty().append(results);
                    $(".recent-post").click(showChatFromRecent);
                });
            }

            function showChatFromRecent(){
                var category = $(this).next().next().text();
                $(".home-category").each(function(){
                    if ($(this).attr("value") === category){
                        currentCategory = [category, $(this).text()];
                    }
                });
                currentFilename = $(this).next().text();
                $(".home-category").hide("slow");
                $("#topicSuccessMessage").slideUp("slow").empty();
                $("#topicFailureMessage").slideUp("slow").empty();
                $("#backbtn1").hide("slow");
                $("#create-post").hide("slow");
                $("#category-list").hide("slow").empty();

                $("#category-title").text(currentCategory[1]).show("slow");
                $("#backbtn2").show("slow");
                $("#delete-topic-btn").show("slow");
                $("#chat-area").show("slow");
                $("#chat-post-btn").show("slow");

                showChat();
            }

            function deleteTopic(){
                $.ajax({
                    type: "POST",
                    url: "deleteTopic.php",
                    data: {filename: currentFilename, category: currentCategory[0]}
                }).done(function(results){
                    if (results === "success"){
                        $("#topicSuccessMessage").empty().append("Topic has been deleted").slideDown("slow");
                        retrieveTopics();
                        updateRecent();
                    }else{
                        $("#topicFailureMessage").empty().append(results).slideDown("slow");
                    }
                });
            }

            $(function(){
                displayHeader("../../",4);
                displayFooter("../../");

                updateRecent();
                
                $(".home-category").click(transitionToList);


                // Clear topic form on exit
                $('#topicModal').on('hidden',function(){
                    $("#modalErrorMessage").empty().hide();
                    $("#topicTitle").val('');
                    $("#topicContent").val('');
                    
                });
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
                        <div class="span8" style="margin-bottom: 20px;">
                            <div class="home-category" value="courses" id="courses-category">Courses</div>
                            <div class="home-category" value="textbooks" id="textbooks-category">Textbooks</div>
                            <div class="home-category" value="acm" id="acm-category">ACM</div>
                            <div class="home-category" value="programming" id="programming-category">Programming</div>
                            <div class="home-category" value="jobs" id="jobs-category">Jobs / Internships</div>
                            <div class="home-category" value="introduce" id="introduce-category">Introduce Yourself</div>
                            <div class="home-category" value="resources" id="resources-category">Resources</div>
                            <div class="home-category" value="anything" id="anything-category">Anything</div>
                            
                            <div id="topicSuccessMessage" class="alert alert-success" style="display: none;"></div>
                            <div id="topicFailureMessage" class="alert alert-danger" style="display: none;"></div>
                            <button onClick="backToCategory();" id="backbtn1" class="btn" style="display: none;"><i class="icon-arrow-left"></i> Back</button>
                            <a id="create-post" class="btn btn-primary" href="#topicModal" data-toggle="modal" style="display: none;"><i class="icon-plus icon-white"></i> Create Topic</a>
                            <span id="category-title" style="display: none;"></span>
                            <ul id="category-list" style="display: none;"></ul>

                            <button onClick="backToCategoryList();" id="backbtn2" class="btn" style="display: none;"><i class="icon-arrow-left"></i> Back</button>
                            <?php if (checkAdmin()) { ?>
                                <button onClick='deleteTopic();' id="delete-topic-btn" class="btn btn-danger" style="display: none;">Delete Topic</button>
                            <?php } ?>
                            <div style="display: none;" id="chat-title"></div>
                            <div style="display: none;" id="chat-content"></div>
                            <div style="display: none;" id="chat-box"></div>
                            <textarea style="display: none;" type="text" id="chat-area"></textarea>
                            <button onClick="createMessage();" id="chat-post-btn" class="btn btn-primary" style="display: none; float: right;"><i class="icon-pencil icon-white"></i> Post</button>
                        </div>
                        <div class="span2">
                            <ul id="recently-posted" class="nav nav-tabs nav-stacked"></ul>
                        </div>
                    </div>
                </div>
            </div>
            <footer></footer>
        </div>


        <!-- Modal for creating topic -->
        <div id="topicModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="configModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h3 id="topicModalLabel">Create Topic</h3>
            </div>
            <div class="modal-body">
                <div class="row-fluid">
                    <div id="modalErrorMessage" class="alert alert-error" style="display: none;"></div>
                    <form class="form-horizontal" id="topicForm">
                        <div class="control-group">
                            <label class="control-label" for="topicTitle">Title:</label>
                            <div class="controls">
                                <input type="text" id="topicTitle"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="topicContent">Content:</label>
                            <div class="controls">
                                <textarea id="topicContent" rows="7"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                <button id="topicSubmitButton" class="btn btn-primary" onclick="createTopic()">Submit</button>
            </div>
        </div>

        <!-- Modal for deleting topic -->
        <div id="deleteModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h3 id="deleteModalLabel">Delete Confirmation</h3>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this topic?</p>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                <button class="btn btn-primary" data-dismiss="modal" onclick="deleteTopic();">Delete</button>
            </div>
        </div>
    </body>
</html>