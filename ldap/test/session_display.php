<?php
session_start();
?>

<html>
<head>
<title>Session Demo</title>
</head>
<body>
<center>
<h2>Display Session</h2>

<?php

echo '<table border="1" cellspacing="0" cellpadding="8">';
echo '<tr><td>Session ID</td><td>',session_id(),'</td></tr>';
echo '<tr><td>Script Name</td><td>',$SCRIPT_NAME,'</td></tr>';
foreach (array_keys($_SESSION) as $k)
{
   echo '<tr><td>',$k,'</td><td>',$_SESSION[$k],'</td></tr>';
}
echo '</table>';

//echo '<p />';
//echo '<table border="1" cellspacing="0" cellpadding="8">';
//foreach (array_keys($_SERVER) as $k)
//{
//   echo '<tr><td>',$k,'</td><td>',$_SERVER[$k],'</td></tr>';
//}
//echo '</table>';

?>

<p />
</center>
</body>
</html>