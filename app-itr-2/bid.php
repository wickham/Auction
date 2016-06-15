<?php
require '/u/z/users/cs105/wickham/app-itr-2/openZdatabase.php';
$openAuctionQuery = $database->prepare('
    SELECT
        AUCTION.STATUS,
        CONCAT(PERSON.FORENAME, \' \', PERSON.SURNAME) AS SELLER,
        AUCTION.OPEN_TIME,
        AUCTION.CLOSE_TIME,
		AUCTION.STATUS,
        ITEM_CATEGORY.NAME AS ITEM_CATEGORY,
		AUCTION.ITEM_DESCRIPTION,
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
$openAuctionQuery = $database->prepare('
		SELECT
		BID.AMOUNT
		FROM BID
		WHERE BID.BID_ID = :bidId;
		');
$openAuctionQuery->bindValue(':bidId', $thisAuctionId, PDO::PARAM_INT);	
$openAuctionQuery->execute();
$Auction = $openAuctionQuery->fetch();
$openAuctionQuery->closeCursor();	


?>
<!DOCTYPE html>
<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
  <head>
   <title>Listing - <?=htmlspecialchars($thisAuction['ITEM_CAPTION'])?></title>
    <meta charset="utf-8" />
    <link href="firstStyle.css" type="text/css" rel="stylesheet" />
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
<h1>Current Bid</h1>
<p>
<a href="buy.php">Back</a>
</p>
</div>
<br/>
<div>
<form action="bid_success.php" method="post" enctype="multipart/form-data">

<?php
if ($thisAuction["STATUS"] != 1):
?>

<fieldset disabled>
<p class="closed">This auction is closed.</p>

<?php
endif;
?>

<fieldset>
<br/>

<h1> <?=htmlspecialchars($thisAuction['ITEM_CAPTION'])?></h1>
<img src="auctionPhoto.php?id=<?= $thisAuctionId ?>" class="icons" />
<dl>
      <dt><h3> Category: </h3></dt>
        <dd> <?=htmlspecialchars($thisAuction['ITEM_CATEGORY'])?> </dd>
      <dt> <h3>Description: </h3> </dt>
        <dd> <?=htmlspecialchars($thisAuction['ITEM_DESCRIPTION'])?> </dd>
      <dt><h3>Current Bid: </h3></dt>
        <dd> $ <?=htmlspecialchars($Auction['AMOUNT'])?> </dd>
      <dt><h3>Minimum Bid: </h3></dt>
        <dd>$ 10.00</dd>
      <dt> <h3>Auction Started: </h3> </dt>
        <dd> <?=htmlspecialchars($thisAuction['OPEN_TIME'])?> </dd>        
      <dt><h3>Auction Ends: </h3></dt>
        <dd> <?=htmlspecialchars($thisAuction['CLOSE_TIME'])?> </dd>
      

  
<dt>Bid Ammount:</dt>
<dd>
<input type="number" name="itemPrice" /> 
<button type="submit">Bid</button></dd>
</dl>
</fieldset>
</form>
</div>



 

</body>
</html>