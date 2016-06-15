<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>

<title>Test</title>
<link rel="stylesheet" href="firstStyle.css" type="text/css">
<meta charset="utf-8" />
</head>

<body>
<?php echo strftime('%X');  ?> /*displays time*/
/* <?= strftime('%X'); ?>    this is a shortcut to that above */
<?php
	$currTime = localtime(time(), true);            /*functions*/
	if ($currTime['tm_hour'] <10):
?>
	echo "<strong>Go back to bed.</strong>\n";
<?php
endif;
?>
/* '1'+'2'=3
   '1'.'2'='12'
*/

/*  <?= htmlspecialchars('food & drinks') ?>	*/

</body>
</html>