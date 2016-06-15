<?php session_start(); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>

<title>Login or Register</title>
<link rel="stylesheet" href="firstStyle.css" type="text/css" />
<meta charset="utf-8" />
</head>

<body>
<div class="top">
<a href="index.php">Home</a>
</div>

<div class="red">
<h1>Sign-up</h1>
<?php
if ($_SESSION['errorarray']):
?>
            <p class="closed">Please fix the following error(s):</p>
<?php
foreach ($_SESSION['errorarray'] as $error):
?>
            <p class="closed"><?=htmlspecialchars($error)?></p>
<?php
endforeach;
?>

<?php
unset($_SESSION['errorarray']);
endif;
?>
<form class="register" name="register" action="register_go.php" method="post">
First Name:
<input type="text" name="first" />
<br/>
Last Name:
<input type="text" name="last" />
<br/>
E-mail:
<input type="email" name="username" />
<br/>
Password:
<input type="password" name="password" />
<br/>
Confirm Password:
<input type="password" name="password_confirm" />
<br/>
<h6><input type="checkbox" name="TOC"/>By checking this box you AGREE to the <a href="terms.php" target="_blank">terms and condidions</a></h6>

<br/>
<input class="button" type="submit" value="Register" />
</form>

</div>
</body>
</html>