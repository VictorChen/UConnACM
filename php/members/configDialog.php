<!-- Modal -->
<div id="configModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="configModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
        <h3 id="configModalLabel">User Configuration</h3>
    </div>
    <div class="modal-body">
        <div class="row-fluid">
            <form class="form-horizontal" id="configForm">
                <div class="span6">
                    <input type="hidden" id="oldEmail" name="oldEmail"/>
                    <div class="control-group">
                        <label class="control-label" for="email">Email:</label>
                        <div class="controls">
                            <input type="text" id="email" name="email"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="pass">Password:</label>
                        <div class="controls">
                            <input type="password" id="pass" name="pass" onclick="document.getElementById('pass').select()" onblur="passwordBlurCheck()" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="firstName">First Name:</label>
                        <div class="controls">
                            <input type="text" id="firstName" name="firstName" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="lastName">Last Name:</label>
                        <div class="controls">
                            <input type="text" id="lastName" name="lastName" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="admin">Admin:</label>
                        <div class="controls">
                            <input type="checkbox" id="admin" name="admin" value="true" />
                        </div>
                    </div>
                </div>
                <div class="span6">
                    <div class="control-group">
                        <label class="control-label" for="major">Major:</label>
                        <div class="controls">
                            <input type="text" id="major" name="major" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="year">Year:</label>
                        <div class="controls">
                            <input type="text" id="year" name="year" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="aboutMe">About Me:</label>
                        <div class="controls">
                            <textarea id="aboutMe" name="aboutMe" rows="7"></textarea>
                        </div>
                    </div>
                </div>
                <label id="failureMessage" style="color: red; font-weight: bold;"></label>
            </form>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button id="configSubmitButton" class="btn btn-primary" onclick="trySubmitConfigForm('configForm', 'failureMessage')">Submit</button>
    </div>
</div>