<div class="welcome">
    Welcome, <?php echo $_SESSION['account']['firstName'] . " " . $_SESSION['account']['lastName'] . " (" . $_SESSION['account']['email'] . ")"; ?>
    <span class="user-option"><a href="http://acm.uconn.edu/php/members/logout.php">Log Out</a></span>
    <span class="user-option"><a href="#currentConfigModal" data-toggle="modal">Edit Profile</a></span>
</div>

<div id="currentConfigModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="configModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
        <h3>Edit Profile</h3>
    </div>
    <div class="modal-body">
        <form class="form-horizontal" id="currentConfigForm">
            <input type="hidden" name="oldEmail" value=<?php echo $_SESSION['account']['email']; ?> />
            <div class="control-group">
                <label class="control-label" for="email">Email:</label>
                <div class="controls">
                    <input type="text" name="email" value=<?php echo $_SESSION['account']['email']; ?> />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="pass">Password:</label>
                <div class="controls">
                    <input type="password" name="pass" onclick="document.getElementById('pass').select()" onblur="passwordBlurCheck()" value="XXXXXXXXXXXXXXXXXXXX" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="firstName">First Name:</label>
                <div class="controls">
                    <input type="text" name="firstName" value=<?php echo $_SESSION['account']['firstName']; ?> />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="lastName">Last Name:</label>
                <div class="controls">
                    <input type="text" name="lastName" value=<?php echo $_SESSION['account']['lastName']; ?> />
                </div>
            </div>
            <div class="control-group" style="display: none;">
                <label class="control-label" for="admin">Admin:</label>
                <div class="controls">
                    <input type="checkbox" name="admin" value="true" checked />
                </div>
            </div>
            <label id="currentFailureMessage" style="color: red; font-weight: bold;"/>
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button class="btn btn-primary" onclick="trySubmitConfigForm('currentConfigForm', 'currentFailureMessage')">Submit</button>
    </div>
</div>