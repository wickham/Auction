<?php
session_start();
if (!isset($_SESSION['user']))
{
    $_SESSION['login_message'] = htmlspecialchars("You need to log in before you can do that!");
    header('Location: index.php');
}
require('/u/z/users/cs105/wickham/PHP/openZdatabase.php');



$user = $_SESSION['user'];
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

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>

<title>Notifying Seller</title>
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
<div class="red">
<a href="won.php">Back</a>
</div>

<div>
<h1><?=htmlspecialchars($_SESSION['username'])?>, these are your current winnings:</h1>
<h3>Payment information for <?=htmlspecialchars($thisAuction['ITEM_CAPTION'])?></h3>
<br/>
Confirm Information
<h6>You will receive conformation e-mail with instructions on how to pay</h6>


<dl>
<dt>Item Won:</dt>
<dd><h2><center><?=htmlspecialchars($thisAuction['ITEM_CAPTION'])?></center></h2></dd><br/>
</dl>
<img class="icons" alt="<?=htmlspecialchars($thisAuction['ITEM_CAPTION'])?>" src="photo.php?photoId=<?=$thisAuction['AUCTION_ID']?>"/>
<p>
                    <strong><?=htmlspecialchars($thisAuction['ITEM_CAPTION'])?></strong><br/>
                    <strong>Current price: </strong><?=htmlspecialchars($bidAmount)?><br/>
                    <strong>Seller: </strong><?=htmlspecialchars($thisAuction['SELLER'])?><br/>
                </p>

<form action="pay_check.php" method="post">
            <select name="payment_method">
            <option value="amex">American Express</option>
            <option value="mastercard">Mastercard</option>
            <option value="visa">Visa</option>
            </select>
            <input type="hidden" name="id" value="<?=htmlspecialchars($thisAuctionId)?>"/>
            <input type="submit" value="Submit"/>
        </form>


</div>
</body>
</html>