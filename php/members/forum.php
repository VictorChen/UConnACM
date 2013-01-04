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
                $("#topicSuccessMessage").slideUp("fast").empty();
                $(".home-category").hide("fast");
                $("#backbtn1").show("fast");
                $("#create-post").show("fast");
                $("#category-title").text(title).show("fast");
                currentCategory = [$(this).attr("value"), title];
                retrieveTopics();
            }

            function retrieveTopics(){
                $.ajax({
                    type: "POST",
                    url: "retrieveTopics.php",
                    data: {category: currentCategory[0]}
                }).done(function(results) {
                    $("#category-list").empty().append(results).show("fast");
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
                $("#topicSuccessMessage").slideUp("fast").empty();
                $("#topicFailureMessage").slideUp("fast").empty();
                $("#backbtn1").hide("fast");
                $("#create-post").hide("fast");
                $("#category-title").hide("fast");
                $("#category-list").hide("fast").empty();
                $(".home-category").show("fast");
            }

            function backToCategoryList(){
                $("#topicSuccessMessage").slideUp("fast").empty();
                $("#topicFailureMessage").slideUp("fast").empty();
                $("#backbtn2").hide("fast");
                $("#delete-topic-btn").hide("fast");
                $("#chat-title").hide("fast");
                $("#chat-content").hide("fast");
                $("#chat-box").hide("fast").empty();
                $("#chat-area").hide("fast");
                $("#chat-post-btn").hide("fast");
                $("#backbtn1").show("fast");
                $("#create-post").show("fast");
                $("#category-title").text(currentCategory[1]).show("fast");
                retrieveTopics();
            }

            function transitionToChat(){
                $("#topicSuccessMessage").slideUp("fast").empty();
                $("#topicFailureMessage").slideUp("fast").empty();
                $("#backbtn1").hide("fast");
                $("#create-post").hide("fast");
                $("#category-list").hide("fast").empty();
                $("#backbtn2").show("fast");
                $("#delete-topic-btn").show("fast");
                $("#chat-area").show("fast");
                $("#chat-post-btn").show("fast");
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
                        $("#topicFailureMessage").empty().append(results.error).slideDown("fast");
                    }else{
                        $("#chat-title").empty().append(results.title).show("fast");
                        $("#chat-content").empty().append(results.content).show("fast");
                        $("#chat-box").empty().append(results.messages).show("fast");
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
                        $("#topicSuccessMessage").empty().append("Success! Topic has been posted").slideDown("fast");
                        $("#topicFailureMessage").slideUp("fast").empty();
                        $('#topicModal').modal('hide')
                        $("#category-list").empty();
                        retrieveTopics();
                        updateRecent();
                    }else{
                        $("#modalErrorMessage").empty().append(results).slideDown("fast");
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
                        $("#topicFailureMessage").slideUp("fast").empty();
                        $("#chat-box").empty();
                        $("#chat-area").val('');
                        showChat();
                        updateRecent();
                    }else{
                        $("#topicFailureMessage").empty().append(results).slideDown("fast");
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
                $(".home-category").hide("fast");
                $("#topicSuccessMessage").slideUp("fast").empty();
                $("#topicFailureMessage").slideUp("fast").empty();
                $("#backbtn1").hide("fast");
                $("#create-post").hide("fast");
                $("#category-list").hide("fast").empty();
                $("#category-title").text(currentCategory[1]).show("fast");
                $("#backbtn2").show("fast");
                $("#delete-topic-btn").show("fast");
                $("#chat-area").show("fast");
                $("#chat-post-btn").show("fast");

                showChat();
            }

            function deleteTopic(){
                $.ajax({
                    type: "POST",
                    url: "deleteTopic.php",
                    data: {filename: currentFilename, category: currentCategory[0]}
                }).done(function(results){
                    if (results === "success"){
                        backToCategoryList();
                        $("#topicSuccessMessage").empty().append("Topic has been deleted").slideDown("fast");
                        updateRecent();
                    }else{
                        $("#topicFailureMessage").empty().append(results).slideDown("fast");
                    }
                });
            }

            $(function(){
                displayHeader("../../",4);
                displayFooter("../../");

                // Show recent topics on page load
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
                                <a id="delete-topic-btn" class="btn btn-danger" href="#deleteModal" data-toggle="modal" style="display: none;">Delete Topic</a>
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