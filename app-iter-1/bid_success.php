<?php
require '/u/z/users/cs105/wickham/PHP/openZdatabase.php';

$newIdQuery = $database->prepare('SELECT NEXT_SEQ_VALUE(:seqGenName);');
$newIdQuery->bindValue(':seqGenName', 'AUCTION', PDO::PARAM_STR);

$newIdQuery->execute();
$thisAuctionId = $newIdQuery->fetchColumn(0);
$newIdQuery->closeCursor();

$openAuctionQuery = $database->prepare('
    SELECT
		AUCTION_ID
		FROM AUCTION
		WHERE AUCTION_ID = :auctionId;
		');
$openAuctionQuery->bindValue(':auctionId', $thisAuctionId, PDO::PARAM_INT);
$openAuctionQuery->execute();
$thisAuction = $openAuctionQuery->fetch();
$openAuctionQuery->closeCursor();


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

/*$openAuctionQuery = $database->prepare('
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
*/

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

?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>

<title>Redirecting</title>
<link rel="stylesheet" href="firstStyle.css" type="text/css" />
<meta charset="utf-8" />
</head>

<body>
<div class="red">
Bid Success!<br/><br/>
 <a href="buy.php">Click here if not redirected.</a>
</div>
</body>
</html>