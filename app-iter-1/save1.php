<?php
require '/u/z/users/cs105/wickham/PHP/openZdatabase.php';


$updateAuctionStmt = $database->prepare('
    UPDATE AUCTION
        SET
            CLOSE_TIME = :closeTime,
            ITEM_CATEGORY = :category,
            ITEM_CAPTION = :caption,
            ITEM_DESCRIPTION = :description,
            ITEM_PHOTO = :photo
        WHERE AUCTION_ID = :auctionId AND STATUS = 1 AND SELLER = :seller;
    ');


$photoFile =fopen($_FILES['poto']['tmp_name'], 'rb');
$thisAuctionId = (int)$_REQUEST["id"];

$updateAuctionStmt->bindValue(':auctionId', $thisAuctionId, PDO::PARAM_INT);
$updateAuctionStmt->bindValue(':seller', 1, PDO::PARAM_INT);
$updateAuctionStmt->bindValue(':closeTime', $_REQUEST['closeTime'], PDO::PARAM_STR);
$updateAuctionStmt->bindValue(':category', $_REQUEST['category'], PDO::PARAM_INT);
$updateAuctionStmt->bindValue(':caption', $_REQUEST['caption'], PDO::PARAM_STR);
$updateAuctionStmt->bindValue(':description', $_REQUEST['description'], PDO::PARAM_STR);
$updateAuctionStmt->bindValue(':photo', $_REQUEST['photo'], PDO::PARAM_LOB);
$updateAuctionStmt->execute();
$updateAuctionStmt->closeCursor();
$openAuctionQuery = $database->prepare('
    SELECT
        AUCTION.STATUS,
        CONCAT(PERSON.FORENAME, \' \', PERSON.SURNAME) AS SELLER,
        AUCTION.OPEN_TIME,
        AUCTION.CLOSE_TIME,
        ITEM_CATEGORY.NAME AS ITEM_CATEGORY,
        AUCTION.ITEM_CAPTION,
        AUCTION.ITEM_DESCRIPTION
        FROM AUCTION
            JOIN ITEM_CATEGORY ON AUCTION.ITEM_CATEGORY = ITEM_CATEGORY.ITEM_CATEGORY_ID
            JOIN PERSON ON AUCTION.SELLER = PERSON.PERSON_ID
        WHERE AUCTION.AUCTION_ID = :auctionId;
    ');
$openAuctionQuery->bindValue(':auctionId', $thisAuctionId, PDO::PARAM_INT);
$openAuctionQuery->execute();
$thisAuction = $openAuctionQuery->fetch();
$openAuctionQuery->closeCursor();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
  <head>
    <title>Edit - <?=htmlspecialchars($thisAuctionId)?></title>
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
<?php
if ($thisAuction["STATUS"] != 1):
?>

<div class="red">
        <p class="closed">This auction is closed!</p>
</div>
<?php
endif;
?>

<br/>
<div class="red"><a href="selling.php">Back</a></div><br/>
<div>
        <h3><?=htmlspecialchars($thisAuction['ITEM_CAPTION'])?></h3>
        <dl>
        	<dt><img src="auctionPhoto.php?id=<?=htmlspecialchars($thisAuctionId) ?>" class="edit" /></dt>
        	<dd><?=htmlspecialchars($thisAuction['ITEM_DESCRIPTION'])?></dd>
             <dt>Seller:</dt>
          	<dd><?=htmlspecialchars($thisAuction['SELLER'])?></dd>
            <dt>Current bid:</dt>
          	<dd>$0.00</dd>
            <br/>
          	<dt>Category:</dt>
          	<dd><?=htmlspecialchars($thisAuction['ITEM_CATEGORY'])?></dd>
          	<dt>Open time:</dt>
          	<dd><?=htmlspecialchars($thisAuction['OPEN_TIME'])?></dd>
          	<dt>Close time:</dt>
          	<dd><?=htmlspecialchars($thisAuction['CLOSE_TIME'])?></dd>

          	
          	</dl>



</div>
</body>
</html>
