<?php
session_start();
require('/u/z/users/cs105/wickham/PHP/openZdatabase.php');
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
<?php
if (isset($_SESSION['user']) && !empty($_SESSION['user'])):
?>
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



<?php
endif;
?>
</div>


<div class="red">
<h1>Current Bid</h1>
<p>
<a href="buy.php">Back</a>
</p>
</div>
<br/>
<?php
if ($thisAuction["STATUS"] != 1):
?><div class="red">
        <p class="closed">This auction is closed!</p>
</div>
<?php
elseif ($thisAuction['STATUS'] == 3 && $highBidder['BIDDER'] == $user):
?><div class="red">
        <p class="closed">You Won!</p>
</div>
<?php
elseif (isset($_SESSION['user']) && $thisAuction['ITEM_OWNER'] != $_SESSION['user'] && !$currUserBid['AMOUNT']):
?>
       <div class="red"><h1>Bid NOW</h1></div>
<?php
elseif ($thisAuction['ITEM_OWNER'] != $_SESSION['user'] && $currUserBid['AMOUNT'] == $highBidder['AMOUNT']):
?><div class="red">
       <p class="closed">You are currently the highest bidder!</p>
       </div>
<?php
elseif (isset($_SESSION['user']) && $currUserBid['AMOUNT'] < $highBidder['AMOUNT'] && $thisAuction['STATUS'] == 3):
?><div class="red">
        <p class="closed">You lost this auction.</p>
</div>
<?php
elseif (isset($_SESSION['user']) && $currUserBid['AMOUNT'] < $highBidder['AMOUNT'] && $_SESSION['user'] != $thisAuction['ITEM_OWNER']):
?><div class="red">
        <p class="closed">New High Bid!</p>
        </div>
<?php
endif;
?>
</div>
<br/>
<div>

<h1> <center><?=htmlspecialchars($thisAuction['ITEM_CAPTION'])?></center></h1>
<img src="photo.php?photoId=<?=$thisAuction['AUCTION_ID']?>" class="icons" />
<dl>
      <dt><h3> Category: </h3></dt>
        <dd> <?=htmlspecialchars($thisAuction['ITEM_CATEGORY'])?> </dd>
      <dt> <h3>Description: </h3> </dt>
        <dd> <?=htmlspecialchars($thisAuction['ITEM_DESCRIPTION'])?> </dd>
      <dt><h3>Current Bid: </h3></dt>
        <dd> <?=htmlspecialchars($bidAmount)?></dd>
      <dt> <h3>Auction Started: </h3> </dt>
        <dd> <?=htmlspecialchars($thisAuction['OPEN_TIME'])?> </dd>        
      <dt><h3>Auction Ends: </h3></dt>
        <dd> <?=htmlspecialchars($thisAuction['TIME_LEFT'])?> </dd>
    </dl>   
 
 
 
 <?php
if ($thisAuction['ITEM_OWNER'] == $user):
?>
        <form action="close.php" method="post">
            <input type="hidden" name="item" value="<?=htmlspecialchars($thisAuction['ITEM_CAPTION'])?>"/>
            <input type="hidden" name="itemId" value="<?=htmlspecialchars($thisAuction['AUCTION_ID'])?>"/>
            <input type="submit" value="Close"/>
        </form>
        <form action="sellitem.php" method="post">
            <input type="hidden" name="id" value="<?=htmlspecialchars($thisAuction['AUCTION_ID'])?>"/>
            <input type="submit" value="Update"/>
        </form>
<?php
elseif (!$user):
?>
<p>You need to <a href="login.php">login</a> before you can place a bid! </p>
<?php
elseif ($thisAuction['STATUS'] == 1):
?>
    <?php
    if ($_SESSION['bid_error']):
    ?>
            <p><?=htmlspecialchars($_SESSION['bid_error'])?></p>
    <?php
    unset($_SESSION['bid_error']);
    endif;
    ?>
        <form action="bid_success.php" method="post">
            <input type="number" required="required" name="user_bid"/>
            <input type="hidden" name="auction" value="<?=$thisAuction['AUCTION_ID']?>"/>
            <input type="submit" value="Place Bid"/>
        </form>
        <form action="endAuction.php" method="post">
            <input type="hidden" name="id" value="<?=htmlspecialchars($thisAuction['AUCTION_ID'])?>"/>
            <input type="submit" value="End auction"/>
            <p>(this is for setting auciton to "3" or ending it)
        </form>

<?php
elseif ($thisAuction['STATUS'] == 3 && $highBidder['BIDDER'] == $user && !$thisAuction['PAID_FOR']):
?>
        <form action="pay.php" method="post">
            <input type="hidden" name="id" value="<?=htmlspecialchars($thisAuction['AUCTION_ID'])?>"/>
            <input type="submit" value="Pay Now!"/>
        </form>
<?php
endif;
?>
 
     </div>
</body>
</html>