var currentCategory;    // Array of 2 elements: [name of the category folder, category to display as text]
var currentFilename;    // String of the current topic (filename)
var processing = false; // Determines whether or not to load more topics
var currentPage = 1;    // The current page index, 1=categories, 2=topics list, 3=chat
var topicStartLoad = 0; // Determines which topics to start loading (from most recent)

function transitionToList(){
    var title = $(this).text();
    $("#topicSuccessMessage").slideUp("fast").empty();
    $(".home-category").hide("fast");
    $("#backbtn1").show("fast");
    $("#create-post").show("fast");
    $("#category-title").text(title).show("fast");
    currentCategory = [$(this).attr("value"), title];
    currentPage = 2;
    topicStartLoad = 0;
    processing = false;
    retrieveTopics();
}

function retrieveTopics(){
    // Prevent calling ajax multiple times
    if (processing)
        return false;
    processing = true;

    $.ajax({
        type: "POST",
        dataType: 'json',
        url: "retrieveTopics.php",
        data: {category: currentCategory[0], startFrom: topicStartLoad}
    }).done(function(results) {
        $("#category-list").append(results.topics).show("fast");
        $('.post-author').off('click');
        $('.post-author').click(function(event){
            event.stopImmediatePropagation();   // Stop chat from showing when user is clicked
            $("#configModal").modal('show');    // Show the user's profile
        });
        $('.post-delete-btn').off('click');
        $(".post-delete-btn").click(function(event){
            event.stopImmediatePropagation();
            currentFilename = $(this).parent().find(".post-filename").text();
            $("#deleteModal").modal('show');
        });
        $(".category-post").off('click');
        $(".category-post").click(transitionToChat);
        topicStartLoad += 10;
        if (results.status === "more")
            processing = false;
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
    currentPage = 1;
}

function backToCategoryList(){
    $("#topicSuccessMessage").slideUp("fast").empty();
    $("#topicFailureMessage").slideUp("fast").empty();
    $("#backbtn2").hide("fast");
    $("#delete-topic-btn").hide("fast");
    $("#edit-topic-btn").hide("fast");
    $("#chat-title").hide("fast");
    $("#chat-content").hide("fast");
    $("#chat-box").hide("fast").empty();
    $("#chat-area").hide("fast");
    $("#chat-post-btn").hide("fast");
    $("#category-list").hide().empty();
    $("#chat-area").val('');
    $("#backbtn1").show("fast");
    $("#create-post").show("fast");
    $("#category-title").text(currentCategory[1]).show("fast");
    currentPage = 2;
    topicStartLoad = 0;
    processing = false;
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
    $("#edit-topic-btn").show("fast");
    $("#chat-area").show("fast");
    $("#chat-post-btn").show("fast");
    currentFilename = $(this).find(".post-filename").text();
    currentPage = 3;
    showChat(true);
}

function showChat(animation, start){
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: "showChat.php",
        data: {filename: currentFilename, category: currentCategory[0], startFrom: start}
    }).done(function(results){
        if (results.error){
            $("#topicFailureMessage").empty().append(results.error).slideDown("fast");
        }else{
            $("#chat-title").empty().append(results.title).show("fast");
            $("#chat-content").empty().append(results.content).show("fast");
            $("#chat-box").show();
            var message = $(results.messages).prependTo("#chat-box");
            if (animation){
                message.slideDown("slow");
            }else{
                message.show();
            }
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
            topicStartLoad = 0;
            processing = false;
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
            $("#topicSuccessMessage").empty().append("Successfully posted message").slideDown("fast");
            $("#chat-box").empty();
            $("#chat-area").val('');
            showChat(false);
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
    $("#chat-area").val('');
    $("#chat-box").empty();
    $("#category-title").text(currentCategory[1]).show("fast");
    $("#backbtn2").show("fast");
    $("#delete-topic-btn").show("fast");
    $("#edit-topic-btn").show("fast");
    $("#chat-area").show("fast");
    $("#chat-post-btn").show("fast");
    currentPage = 3;
    showChat(true);
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
            $("#topicFailureMessage").slideUp("fast").empty();
            updateRecent();
        }else{
            $("#topicFailureMessage").empty().append(results).slideDown("fast");
            $("#topicSuccessMessage").slideUp("fast").empty();
        }
    });
}

function loadTopicXML(){
    $.ajax({
        type: "POST",
        url: "loadTopicXML.php",
        data: {filename: currentFilename, category: currentCategory[0]}
    }).done(function(results){
        $("#editTopicContent").val(results);
    });
}

function editTopic(){
    $.ajax({
        type: "POST",
        url: "editTopic.php",
        data: {filename: currentFilename, category: currentCategory[0], xml: $("#editTopicContent").val()}
    }).done(function(results){
        if (results === "success"){
            $("#topicSuccessMessage").empty().append("Topic has been edited").slideDown("fase");
            $("#topicFailureMessage").slideUp("fast").empty();
            $('#editModal').modal('hide');
            $("#chat-box").empty();
            showChat(true);
            updateRecent();
        }else{
            $("#editErrorMessage").empty().append(results).slideDown("fast");
        }
    });
}

function loadPrevious(start){
    $(".btn-load-previous").fadeOut('fast', function(){
        $(this).remove();
        showChat(true, start);
    });
}

$(function(){
    displayHeader("../../",4);
    displayFooter("../../");

    // Dynamically set the width of the categories 
    var resizeCategories = function(){
        var newBoxWidth = $("#category-container").width()/4;
        var boxTotalWidth = $(".home-category").outerWidth(true);
        var boxWidth = $(".home-category").width();
        var padding = boxTotalWidth - boxWidth;
        $(".home-category").width(newBoxWidth-padding-1);   // -1 for round-off error
    };
    resizeCategories();
    $(window).resize(resizeCategories);

    // Show recent topics on page load
    updateRecent();
    
    $(".home-category").click(transitionToList);

    // Clear topic form on exit
    $('#topicModal').on('hidden',function(){
        $("#modalErrorMessage").empty().hide();
        $("#topicTitle").val('');
        $("#topicContent").val('');
    });
    $('#editModal').on('hidden',function(){
        $("#editErrorMessage").empty().hide();
    });

    // Load more topics when scroll is near bottom
    $(document).scroll(function(){
        if ($(window).scrollTop() >= ($(document).height() - $(window).height())*0.7){
            if (currentPage === 2){
                retrieveTopics();
            }
        }
    });
});