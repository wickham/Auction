<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>

<title>Notifying Seller</title>
<link rel="stylesheet" href="firstStyle.css" type="text/css" />
<meta charset="utf-8" />
</head>

<body>
<div class="top">
<nav>
Signed in as, (User)<br/>
<span class="account">
<a href="sell.php">My Account</a>
<br/>
<a href="index.php">Logout</a>
</span>
<form>
<span class="search">
<input type="text" placeholder="Search" name="search" />
</span>
</form>
</nav>
</div>
<div class="red">
<a href="won.php">Back</a>
</div>

<div>
<h1>(User), these are your current winnings:</h1>
<h3>Payment information for (Item_1)</h3>
<br/>
Confirm Information
<h6>You will receive conformation e-mail with instructions on how to pay</h6>

<form action="sent.php" method="post">
<dl>
<dt>Item Won:</dt> 
<dd>ITEM_1 (Item Name)</dd><br/>
<dt>Name:</dt>
<dd><input type="text" name="name" /><br/></dd>
<dt>E-mail:</dt><dd><input type="text" name="mail" value="your email" /></dd>
<dt>Comments:</dt><dd><input type="text" name="comment" value="your comment" size="50" /></dd><br/>
<button class="button">Submit</button>
<input class="button" type="reset" value="Reset" />
</form>



</div>
</body>
</html>