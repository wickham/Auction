<?php
require '/u/z/users/cs105/wickham/PHP/openZdatabase.php';
$openAuctionQuery = $database->prepare('
    SELECT
        A.STATUS,
        A.ITEM_CATEGORY,
        A.ITEM_CAPTION,
        A.ITEM_DESCRIPTION
        FROM AUCTION A
            JOIN PERSON P ON A.SELLER = P.PERSON_ID
        WHERE A.AUCTION_ID = :auctionId;
    ');
$thisAuctionId = $_REQUEST['id'];
$openAuctionQuery->bindValue(':auctionId', $thisAuctionId, PDO::PARAM_INT);
$openAuctionQuery->execute();
$thisAuction = $openAuctionQuery->fetch();
$openAuctionQuery->closeCursor();

//close function
$closeAuctionQuery = $database->prepare('
		UPDATE AUCTION 
		SET STATUS = 5 
		WHERE AUCTION_ID = :auctionId;

');
$closeAuctionQuery->bindValue(':auctionId', $thisAuctionId, PDO::PARAM_INT);
$closeAuctionQuery->execute();
$thisAuction = $openAuctionQuery->fetch();
$closeAuctionQuery->closeCursor();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>

<title>Redirecting</title>
<link rel="stylesheet" href="firstStyle.css" type="text/css" />
<meta charset="utf-8" />
</head>

<body>
<div class="red">
Listing  <p><h3><?=htmlspecialchars($thisAuction['ITEM_CAPTION'])?></h3></p> is now CLOSED!<br/>
If this is an error you can re-post your item by using the EDIT feature.
<br/><br/>
 <a href="selling.php">Click here if not redirected.</a>
</div>
</body>
</html>