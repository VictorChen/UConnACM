<?php
	$submitted = isset($_COOKIE["meeting"]);
	if (!$submitted){
		setcookie("meeting", true, time()+864000);
		$submitted = false;
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>UCONN ACM Poll</title>
		<meta charset="UTF-8">
		<meta name="robots" content="noindex">
		<link rel="stylesheet" href="../css/style.css" type="text/css" />
		<link rel="stylesheet" href="../css/bootstrap.css" type="text/css" />
		<link rel="stylesheet" href="../css/poll.css" type="text/css" />
		<link rel="shortcut icon" href="../img/icon.ico">
		<script src="../js/jquery.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script src="../js/footer.js"></script>
		<script src="../js/googleanalytics.js"></script>
	</head>

	<body>
		<div class="page-wrapper">
			<div class="content-wrapper">
				<!-- Menu -->
				<div class="navbar">
					<div class="navbar-inner">
						<div class="container-fluid">
							<a class="brand" href="http://acm.uconn.edu/"><img src="../img/logo.png" /><span class="brand-name">UCONN ACM</span></a>
							<ul class="nav pull-right">
								<li>
									<a href="http://acm.uconn.edu/">
										<i class="icon-home icon-white"></i>
										Home
									</a>
								</li>
								<li class="divider-vertical"></li>
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">
										<i class="icon-calendar icon-white"></i>
										Events 
										<b class="caret"></b>
									</a>
									<ul class="dropdown-menu">
										<li><a href="http://acm.uconn.edu/events/fall_2012.html">Fall 2012</a></li>
										<li><a href="http://acm.uconn.edu/events/spring_2012.html">Spring 2012</a></li>
									</ul>
								</li>
								<li class="divider-vertical"></li>
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">
										<i class="icon-user icon-white"></i>
										Members 
										<b class="caret"></b>
									</a>
									<ul class="dropdown-menu">
										<li><a href="http://acm.uconn.edu/members/fall_2012.html">Fall 2012</a></li>
										<li><a href="http://acm.uconn.edu/members/spring_2012.html">Spring 2012</a></li>
									</ul>
								</li>
								<li class="divider-vertical"></li>
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">
										<i class="icon-fire icon-white"></i>
										Competition 
										<b class="caret"></b>
									</a>
									<ul class="dropdown-menu">
										<li><a href="http://acm.uconn.edu/competition/tron.html">Tron</a></li>
										<li><a href="http://acm.uconn.edu/competition/acm_2012.html">2012 ACM ICPC NENA Regional Finals</a></li>
									</ul>
								</li>
							</ul>
						</div>
					</div>
				</div>

				<div id="page-header" class="row-fluid">
					<div class="span12 hero-unit black">
						<h1>ACM Meeting Time Poll</h1>
						<p></p>
					</div>
				</div>
				
				<div class="content-padded">
					<?php
						$monday = $_POST['monday'];
						$tuesday = $_POST['tuesday'];
						$wednesday = $_POST['wednesday'];
						$thursday = $_POST['thursday'];
						$friday = $_POST['friday'];
						
						if ($submitted){
							echo "You have already submitted your preferred times.";
						}
						
						if (!empty($monday) || !empty($tuesday) || !empty($wednesday) || !empty($thursday) || !empty($friday)){
							if (!$submitted){
								$myFile = "/u/acm/storage/database/meeting_times.csv";
								$fh = fopen($myFile, 'a+') or die("Error: Cannot submit meeting times. Please try again.");

								if(!empty($monday)){
									$line = "monday";
									for($i=0; $i<count($monday); $i++){
										$line .= "," . $monday[$i];
									}
									$line .= "\n";
									fwrite($fh, $line);
								}
								
								if(!empty($tuesday)){
									$line = "tuesday";
									for($i=0; $i<count($tuesday); $i++){
										$line .= "," . $tuesday[$i];
									}
									$line .= "\n";
									fwrite($fh, $line);
								}
								
								if(!empty($wednesday)){
									$line = "wednesday";
									for($i=0; $i<count($wednesday); $i++){
										$line .= "," . $wednesday[$i];
									}
									$line .= "\n";
									fwrite($fh, $line);
								}
								
								if(!empty($thursday)){
									$line = "thursday";
									for($i=0; $i<count($thursday); $i++){
										$line .= "," . $thursday[$i];
									}
									$line .= "\n";
									fwrite($fh, $line);
								}
								
								if(!empty($friday)){
									$line = "friday";
									for($i=0; $i<count($friday); $i++){
										$line .= "," . $friday[$i];
									}
									$line .= "\n";
									fwrite($fh, $line);
								}
								
								echo "You have successfully submitted your preferred times. Thanks!";
								fclose($fh);
							}
						}else{
							echo "No meeting times selected. Please try again.";
						}
					?>
				</div>
			</div>
			<footer>
				<div>
					<a href="http://uconn.edu/"><img src="../img/uconn.png" /></a>
					<a href="http://www.acm.org/"><img src="../img/acm_logo.png" /></a>
					<span class="footer-info">
						&#169; UConn ACM 2012
						<span class="separate-bar"> | </span>
						Developed by <a id="vchen" href="http://www.linkedin.com/in/vichen" rel="popover" title="Victor Chen" data-content="View his linkedIn profile">Victor Chen</a>
					</span>
				</div>
			</footer>
		</div>
	</body>
</html>