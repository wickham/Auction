<?php
session_start();
if (!isset($_SESSION['user']))
{
    $_SESSION['login_message'] = htmlspecialchars("You need to log in before you can list an item!");
    header('Location: index.php');
}
require('/u/z/users/cs105/wickham/PHP/openZdatabase.php');
$categoriesQuery = $database->prepare('
    SELECT
        ITEM_CATEGORY_ID,
        NAME
        FROM ITEM_CATEGORY;
    ');
$categoriesQuery->execute();
$categories = $categoriesQuery->fetchAll();
$categoriesQuery->closeCursor();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
  <head>
    <title>New Posting!</title>
    <link rel="stylesheet" href="firstStyle.css" type="text/css" />
    <meta charset="utf-8" />
  </head>

<body>
<div class="top">
<nav>
Signed in as <?=htmlspecialchars($_SESSION['username'])?><br/>
<span class="account">
<?php
if (isset($_SESSION['user']) && !empty($_SESSION['user'])):
?>

<a href="sell.php">My Account</a>
<br/>
<a href="logout.php">Logout</a>
<?php
endif;
?>
</span>
<form>
<span class="search">
<input type="text" placeholder="Search" name="search" />
</span>
</form>
</nav>
</div>
<div class="red">
<h2>Posting a new item:</h2>
<p><a href="sell.php">Back</a>
</div>
<br/>
<div>

<form action="newItem.php" method="post" enctype="multipart/form-data">
<dl>

<br/>
<dt>Title:</dt>
<dd><input type="text" name="item_name" required maxlength="78" size="30" /></dd>
<dt>Listing Price:</dt>
<dd>$<input type="number" name="itemPrice" required maxlength="78" size="29" placeholder="0.00" /></dd>
<dt>Description:</dt>
<dd><textarea name="description" required maxlength="100" cols="30" rows="4"></textarea></dd>
<dt>Category:</dt>
<dd><select name="category" required="required">
<?php
foreach ($categories as $currCat):
?>
  <option value="<?=htmlspecialchars($currCat['ITEM_CATEGORY_ID'])?>"><?=htmlspecialchars($currCat['NAME'])?>
  </option>
<?php
endforeach;
?>
  </select></dd>
<dt>Photo:</dt>
<dd><input type="file" name="photo" accept="image/jpeg" /></dd>
<dt>Close time:</dt>
<dd><input name="closeTime" required size="19" /></dd>
<dt>format: YYYY:MM:DD HH:MM:SS</dt>
<dt></dt><dd><button type="submit">Save</button></dd>
</dl>
</form>


</div>
</body>
</html>
