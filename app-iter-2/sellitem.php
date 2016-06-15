<?php
session_start();
require('/u/z/users/cs105/wickham/PHP/openZdatabase.php');
$openAuctionQuery = $database->prepare('
    SELECT
        A.AUCTION_ID,
        A.STATUS,
        A.SELLER AS ITEM_OWNER,
        CONCAT(P.FORENAME, \' \', P.SURNAME) AS SELLER,
        A.OPEN_TIME,
        A.CLOSE_TIME,
        CONCAT(
            FLOOR(HOUR(TIMEDIFF(A.CLOSE_TIME, NOW())) / 24), " days ",
            MOD(HOUR(TIMEDIFF(A.CLOSE_TIME, NOW())), 24), " hours ",
            MINUTE(TIMEDIFF(A.CLOSE_TIME, NOW())), " minutes") AS TIME_LEFT,
        C.NAME AS ITEM_CATEGORY,
        A.ITEM_CAPTION,
        A.ITEM_DESCRIPTION,
        A.ITEM_PHOTO
        FROM AUCTION A
            JOIN ITEM_CATEGORY C ON A.ITEM_CATEGORY = C.ITEM_CATEGORY_ID
            JOIN PERSON P ON A.SELLER = P.PERSON_ID
        WHERE A.AUCTION_ID = :auctionId;
    ');
$thisAuctionId = $_POST['id'];
$openAuctionQuery->bindValue(':auctionId', $thisAuctionId, PDO::PARAM_INT);
$openAuctionQuery->execute();
$thisAuction = $openAuctionQuery->fetch();
$openAuctionQuery->closeCursor();
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
    <title>Lising - <?=htmlspecialchars($thisAuction['ITEM_CAPTION'])?></title>
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
<a href="index.php">Logout</a>
</span>
<form>
<span class="search">
<input type="text" placeholder="Search" name="search" />
</span>
</form>
</nav>
</div>


<div>

<h2>You are editing: <?=htmlspecialchars($thisAuctionId)?></h2>

<h6>If this is not correct, please <a href="index.php">logout </a>before proceeding or go <a href="selling.php">back</a></h6>
</div>

<br/>

<div>
<form action="save.php" method="post" enctype="multipart/form-data">
<dl>
<dt>Title:</dt>
<dd><input type="text" name="item_name" value="<?=htmlspecialchars($thisAuction['ITEM_CAPTION'])?>" /></dd>
<br/>
<dt>Current Bid:</dt>
<dd>$<?=htmlspecialchars($Auction['AMOUNT'])?></dd>
<br/>
<dt>Category:</dt>
<dd> <select name="category" required="required">
<?php
foreach ($categories as $currCat):
?>
<option value="<?=htmlspecialchars($currCat['ITEM_CATEGORY_ID'])?>"<?=htmlspecialchars($currCat['ITEM_CATEGORY_ID'])==htmlspecialchars($thisAuction['ITEM_CATEGORY'])? ' selected':''?>> <?=htmlspecialchars($currCat['NAME'])?></option>
<?php
endforeach;
?>
</select></dd>
<br/>
<dt>Description:</dt>
<dd><textarea name="description" required maxlength="100" cols="23" rows="7"><?=htmlspecialchars($thisAuction['ITEM_DESCRIPTION'])?></textarea></dd>
<br/>
<dt>Open time:</dt>
<dd><?=htmlspecialchars($thisAuction['OPEN_TIME'])?></dd>
<br/>
<dt>Close time:</dt>
<dd><input name="closeTime" required value="<?=htmlspecialchars($thisAuction['CLOSE_TIME'])?>" /></dd>
<br/>
<dt>New photo:</dt>
<dd><input type="file" name="photo" required accept="image/jpeg" /></dd>
<br/>
<br/>
<input type="hidden" name="user" value="<?=htmlspecialchars($thisAuctionId)?>"/>
<button type="submit">Save</button>
</dl>
</form>



</div>
</body>
</html>