// Delete account functions
var currentAccountHash = "";
var currentID = "";

function setCurrentAccount(accountHash) {
    currentAccountHash = accountHash;
}

function deleteCurrentAccount() {
    $.get('deleteUser.php?hash=' + currentAccountHash, function() {
        location.reload(true);
    });
}

// Account Configuration Functions
function passwordBlurCheck() {
    elem = $('#pass');
    if (elem.val() == '' && $('#oldEmail').val() != '') elem.val('XXXXXXXXXXXXXXXXXXXX');
}

function trySubmitConfigForm() {
    // Do all validation server side since checking for email matches needs to be done there anyway
    $.post('userConfig.php', $('#configForm :input').serialize(), function(data) {
        if (data == 'SUCCESS') {
            location.reload(true);
        } else if (data == '') {
            $('#failureMessage').html('Server Side Error').slideDown("slow");
        } else {
            $('#failureMessage').html(data).slideDown("slow");
        }
    });
}

function loadAccountData(accountHash, readonly) {
    $('#failureMessage').hide();

    // Get the server request started and do the rest while waiting on it
    $.getJSON('getAccountData.php?hash=' + accountHash, function(data) {
        if (data.success) {
            $('#oldEmail').val(data.email);
            $('#currentEmail').val(data.email);
            $('#email').val(data.email);
            $('#pass').val('XXXXXXXXXXXXXXXXXXXX');
            $('#firstName').val(data.firstName);
            $('#lastName').val(data.lastName);
            $('#admin').prop('checked', data.admin);
            $('#major').val(data.major);
            $('#year').val(data.year);
            $('#aboutMe').val(data.aboutMe);

            // Check if the user's image exists
            currentID = data.id;
            loadImage();
        }
    });
    
    // Default readonly to false
    readonly = typeof readonly !== 'undefined' ? readonly : false;
    setConfigFormReadOnly(readonly);
}

function resetConfigForm() {
    $('#oldEmail').val('');
    $('#email').val('');
    $('#pass').val('');
    $('#firstName').val('');
    $('#lastName').val('');
    $('#admin').prop('checked', false);
    $('#major').val('');
    $('#year').val('');
    $('#aboutMe').val('');
    $('#failureMessage').hide();
}

function setConfigFormReadOnly(readonly) {
    $('#email').prop('readonly', readonly);
    $('#pass').prop('readonly', readonly);
    $('#firstName').prop('readonly', readonly);
    $('#lastName').prop('readonly', readonly);
    $('#admin').prop('readonly', readonly);
    $('#major').prop('readonly', readonly);
    $('#year').prop('readonly', readonly);
    $('#aboutMe').prop('readonly', readonly);
    $('#configSubmitButton').toggle(!readonly);
    
    // Checkbox fix
    if (readonly) $('#admin').click(function() { return false; });
    else $('#admin').click($.noop());
}

function loadImage(){
    var timestamp = new Date().getTime();
    var imageURL = "http://acm.uconn.edu/accountImages/"+currentID+".png?"+timestamp;
    var successCallback = function(){
        $("#profileImage").attr("src", imageURL);
    };
    var failCallback = function(){
        $("#profileImage").attr("src", "http://acm.uconn.edu/accountImages/default.png?"+timestamp);
    };
    checkImage(imageURL, successCallback, failCallback);
}

$(function(){
    $('#profileImageForm').ajaxForm(function(result) { 
        if (result === "success"){
            loadImage();
            $('#failureMessage').slideUp("slow");
        }else{
            $('#failureMessage').html(result).slideDown("slow");
        }
    }); 
});