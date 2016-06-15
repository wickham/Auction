<?php
session_start();
require('/u/tcorley/openZdatabase.php');
$openAuctionQuery = $database->prepare('
    SELECT
        A.AUCTION_ID,
        A.STATUS,
        A.PAID_FOR,
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
$thisAuctionId = $_REQUEST['id'];
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
        ) AND AUCTION = :auctionId;
    ');
$checkHighestBid->bindValue(':auctionId', $thisAuctionId, PDO::PARAM_INT);
$checkHighestBid->execute();
$highBidder = $checkHighestBid->fetch();
$checkHighestBid->closeCursor();
$user = $_SESSION['user'];
$highestUserBid = $database->prepare('
    SELECT AMOUNT
    FROM BID
    WHERE AMOUNT = (
        SELECT MAX(AMOUNT)
        FROM BID
        WHERE AUCTION = :auctionId and BIDDER = :user
        ) AND AUCTION = :auctionId;
    ');
$highestUserBid->bindValue(':user', $user, PDO::PARAM_INT);
$highestUserBid->bindValue(':auctionId', $thisAuctionId, PDO::PARAM_INT);
$highestUserBid->execute();
$currUserBid = $highestUserBid->fetch();
$highestUserBid->closeCursor();
$bidAmount = ($highBidder['AMOUNT']) ? '$' . $highBidder['AMOUNT'] : 'No bids yet';
// $user = 1;
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <meta charset="utf-8"/>
        <title>Item Details</title>
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
        <h1><?=htmlspecialchars($thisAuction['ITEM_CAPTION'])?> Details</h1>
<?php
if ($thisAuction['STATUS'] == 3 && $highBidder['BIDDER'] == $user):
?>
        <h3 class="goodtogo">You won!</h3>
<?php
elseif (isset($_SESSION['user']) && $thisAuction['ITEM_OWNER'] != $_SESSION['user'] && !$currUserBid['AMOUNT']):
?>
        <h3>You haven't bid on this item yet</h3>
<?php
elseif ($thisAuction['ITEM_OWNER'] != $_SESSION['user'] && $currUserBid['AMOUNT'] == $highBidder['AMOUNT']):
?>
        <h3 class="goodtogo">You're still the high bidder!</h3>
<?php
elseif (isset($_SESSION['user']) && $currUserBid['AMOUNT'] < $highBidder['AMOUNT'] && $thisAuction['STATUS'] == 3):
?>
        <h3 class="warning">You didn't win this auction:(</h3>
<?php
elseif (isset($_SESSION['user']) && $currUserBid['AMOUNT'] < $highBidder['AMOUNT'] && $_SESSION['user'] != $thisAuction['ITEM_OWNER']):
?>
        <h3 class="warning">You've been outbid!</h3>
<?php
endif;
?>
        <div class="item_other">
                <img class="item_pic" alt="<?=htmlspecialchars($thisAuction['ITEM_CAPTION'])?>" src="image.php?photoId=<?=$thisAuction['AUCTION_ID']?>"/>
                <p>
                    <strong><?=htmlspecialchars($thisAuction['ITEM_CAPTION'])?></strong><br/>
                    <strong>Current price: </strong><?=htmlspecialchars($bidAmount)?><br/>
                    <strong>Seller: </strong><?=htmlspecialchars($thisAuction['SELLER'])?><br/>
                    <strong>Category: </strong><?=htmlspecialchars($thisAuction['ITEM_CATEGORY'])?><br/>
                    <strong>Auction Start: </strong><?=htmlspecialchars($thisAuction['OPEN_TIME'])?><br/>
                    <strong>Time Left: </strong><?=htmlspecialchars($thisAuction['TIME_LEFT'])?><br/>
                    <strong>Other item details: </strong><?=htmlspecialchars($thisAuction['ITEM_DESCRIPTION'])?><br/>
                </p>
            </div>
<?php
if ($thisAuction['ITEM_OWNER'] == $user):
?>
        <form action="cancel.php" method="post">
            <input type="hidden" name="item" value="<?=htmlspecialchars($thisAuction['ITEM_CAPTION'])?>"/>
            <input type="hidden" name="itemId" value="<?=htmlspecialchars($thisAuction['AUCTION_ID'])?>"/>
            <input type="submit" value="Cancel Listing"/>
        </form>
        <form action="update.php" method="post">
            <input type="hidden" name="id" value="<?=htmlspecialchars($thisAuction['AUCTION_ID'])?>"/>
            <input type="submit" value="Update Listing"/>
        </form>
<?php
elseif (!$user):
?>
        <p>You need to <a href="index.php">login</a> before you can place a bid! </p>

<?php
elseif ($thisAuction['STATUS'] == 1):
?>

    <?php
    if ($_SESSION['bid_error']):
    ?>
            <p class="warning"><?=htmlspecialchars($_SESSION['bid_error'])?></p>
    <?php
    unset($_SESSION['bid_error']);
    endif;
    ?>
        <form action="bid_action.php" method="post">
            <input type="number" required="required" name="user_bid"/>
            <input type="hidden" name="auction" value="<?=$thisAuction['AUCTION_ID']?>"/>
            <input type="submit" value="Place Bid"/>
        </form>
        <p><em><strong>ALL BIDS ARE FINAL!</strong></em></p>
        <p>If you want to try out payment, place a bid and then end the auction to pay</p>
        <form action="end_auction.php" method="post">
            <input type="hidden" name="id" value="<?=htmlspecialchars($thisAuction['AUCTION_ID'])?>"/>
            <input class="button" type="submit" value="End auction...for testing"/>
        </form>

<?php
elseif ($thisAuction['STATUS'] == 3 && $highBidder['BIDDER'] == $user && !$thisAuction['PAID_FOR']):
?>
        <form action="pay_for_item.php" method="post">
            <input type="hidden" name="id" value="<?=htmlspecialchars($thisAuction['AUCTION_ID'])?>"/>
            <input type="submit" value="Pay for Item!"/>
        </form>
<?php
endif;
?>
        </div>
    </body>
</html>
