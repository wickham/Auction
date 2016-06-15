<?php
session_start();
require('/u/tcorley/openZdatabase.php');
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
$checkHighestBid = $database->prepare('
    SELECT AMOUNT, BIDDER, BID_ID
    FROM BID
    WHERE AMOUNT = (
        SELECT MAX(AMOUNT)
        FROM BID
        WHERE AUCTION = :auctionId
        );
    ');
$checkHighestBid->bindValue(':auctionId', $thisAuctionId, PDO::PARAM_INT);
$checkHighestBid->execute();
$highBidder = $checkHighestBid->fetch();
$user = $_SESSION['user'];
$bidAmount = ($highBidder['AMOUNT']) ? '$' . $highBidder['AMOUNT'] : 'No bids yet';
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <meta charset="utf-8"/>
        <title>Pay for Item</title>
        <link rel="stylesheet" type="text/css" href="stylesheet.css"/>
        <link href='http://fonts.googleapis.com/css?family=Gafata' rel='stylesheet' type='text/css'/>
    </head>
    <body>
        <ul class="navbar">
            <li><a href="mainpage.php">Home</a></li>
            <li><a href="browse.php">Browse</a></li>
            <li><a href="list_item.php">List Item</a></li>
<?php
if (isset($_SESSION['user']) && !empty($_SESSION['user'])):
?>
            <li><a href="logout_confirm.php">Logout</a></li>
            <li class="go_right">You are <?=htmlspecialchars($_SESSION['username'])?> for this session :)</li>
<?php
endif;
?>
        </ul>
        <div class="content">
        <h1>Pay for Item</h1>
         <div class="item_other">
                <img class="item_pic" alt="<?=htmlspecialchars($thisAuction['ITEM_CAPTION'])?>" src="image.php?photoId=<?=$thisAuction['AUCTION_ID']?>"/>
                <p>
                    <strong><?=htmlspecialchars($thisAuction['ITEM_CAPTION'])?></strong><br/>
                    <strong>Current price: </strong><?=htmlspecialchars($bidAmount)?><br/>
                    <strong>Seller: </strong><?=htmlspecialchars($thisAuction['SELLER'])?><br/>
                </p>
            </div>
        <form action="pay_action.php" method="post">
            <select name="payment_method">
            <option value="amex">American Express</option>
            <option value="visa">Visa</option>
            <option value="mastercard">MasterCard</option>
            </select>
            <input type="hidden" name="id" value="<?=htmlspecialchars($thisAuctionId)?>"/>
            <input type="submit" value="Submit"/>
        </form>
        </div>
    </body>
</html>
