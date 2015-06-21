<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

<link href="calendar.css" type="text/css" rel="stylesheet" />
<link href="social.css" type="text/css" rel="stylesheet" />




</head>
<body style="background:#aaa;padding-bottom: 70px;">
<?php
/*
<body oncontextmenu="return false">
<script language="javascript">
document.onmousedown=disableclick;
status="Right Click Disabled";
Function disableclick(event)
{
  if(event.button==2)
   {
     alert(status);
     return false;
   }
}

</script>
*/
?>
<div id="wrapper" >

		<div class="jumbotronmod">
			<center>&#2835;&#2849;&#2879;&#2822; &#2837;&#2887;&#2866;&#2887;&#2851;&#2893;&#2849;&#2864;</center>
		</div>
	<div class="container-fluid">

<?php
	include 'calendar.php';

	$calendar = new Calendar();

	echo $calendar->show();

?>


	</div>

<!-- Footer -->
<nav class="navbar navbar-inverse navbar-fixed-bottom">
  <div class="container-fluid">

    <a class="navbar-brand navbar-right" href="#"><span class="glyphicon glyphicon-copyright-mark"></span>  Oriya Calendar Team</a>
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>


    <div class="collapse navbar-collapse" id="myNavbar">
        <div class="social-popout"><a href="#"><img src="images/facebook.png" alt="Facebook" ></a></div>
        <div class="social-popout"><a href="#"><img src="images/twitter.png" alt="Twitter" ></a></div> 
        <div class="social-popout"><a href="#"><img src="images/googleplus.png" alt="Google Plus" ></a></div>
    </div>

  </div>
</nav>
    </div>
  </div>
</nav>


</div>
</body>
</html>
