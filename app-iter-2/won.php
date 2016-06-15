<?php
session_start();
if (!isset($_SESSION['user']))
{
    $_SESSION['login_message'] = htmlspecialchars("You need to log in before you can do that!");
    header('Location: index.php');
}
require('/u/z/users/cs105/wickham/PHP/openZdatabase.php');



$user = $_SESSION['user'];
$biddingQuery = $database->prepare('
    SELECT DISTINCT
    	A.AUCTION_ID,
    	A.SELLER,
    	A.STATUS,
    	A.OPEN_TIME,
    	A.CLOSE_TIME,
    	CONCAT(
            	FLOOR(HOUR(TIMEDIFF(A.CLOSE_TIME, NOW())) / 24), " days ",
            	MOD(HOUR(TIMEDIFF(A.CLOSE_TIME, NOW())), 24), " hours ",
            	MINUTE(TIMEDIFF(A.CLOSE_TIME, NOW())), " minutes") AS TIME_LEFT,
    	C.NAME AS ITEM_CATEGORY,
    	A.ITEM_CAPTION,
    	(SELECT MAX(AMOUNT)
        	FROM BID
        	WHERE AUCTION = A.AUCTION_ID) AS BID_AMOUNT,
    	(SELECT MAX(AMOUNT)
        	FROM BID
        	WHERE AUCTION = A.AUCTION_ID AND BIDDER = :user) AS BIDDER_MAX
    	FROM AUCTION A
    	JOIN ITEM_CATEGORY C ON A.ITEM_CATEGORY = C.ITEM_CATEGORY_ID
    	JOIN BID B ON A.AUCTION_ID = B.AUCTION
    	WHERE A.STATUS = 1 AND A.SELLER <> :user AND B.BIDDER = :user;
    ');
$biddingQuery->bindValue(':user',$user, PDO::PARAM_INT);
$biddingQuery->execute();
$biddersCurrent = $biddingQuery->fetchAll();
$biddingQuery->closeCursor();

$wonItemQuery = $database->prepare('
SELECT DISTINCT
    A.AUCTION_ID,
    A.PAID_FOR,
    A.SELLER,
    A.STATUS,
    C.NAME AS ITEM_CATEGORY,
    A.ITEM_CAPTION,
    (SELECT MAX(AMOUNT)
        FROM BID
        WHERE AUCTION = A.AUCTION_ID) AS BID_AMOUNT,
    (SELECT MAX(AMOUNT)
        FROM BID
        WHERE AUCTION = A.AUCTION_ID AND BIDDER = :user) AS BIDDER_MAX
    FROM AUCTION A
    JOIN ITEM_CATEGORY C ON A.ITEM_CATEGORY = C.ITEM_CATEGORY_ID
    LEFT JOIN BID B ON A.AUCTION_ID = B.AUCTION
    WHERE A.STATUS = 3 AND A.SELLER <> :user AND B.BIDDER = :user;
    ');
$wonItemQuery->bindValue(':user',$user, PDO::PARAM_INT);
$wonItemQuery->execute();
$wonItems = $wonItemQuery->fetchAll();
$biddingQuery->closeCursor();

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
<h1><?=htmlspecialchars($_SESSION['username'])?>, these are your current winnings:</h1>
<a href="sell.php">Back</a>
</div>
<?php
if (!count($wonItems)):
?>
  <div>      <h5>You have not won any items yet!</h5>
</div>
<?php
endif;
?>
<?php
if (count($wonItems)):
?>
<?php
foreach ($wonItems as $element):
?>
<div>
        <p>
            <a href="sellitem.php?id=<?=$element['AUCTION_ID']?>"><?=htmlspecialchars($element['ITEM_CAPTION'])?></a><br/>
            Ending Price: <?=htmlspecialchars(($element['BID_AMOUNT']) ? '$' . $element['BID_AMOUNT'] : 'No bids yet')?><br/>
            Category: <?=htmlspecialchars($element['ITEM_CATEGORY'])?><br/>
        </p>
<?php
if ($element['BID_AMOUNT'] > $element['BIDDER_MAX']):
?>
        <p><strong>You didn't win this item!</strong></p>
<?php
elseif (!$element['PAID_FOR']):
?>
        <form action="pay.php" method="post">
            <input type="hidden" name="id" value="<?=htmlspecialchars($element['AUCTION_ID'])?>"/>
            <input type="submit" value="Pay Now!"/>
        </form>
<?php
else:
?>
    <p><strong>You have paid for this item!</strong></p>
<?php
endif;
?>
    </div>

<?php
endforeach;
?>
<?php
endif;
?>

</body>
</html>