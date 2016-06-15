<!DOCTYPE html>
<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>Buy, Sell, Manage!</title>
<meta charset="utf-8" />
<link href="firstStyle.css" type="text/css" rel="stylesheet" />

</head>

<body>
<div class="login">
<form class="fields" name="user" action="signed.php" method="get">
Username:
<input type="text" name="username" size="20" />
Password:
<input type="password" name="password" size="20" />

<input class="button" type="submit" value="Login" />
</form>
<form class="fields" action="register.php" method="get"> 
<input class="button" type="submit" value="Register" />
</form>
</div>
<div>
<h1>Acme Auction: Buy, Sell, Manage!</h1>
<a href="login.php">Returning? Log in</a>
<br/>
<br/>
<a href="login.php">Buy Items</a>
<br/><br/>
<a href="login.php">Sell Item</a>
</div>
</body>
</html>