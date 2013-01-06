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
        <script src="../../js/forum.js"></script>
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
                        <div id="category-container" class="span8">
                            <div class="home-category" value="courses" title="Courses" id="courses-category">Courses</div>
                            <div class="home-category" value="textbooks" title="Textbooks" id="textbooks-category">Textbooks</div>
                            <div class="home-category" value="acm" title="ACM" id="acm-category">ACM</div>
                            <div class="home-category" value="programming" title="Programming" id="programming-category">Programming</div>
                            <div class="home-category" value="jobs" title="Jobs / Internships" id="jobs-category">Jobs / Internships</div>
                            <div class="home-category" value="introduce" title="Introduce Yourself" id="introduce-category">Introduce Yourself</div>
                            <div class="home-category" value="resources" title="Resources" id="resources-category">Resources</div>
                            <div class="home-category" value="anything" title="Anything" id="anything-category">Anything</div>
                            
                            <div id="topicSuccessMessage" class="alert alert-success" style="display: none;"></div>
                            <div id="topicFailureMessage" class="alert alert-danger" style="display: none;"></div>
                            <button onClick="backToCategory();" id="backbtn1" class="btn" style="display: none;"><i class="icon-arrow-left"></i> Back</button>
                            <a id="create-post" class="btn btn-primary" href="#topicModal" data-toggle="modal" style="display: none;"><i class="icon-plus icon-white"></i> Create Topic</a>
                            <span id="category-title" style="display: none;"></span>
                            <ul id="category-list" style="display: none;"></ul>

                            <button onClick="backToCategoryList();" id="backbtn2" class="btn" style="display: none;"><i class="icon-arrow-left"></i> Back</button>
                            <?php if (checkAdmin()) { ?>
                                <a id="edit-topic-btn" class="btn btn-primary" onClick="loadTopicXML();" href="#editModal" data-toggle="modal" style="display: none;">Edit Topic</a>
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

        <!-- Modal for editing topic -->
        <div id="editModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="configModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h3>Edit Topic</h3>
            </div>
            <div class="modal-body">
                <div id="editErrorMessage" class="alert alert-error" style="display: none;"></div>
                <textarea id="editTopicContent" rows="7"></textarea>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                <button id="editTopicSubmitButton" class="btn btn-primary" onclick="editTopic()">Submit</button>
            </div>
        </div>
    </body>
</html>