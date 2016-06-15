<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>

<title>Login or Register</title>
<link rel="stylesheet" href="firstStyle.css" type="text/css" />
<meta charset="utf-8" />
</head>

<body>
<div class="top">
<span class="account">
<a href="index.php">Home</a>
</span>
<nav>

<form name="user" action="login_go.php" method="post">
<span class="search">
Username:
<input type="text" name="username" size="20" />
Password:
<input type="password" name="password" size="20" />

<input type="submit" value="Login" class="button" />
</span>
</form>

</nav>
<br/>
<br/>
<form action="register.php" method="post">
<span class="search"> 
<input type="submit" value="Register" class="button" />

</span>
</form>
</div>
<div class="red">
<h1>Login</h1>
<form class="login" name="user" action="login_go.php" method="post">
Username:
<input type="text" name="username" />
<br/>
Password:
<input type="password" name="password" />
<br/>
<input type="submit" value="Login" class="button" />
</form>
</div>
<div class="red">
<h4>No Login?</h4>
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
<h6><input type="checkbox" name="agree" />By checking this box you AGREE to the <a href="terms.php" target="_blank">terms and condidions</a></h6>

<br/>
<input class="button" type="submit" value="Register" />
</form>

</div>
</body>
</html>