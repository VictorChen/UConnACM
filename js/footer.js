function displayFooter(path){
	$("<div>\
		<a href='http://uconn.edu/'><img src='"+path+"img/uconn.png' /></a>\
		<a href='http://www.acm.org/'><img src='"+path+"img/acm_logo.png' /></a>\
		<span class='footer-info'>\
			&#169; UConn ACM 2013\
			<span class='separate-bar'> | </span>\
			Developed by <a id='vchen' href='http://www.linkedin.com/in/vichen' rel='popover' title='Victor Chen' data-content='View his linkedIn profile'>Victor Chen</a>\
		</span>\
	</div>").prependTo("footer");
	
	var lastUpdated = "February 20, 2013";
	$("footer .footer-info").append("<span class='separate-bar'> |</span> Last updated " + lastUpdated);

	$("#vchen").popover({
		placement: "top"
	});
}