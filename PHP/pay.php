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
<a href="sell.html">My Account</a>
<br/>
<a href="index.html">Logout</a>
</span>
<form>
<span class="search">
<input type="text" placeholder="Search" name="search" />
</span>
</form>
</nav>
</div>
<div>
<h1>(User), these are your current winnings:</h1>
<h3>Payment information for (Item_1)</h3>
<br/>
Confirm Information
<h6>You will receive conformation e-mail with instructions on how to pay</h6>

<form action="sent.html" method="get">
Name:<br/>
<input type="text" name="name" value="your name" /><br/>
Item Won:<br/>
<input name="mail" value="ITEM_1 (Item Name)" /><br/>
E-mail:<br/>
<input type="text" name="mail" value="your email" /><br/>
Mailing Address:<br/>
<input type="text" name="address" value="your address" /><br/>
Comment:<br/>
<input type="text" name="comment" value="your comment" size="50" /><br/><br/>
</form>
<form action="sent.html" method="get"><button class="button">Submit</button>
<input class="button" type="reset" value="Reset" />
</form>

<br/>
<br/>
<a href="won.html">Back</a>
<br/>



</div>
</body>
</html>