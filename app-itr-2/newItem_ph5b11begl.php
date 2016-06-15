<?php
require '/u/z/users/cs105/wickham/PHP/openZdatabase.php';

$newIdQuery = $database->prepare('SELECT NEXT_SEQ_VALUE(:seqGenName);');
$newIdQuery->bindValue(':seqGenName', 'AUCTION', PDO::PARAM_STR);

$newIdQuery->execute();
$thisAuctionId = $newIdQuery->fetchColumn(0);
$newIdQuery->closeCursor();

$insertAuctionStmt = $database->prepare('
    INSERT AUCTION
        (AUCTION_ID, STATUS, SELLER, OPEN_TIME, CLOSE_TIME, ITEM_CATEGORY, ITEM_CAPTION, ITEM_DESCRIPTION, ITEM_PHOTO)
        VALUES (:auctionId, :status, :seller, CURRENT_TIMESTAMP, :closeTime, :category, :caption, :description, :photo);
	');
	$photoFile = fopen($_FILES['photo']['tmp_name'], 'rb');

$insertAuctionStmt->bindValue(':auctionId', $thisAuctionId, PDO::PARAM_INT);
$insertAuctionStmt->bindValue(':status', 1, PDO::PARAM_INT);
$insertAuctionStmt->bindValue(':seller', 1, PDO::PARAM_INT);
$insertAuctionStmt->bindValue(':closeTime', $_REQUEST['closeTime'], PDO::PARAM_STR);
$insertAuctionStmt->bindValue(':category', $_REQUEST['category'], PDO::PARAM_INT);
$insertAuctionStmt->bindValue(':caption', $_REQUEST['caption'], PDO::PARAM_STR);
$insertAuctionStmt->bindValue(':description', $_REQUEST['description'], PDO::PARAM_STR);
$insertAuctionStmt->bindValue(':photo', $photoFile, PDO::PARAM_LOB);

$insertAuctionStmt->execute();
$insertAuctionStmt->closeCursor();

//BID
$newIdQuery->bindValue(':seqGenName', 'BID', PDO::PARAM_STR);
$newIdQuery->execute();
$thisBidId = $newIdQuery->fetchColumn(0);
$newIdQuery->closeCursor();	


$insertAuctionStmt = $database->prepare('	
	INSERT INTO BID
		(BID_ID, BIDDER, AUCTION, BID_TIME, AMOUNT)
	VALUES (:bidId, :bidder, :auction, current_timestamp, :itemPrice);
    ');
//BID
$insertAuctionStmt->bindValue(':bidId', $thisBidId, PDO::PARAM_INT);
$insertAuctionStmt->bindValue(':bidder', 1, PDO::PARAM_INT);
$insertAuctionStmt->bindValue(':auction', $thisBidId, PDO::PARAM_INT);
$insertAuctionStmt->bindValue(':itemPrice', $_REQUEST['itemPrice'], PDO::PARAM_STR);
$insertAuctionStmt->execute();
$insertAuctionStmt->closeCursor();

$openAuctionQuery = $database->prepare('
    SELECT
        AUCTION.STATUS,
        CONCAT(PERSON.FORENAME, \' \', PERSON.SURNAME) AS SELLER,
		AUCTION.ITEM_CAPTION,
        AUCTION.OPEN_TIME,
        AUCTION.CLOSE_TIME,
        ITEM_CATEGORY.NAME AS ITEM_CATEGORY,
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

$openAuctionQuery = $database->prepare('
		SELECT
		BID.AMOUNT
		FROM BID
		WHERE BID.BID_ID = :bidId;
		');
$openAuctionQuery->bindValue(':bidId', $thisBidId, PDO::PARAM_INT);	
$openAuctionQuery->execute();
$Auction = $openAuctionQuery->fetch();
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
<div class="red"><a href="selling.php">Back</a></div>
<br/>
<div>
        <h3><?=htmlspecialchars($thisAuction['ITEM_CAPTION'])?></h3>
        <dl>
        	<dt><img src="auctionPhoto.php?id=<?=htmlspecialchars($thisAuctionId) ?>" class="edit" /></dt>
        	<dd><?=htmlspecialchars($thisAuction['ITEM_DESCRIPTION'])?></dd>
             <dt>Seller:</dt>
          	<dd><?=htmlspecialchars($thisAuction['SELLER'])?></dd>
            <dt>Current Price:</dt>
          	<dd>$<?=htmlspecialchars($Auction['AMOUNT'])?></dd>
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
<?php
echo "<mm:dwdrfml documentRoot=" . __FILE__ .">";$included_files = get_included_files();foreach ($included_files as $filename) { echo "<mm:IncludeFile path=" . $filename . " />"; } echo "</mm:dwdrfml>";
?>