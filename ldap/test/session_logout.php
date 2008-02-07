<?php
session_start();
session_destroy();
?>

<html>
<head>
<title>Session Demo</title>
</head>
<body>
<center>

<h2>
You have sucessfully logged out
<p />
After a brief pause you will return to the main page
</h2>

<META HTTP-EQUIV="refresh" content="2; URL=../../index.php">

</center>
</body>
</html>
