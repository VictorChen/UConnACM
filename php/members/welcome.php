<div class="welcome">
    Welcome, <?php echo $_SESSION['account']['firstName'] . " " . $_SESSION['account']['lastName'] . " (" . $_SESSION['account']['email'] . ")"; ?>
    <span class="user-option"><a href="http://acm.uconn.edu/php/members/logout.php">Log Out</a></span>
    <span class="user-option"><a href="#configModal" onclick="loadAccountData('<?php echo hashEmail($_SESSION['account']['email']); ?>')" data-toggle="modal">Edit Profile</a></span>
</div>

<?php require_once('configDialog.php'); ?>
