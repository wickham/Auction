<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>

<title>Selling Current Items</title>
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

<h1>(User), these are your current listings:</h1>

<a href="sell.php">Back</a>
</div>
<div>
<br/>
<h3>Listing 1 (Title)</h3>
<ul>
<li>Picture1</li>
<li>Curent Bid</li>
<li>Time Remaining</li>
<li>Discription</li>
<li>Category</li>
</ul>

<h4>|  <a href="sellitem.php">   Edit</a>   |
<a href="close.php">Cancel</a> |   
</h4><br/>

<h3>Listing 2 SOLD!(Title)</h3>


<ul>
<li>Picture2</li>
<li>SOLD!</li>
<li>SOLD!</li>
<li>Discription</li>
<li>Category</li>
</ul>
<h4>|  <a href="sellitem.php">   Edit</a>   |   
<a href="close.php">Cancel</a> |</h4><br/>
<br/>
<br/>

<a href="buy.php">Buy Items</a>
<br/>
<a href="sellitem.php">Sell Item</a>
<br/><br/>



</div>
</body>
</html>