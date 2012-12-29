// Account Configuration Functions
function passwordBlurCheck() {
    elem = document.getElementById('pass');
    if (elem.value == '' && document.getElementById('oldEmail').value != '') elem.value = "XXXXXXXXXXXXXXXXXXXX";
}

function trySubmitConfigForm(formID, msgID) {
    // Do all validation server side since checking for email matches needs to be done there anyway
    $.post('userConfig.php', $('#' + formID).serialize(), function(data) {
        if (data == 'SUCCESS') {
            document.location.reload(true);
        } else if (data == '') {
            document.getElementById(msgID).innerHTML = "Server Side Error";
        } else {
            document.getElementById(msgID).innerHTML = data;
        }
    });
}