<?php
require '/u/z/users/cs105/wickham/PHP/openZdatabase.php';
$categoriesQuery = $database->prepare('
    SELECT
        ITEM_CATEGORY_ID,
        NAME
        FROM ITEM_CATEGORY;
    ');
$categoriesQuery->execute();
$categories = $categoriesQuery->fetchAll();
$categoriesQuery->closeCursor();

$openAuctionQuery = $database->prepare('
    SELECT
        AUCTION.STATUS,
        CONCAT(PERSON.FORENAME, \' \', PERSON.SURNAME) AS SELLER,
        AUCTION.OPEN_TIME,
        AUCTION.CLOSE_TIME,
        ITEM_CATEGORY.NAME AS ITEM_CATEGORY,
		AUCTION.ITEM_DESCRIPTION,
		AUCTION.ITEM_PHOTO,
        AUCTION.ITEM_CAPTION
        FROM AUCTION
            JOIN ITEM_CATEGORY ON AUCTION.ITEM_CATEGORY = ITEM_CATEGORY.ITEM_CATEGORY_ID
            JOIN PERSON ON AUCTION.SELLER = PERSON.PERSON_ID
        WHERE AUCTION.AUCTION_ID = :auctionId;
    ');
$thisAuctionId = $_REQUEST['id'];
$openAuctionQuery->bindValue(':auctionId', $thisAuctionId, PDO::PARAM_INT);
$openAuctionQuery->execute();
$thisAuction = $openAuctionQuery->fetch();
$openAuctionQuery->closeCursor();
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
<h2>Posting a new item:</h2>
<p><a href="sell.php">Back</a>
</div>
<br/>
<div>

<? /*<form action="newItem.php" method="post" enctype="multipart/form-data">
<dl>

<dt><h3>Auction: #<?=htmlspecialchars($thisAuctionId)?></h3></dt>
<dd></dd>
<br/>
<dt>Title:</dt>
<dd><input type="text" name="caption" required maxlength="78" size="30" /></dd>
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

*/ ?>
<form action="bid_success.php" method="get">
<input type="text" name="name1" value="value1" />
<input type="hidden" name="name2" value="value2" />
<select name="name3">
<option value="1">option1</option>
<option value="2" selected="selected">option2</option>
<option value="3">option3</option>
</select>
<button type="submit">Submit</button>
</form>

</div>
</body>
</html>
