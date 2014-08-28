<?php
session_start();
ob_start();
error_reporting(0);
@ini_set('display_errors', 0);
?>
<!DOCTYPE html>
<html>
<head>
<title>RSS Reader Challenge</title>
<meta charset="utf-8">
<meta name="description" content="Login to your twitter account to view your tweets,followers">
<meta name="author" content="Mouyse">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
<!-- Optional theme -->
<link rel="stylesheet" href="bootstrap/css/bootstrap-theme.min.css">
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="bootstrap/js/bootstrap.min.js"></script>

<!-- Custom CSS -->
<link rel="stylesheet" href="css/styles.css">
<link rel="stylesheet" href="css/normalize.css">

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://code.jquery.com/ui/1.11.1/jquery-ui.js"></script>


</head>

<body>

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container">
	<div class="navbar-header">
	  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
		<span class="sr-only">Toggle navigation</span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	  </button>
	  <a class="navbar-brand" href="#">RSS Reader</a>
	</div>
	
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$("#addfeed").click(function(){
		var siteUrl=$("#siteUrl").val();
		$.ajax({
			url: "http://shahinfosolutions.com/EW/RSSReader/process.php", 				//This is the page where you will handle your SQL insert
			data: "siteUrl="+siteUrl,
			type: "POST",
			crossDomain: true,
			async: false,
		   	success: function(msg){
			   	if(msg==null || msg=='')
				   	alert('No RSS Feed found!');
			   	else
		   			$("#addedfeeds").append(" <a href='#' class='list-group-item' onClick='loadRSSFeeds(\""+msg+"\")'>"+msg+"</a>");
	   			$("#siteUrl").val("");
		   	}
		});				
	});	
});
function loadRSSFeeds(feedUrl){
	//alert(feedUrl);
	$.ajax({
		url: "http://shahinfosolutions.com/EW/RSSReader/process.php", 				//This is the page where you will handle your SQL insert
		data: "feedUrl="+feedUrl,
		type: "POST",
		crossDomain: true,
		async: false,
	   	success: function(msg){
		   	//alert(msg);
		   	$("#rightHalf").html("");
			$("#rightHalf").append('<div id="myCarousel" class="carousel slide"><ol class="carousel-indicators" id="myCarouselOL"></ol><div class="carousel-inner" id="mainContent" style="height:120px;"></div><a class="carousel-control left" href="#myCarousel" data-slide="prev"><span class="icon-prev"></span></a><a class="carousel-control right" href="#myCarousel" data-slide="next"><span class="icon-next"></span></a></div>');
			var counter=0;
			$.each(JSON.parse(msg), function(idx, obj) {
				alert(obj['description']);
				if(counter>0){
					if(counter==1){				
						$("#myCarouselOL").append('<li data-target="#myCarousel" data-slide-to="'+counter+'" class="active"></li>');
						$("#mainContent").append('<div class="item active"><table  style="margin-left:10%;margin-top:3%;margin-right:10%;"><tr><td><img src="'+obj['imageUrl']+'" alt=""></td><td><table style="margin-left:2%;"><tr><td><h4>'+obj['title']+'</h4></td></tr><tr><td><p>'+obj['description'].substr(0,149)+'</p></td></tr></table></td></tr></table></div>');
					}
					else{
						$("#myCarouselOL").append('<li data-target="#myCarousel" data-slide-to="'+counter+'"></li>');
						$("#mainContent").append('<div class="item "><table  style="margin-left:10%;margin-top:3%;margin-right:10%;"><tr><td><img src="'+obj['imageUrl']+'" alt="" ></td><td><table style="margin-left:2%;"><tr><td><h4>'+obj['title']+'</h4></td></tr><tr><td><p>'+obj['description'].substr(0,149)+'</p></td></tr></table></td></tr></table></div>');
					}				
				}				
				counter++;
			});
	   		//$("#rssOutput").html(msg);
	   	}
	});		
}
</script>
<div class="container">
	<div class="leftHalf">
		<p><h3><span class="label label-primary">Add Feed URL</span></h3></p>
		<br />
		<div class="input-group">
		  <span class="input-group-addon">@</span>
		  <input type="text" class="form-control" placeholder="Add Feed URL" id="siteUrl" name="siteUrl">
		</div>
		<br />
		<button type="button" class="btn btn-default btn-lg" id="addfeed" name="addfeed">
		  <span class="glyphicon glyphicon-plus"></span> Submit
		</button>
		
		<br />
		<p><h3><span class="label label-info">Added Feeds</span></h3></p>
		<br />
		<div class="list-group" id="addedfeeds">
		 
		</div>
	</div>
	<div class="rightHalf" id="rightHalf">
	    <div id="myCarousel" class="carousel slide">
                <ol class="carousel-indicators" id="myCarouselOL">
                  <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                  <li class="" data-target="#myCarousel" data-slide-to="1"></li>
                  <li class="" data-target="#myCarousel" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner" id="mainContent">
                  <div class="item active">
                    <img src="http://getbootstrap.com/2.3.2/assets/img/bootstrap-mdo-sfmoma-01.jpg" alt="">
                    <div class="carousel-caption">
                      <h4>First Thumbnail label</h4>
                      <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                    </div>
                  </div>
                  <div class="item">
                    <img title="" src="http://getbootstrap.com/2.3.2/assets/img/bootstrap-mdo-sfmoma-02.jpg" alt="">
                    <div class="carousel-caption">
                      <h4>Second Thumbnail label</h4>
                      <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                    </div>
                  </div>
                  <div class="item">
                    <img title="" src="http://getbootstrap.com/2.3.2/assets/img/bootstrap-mdo-sfmoma-03.jpg" alt="">
                    <div class="carousel-caption">
                      <h4>Third Thumbnail label</h4>
                      <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                    </div>
                  </div>
                </div>
                <a class="carousel-control left" href="#myCarousel" data-slide="prev"><span class="icon-prev"></span></a>
                <a class="carousel-control right" href="#myCarousel" data-slide="next"><span class="icon-next"></span></a>
     </div>
	</div>
</div>
<script src="bootstrap/js/bootstrap.js"></script>
</body>
</html>