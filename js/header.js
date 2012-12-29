function displayHeader(path, pageNumber){
	$("<div class='navbar'>\
		<div class='navbar-inner'>\
			<div class='container-fluid'>\
				<a class='brand' href='http://acm.uconn.edu'><img src='"+path+"img/logo.png' /><span class='brand-name'>UCONN ACM</span></a>\
				<ul class='nav pull-right'>\
					<li class='category'>\
						<a href='http://acm.uconn.edu'>\
							<i class='icon-home icon-white'></i>\
							Home\
						</a>\
					</li>\
					<li class='divider-vertical'></li>\
					<li class='dropdown category'>\
						<a href='#' class='dropdown-toggle' data-toggle='dropdown'>\
							<i class='icon-calendar icon-white'></i>\
							Events \
							<b class='caret'></b>\
						</a>\
						<ul class='dropdown-menu'>\
							<li><a href='http://acm.uconn.edu/events/spring_2013.html'>Spring 2013</a></li>\
							<li><a href='http://acm.uconn.edu/events/fall_2012.html'>Fall 2012</a></li>\
							<li><a href='http://acm.uconn.edu/events/spring_2012.html'>Spring 2012</a></li>\
						</ul>\
					</li>\
					<li class='divider-vertical'></li>\
					<li class='dropdown category'>\
						<a href='#' class='dropdown-toggle' data-toggle='dropdown'>\
							<i class='icon-user icon-white'></i>\
							Members \
							<b class='caret'></b>\
						</a>\
						<ul class='dropdown-menu'>\
							<li><a href='http://acm.uconn.edu/members/spring_2013.html'>Spring 2013</a></li>\
							<li><a href='http://acm.uconn.edu/members/fall_2012.html'>Fall 2012</a></li>\
							<li><a href='http://acm.uconn.edu/members/spring_2012.html'>Spring 2012</a></li>\
						</ul>\
					</li>\
					<li class='divider-vertical'></li>\
					<li class='dropdown category'>\
						<a href='#' class='dropdown-toggle' data-toggle='dropdown'>\
							<i class='icon-fire icon-white'></i>\
							Competitions \
							<b class='caret'></b>\
						</a>\
						<ul class='dropdown-menu'>\
							<li><a href='http://acm.uconn.edu/competition/pacman_2013.html'>PacMan 2013</a></li>\
							<li><a href='http://acm.uconn.edu/competition/acm_2012.html'>ACM ICPC 2012</a></li>\
							<li><a href='http://acm.uconn.edu/competition/tron_2012.html'>Tron 2012</a></li>\
						</ul>\
					</li>\
					<li class='divider-vertical'></li>\
					<li class='category'>\
						<a href='http://acm.uconn.edu/php/members/home.php'>\
							<i class=' icon-comment icon-white'></i>\
							Members Area\
						</a>\
					</li>\
				</ul>\
			</div>\
		</div>\
	</div>").prependTo(".content-wrapper");
	$('li.category').eq(pageNumber).addClass("active");
}