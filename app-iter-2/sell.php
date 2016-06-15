<?php
session_start();
if ($_SESSION['user'] == "")
{
    $_SESSION['login_message'] = htmlspecialchars("Please log in again.");
    header("Location: index.php"); 
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>

<title>User Home</title>
<link rel="stylesheet" href="firstStyle.css" type="text/css" />
<meta charset="utf-8" />
</head>

<body>
<div class="top">
<nav>
Signed in as <?=htmlspecialchars($_SESSION['username'])?><br/>
<span class="account">
<a href="sell.php">My Account</a>
<br/>
<a href="logout.php">Logout</a>
</span>
<form>
<span class="search">
<input type="text" placeholder="Search" name="search" />
</span>
</form>
</nav>
</div>
<div>
<h1>Welcome Back <?=htmlspecialchars($_SESSION['username'])?>!</h1>
<a href="new.php">List Item</a>
<br/><br/>
<a href="buy.php">Buy Items</a>
<br/><br/>
<a href="selling.php">View Your Listings</a>
<br/>
<br/>
<a href="won.php">Your Won Items</a>
<br/>
<br/>





</div>
</body>
</html>