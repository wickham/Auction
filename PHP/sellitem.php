<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
  <head>
    <title>Sell!</title>
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
<h1>Listing/Edit Item</h1>
<h6>If this is not correct, please <a href="index.php">logout </a>before proceeding or go <a href="sell.php">back</a></h6>
<form class="register" action="success.php" method="post" enctype="multipart/form-data">
Title: <input class="size" type="text" name="item_title" />
<br/>
Starting Price: <input class="size" type="number" name="item_price" />
<br/>
Category: <select class="size" name="item_category">
<option value="empty"> </option>
<option value="vehicle">Vehicles</option>
<option value="electronics">Electronics</option>
<option value="other">Other Options</option>
</select>
<br/>
Description: <textarea rows="3" cols="23" name="item_description"></textarea>
<br/>
Condition: <select class="size" name="item_condition">
<option value="excellent">Excellent</option>
<option value="good">Good</option>
<option value="okay">Okay</option>
<option value="damaged">Damaged</option>
<option value="asis">As Is</option>
</select>
<br/>
Posting Length (in hours): <input class="size" type="text" name="item_time" />
<br/>
Picture -> <input class="size" type="file" name="photo" accept="image/jpeg" />
<br/>
<br/>
<button class="button">POST</button>
</form>



</div>
</body>
</html>