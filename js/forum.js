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
    retrieveTopics();
}

function retrieveTopics(){
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: "retrieveTopics.php",
        data: {category: currentCategory[0], startFrom: topicStartLoad}
    }).done(function(results) {
        $("#category-list").append(results.topics).show("fast");
        $('.post-author').click(function(event){
            event.stopImmediatePropagation();   // Stop chat from showing when user is clicked
            $("#configModal").modal('show');    // Show the user's profile
        });
        $(".post-delete-btn").click(function(event){
            event.stopImmediatePropagation();
            currentFilename = $(this).parent().find(".post-filename").text();
            $("#deleteModal").modal('show');
        });
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
    $("#chat-title").hide("fast");
    $("#chat-content").hide("fast");
    $("#chat-box").hide("fast").empty();
    $("#chat-area").hide("fast");
    $("#chat-post-btn").hide("fast");
    $("#category-list").hide().empty();
    $("#backbtn1").show("fast");
    $("#create-post").show("fast");
    $("#category-title").text(currentCategory[1]).show("fast");
    currentPage = 2;
    topicStartLoad = 0;
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
    currentPage = 3;
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
            topicStartLoad = 0;
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
    currentPage = 3;
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

    $(document).scroll(function(){
        // Prevent calling ajax multiple times
        if (processing)
            return false;

        // Load more topics when scroll is near bottom
        if ($(window).scrollTop() >= ($(document).height() - $(window).height())*0.9){
            if (currentPage === 2){
                processing = true;
                retrieveTopics(topicStartLoad);
            }
        }
    });
});