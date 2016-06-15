<?php
require '/u/z/users/cs105/wickham/PHP/openZdatabase.php';
?>

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

<h1>(USER), these are your current listings:</h1>

<a href="sell.php">Back</a>
</div>

<br/>
<?php
$myOpenAuctionQuery = $database->prepare('
    SELECT
        A.AUCTION_ID,
        A.OPEN_TIME,
        A.CLOSE_TIME,
        C.NAME AS ITEM_CATEGORY,
        A.ITEM_CAPTION,
        A.ITEM_DESCRIPTION,
        A.ITEM_PHOTO
        FROM AUCTION A
            JOIN ITEM_CATEGORY C ON A.ITEM_CATEGORY = C.ITEM_CATEGORY_ID
            JOIN PERSON P ON A.SELLER = P.PERSON_ID
        WHERE A.STATUS = 1 AND A.SELLER = 1;
    ');
$myOpenAuctionQuery->execute();
foreach ($myOpenAuctionQuery->fetchAll() as $auction):
?>
<div>

<dl>
<img src="auctionPhoto.php?id=<?=htmlspecialchars($auction['AUCTION_ID'])?>" class="icons" />

<dt><h2><?=htmlspecialchars($auction['ITEM_CAPTION'])?></h2></dt><dd></dd>
<dl>
<dt>Category:</dt>
<dd><?=htmlspecialchars($auction['ITEM_CATEGORY'])?></dd>

<dt>Open Time:</dt>
<dd><?=htmlspecialchars($auction['OPEN_TIME'])?></dd>
</dl>
| <a href="sellitem.php?id=<?=htmlspecialchars($auction['AUCTION_ID'])?>">Edit</a>  |
<a href="close.php?id=<?=htmlspecialchars($auction['AUCTION_ID'])?>">Close </a> |
</div>
<br/>
<?php
endforeach;
$myOpenAuctionQuery->closeCursor();
?>

</body>
</html>