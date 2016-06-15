<?php
session_start();
if (!isset($_SESSION['user']))
{
    $_SESSION['login_message'] = htmlspecialchars("You can't be successful without logging in!");
    header('Location: index.php');
}
$message = $_REQUEST['message'];
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>

<title>Redirecting</title>
<link rel="stylesheet" href="firstStyle.css" type="text/css" />
<meta charset="utf-8" />
</head>

<body>
<div class="red">
<?=htmlspecialchars($message)?><br/><br/>
 <a href="buy.php">Click here if not redirected.</a>
</div>
</body>
</html>