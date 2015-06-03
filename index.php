<html>
<head>   
<link href="calendar.css" type="text/css" rel="stylesheet" />
</head>
<body>
<center style ="width:80%"> 
<?php
include 'calendar.php';
 
$calendar = new Calendar();
 
echo $calendar->show();

?>
</center> 
</body>
</html>       
