<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<!-- 
<link href="calendar.css" type="text/css" rel="stylesheet" />
-->
</head>
<body>
<div class="container"> 
	<div class="jumbotron">
    	<h2><center>&#2835;&#2849;&#2879;&#2822; &#2837;&#2887;&#2866;&#2887;&#2851;&#2864;</center></h2>
    </div>
<?php
include 'calendar.php';
 
$calendar = new Calendar();
 
echo $calendar->show();

?>
</div> 
</body>
</html>       
