<!DOCTYPE html>
<html lang="en">
	<head>
		<title>UCONN ACM Sign Up</title>
		<meta charset="UTF-8">
		<meta name="robots" content="noindex">
		<link rel="stylesheet" href="../css/style.css" type="text/css" />
		<link rel="stylesheet" href="../css/bootstrap.css" type="text/css" />
		<link rel="stylesheet" href="../css/confirmation.css" type="text/css" />
		<link rel="shortcut icon" href="../img/icon.ico">
		<script src="../js/lib/jquery.js"></script>
		<script src="../js/lib/bootstrap.min.js"></script>
		<script src="../js/header.js"></script>
		<script src="../js/footer.js"></script>
		<script src="../js/googleanalytics.js"></script>
		<script>
			$(document).ready(function(){
				displayHeader("../");
				displayFooter("../");
			});
		</script>
	</head>

	<body>
		<div class="page-wrapper">
			<div class="content-wrapper">
				<div id="page-header" class="row-fluid">
					<div class="span12 hero-unit black">
						<h1><?php echo $_GET["title"] ?></h1>
					</div>
				</div>
				
				<?php
					if ($_GET["type"] == "success"){
						echo "<div class='content-padded'><div class='alert alert-success'>" . $_GET["msg"] . "</div><img src='../img/dog.jpg' /></div>";
					}else{
						$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
						echo "<div class='content-padded'><div class='alert alert-error'>" . $_GET["msg"] . "</div><img src='../img/dog2.jpg' /><br><a class='btn btn-primary' href='$url'>Go Back</a></div>";
					}
				?>
			</div>
			<footer></footer>
		</div>
	</body>
</html>