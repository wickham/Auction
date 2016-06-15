<?php
session_start();
if (!isset($_SESSION['user']))
{
    $_SESSION['login_message'] = htmlspecialchars("You need to log in before you can do that!");
    header('Location: index.php');
}
require('/u/tcorley/openZdatabase.php');
$user = $_SESSION['user'];
$biddingActivityQuery = $database->prepare('
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
$biddingActivityQuery->bindValue(':user',$user, PDO::PARAM_INT);
$biddingActivityQuery->execute();
$biddersCurrent = $biddingActivityQuery->fetchAll();
$biddingActivityQuery->closeCursor();

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
$biddingActivityQuery->closeCursor();

$sellerItemsQuery = $database->prepare('
SELECT
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
    (
        SELECT 
            MAX(AMOUNT)
        FROM BID
            JOIN AUCTION ON AUCTION_ID = AUCTION
        WHERE AUCTION = A.AUCTION_ID
    ) AS BID_AMOUNT
    FROM AUCTION A
    JOIN ITEM_CATEGORY C ON A.ITEM_CATEGORY = C.ITEM_CATEGORY_ID
    LEFT JOIN BID B ON A.AUCTION_ID = B.AUCTION
    WHERE A.STATUS = 1 AND A.SELLER = :user; 
    ');
$sellerItemsQuery->bindValue(':user',$user, PDO::PARAM_INT);
$sellerItemsQuery->execute();
$sellersItems = $sellerItemsQuery->fetchAll();
$sellerItemsQuery->closeCursor();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <meta charset="utf-8"/>
        <title>User Activity</title>
        <link rel="stylesheet" type="text/css" href="stylesheet.css"/>
        <link href='http://fonts.googleapis.com/css?family=Gafata' rel='stylesheet' type='text/css'/>
    </head>
    <body>
        <ul class="navbar">
            <li><a href="mainpage.php">Home</a></li>
            <li><a href="browse.php">Browse</a></li>
            <li><a href="list_item.php">List Item</a></li>
            <li><a href="logout_confirm.php">Logout</a></li>
            <li><a href="accountholder_info.php">Your account details</a></li>
            <li class="go_right">You are <?=htmlspecialchars($_SESSION['username'])?> for this session :)</li>
        </ul>
        <div class="content">
            <h1><?=htmlspecialchars($_SESSION['username'])?>'s Activity</h1>
            <form action="mainpage.php">
                <input type="submit" value="Back to main"/>
            </form>
  

            <h3 class="user_separator">Your bids</h3>
<?php
if (!count($biddersCurrent)):
?>
        <h5>You aren't bidding on anything! <a href="browse.php">browse</a> for some items!</h5>
<?php
endif;
?>

<?php
foreach ($biddersCurrent as $element):
?>

    <div class="item_default">
<?php
if ($element['BID_AMOUNT'] > $element['BIDDER_MAX']):
?>
    <p class="warning">You've been outbidded! Your last bid was $<?=htmlspecialchars($element['BIDDER_MAX'])?>.</p>
<?php
else:
?>
    <p class="goodtogo">You're the current high bidder!</p>
<?php
endif;
?>
        <p>
            <a href="item.php?id=<?=$element['AUCTION_ID']?>"><?=htmlspecialchars($element['ITEM_CAPTION'])?></a><br/>
            Current price: <?=htmlspecialchars(($element['BID_AMOUNT']) ? '$' . $element['BID_AMOUNT'] : 'No bids yet')?><br/>
            Category: <?=htmlspecialchars($element['ITEM_CATEGORY'])?><br/>
            Auction Start: <?=htmlspecialchars($element['OPEN_TIME'])?><br/>
            Time Left: <?=htmlspecialchars($element['TIME_LEFT'])?><br/>
        </p>

    </div>

<?php
endforeach;
?>




            <h3 class="user_separator">Ended Auctions</h3>

<?php
if (!count($wonItems)):
?>
        <h5>No items have ended. Keep a lookout!</h5>
<?php
endif;
?>

<?php
if (count($wonItems)):
?>
<?php
foreach ($wonItems as $element):
?>

    <div class="item_default">
        <p>
            <a href="item.php?id=<?=$element['AUCTION_ID']?>"><?=htmlspecialchars($element['ITEM_CAPTION'])?></a><br/>
            Ending Price: <?=htmlspecialchars(($element['BID_AMOUNT']) ? '$' . $element['BID_AMOUNT'] : 'No bids yet')?><br/>
            Category: <?=htmlspecialchars($element['ITEM_CATEGORY'])?><br/>
        </p>
<?php
if ($element['BID_AMOUNT'] > $element['BIDDER_MAX']):
?>
        <p class="warning">You didn't win this item:(</p>
<?php
elseif (!$element['PAID_FOR']):
?>
        <form action="pay_for_item.php" method="post">
            <input type="hidden" name="id" value="<?=htmlspecialchars($element['AUCTION_ID'])?>"/>
            <input type="submit" value="Pay for Item!"/>
        </form>
<?php
else:
?>
    <p class="goodtogo">You have paid for this item!</p>
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

            <h3 class="user_separator">Your Items</h3>
<?php
if (!count($sellersItems)):
?>
        <h5>You aren't selling anything! <a href="list_item.php">list</a> an item!</h5>
<?php
endif;
?>
<?php
foreach ($sellersItems as $element):
?>

    <div class="item_default">
        <p>
            <a href="item.php?id=<?=$element['AUCTION_ID']?>"><?=htmlspecialchars($element['ITEM_CAPTION'])?></a><br/>
            Current price: <?=htmlspecialchars(($element['BID_AMOUNT']) ? '$' . $element['BID_AMOUNT'] : 'No bids yet')?><br/>
            Category: <?=htmlspecialchars($element['ITEM_CATEGORY'])?><br/>
            Auction Start: <?=htmlspecialchars($element['OPEN_TIME'])?><br/>
            Time Left: <?=htmlspecialchars($element['TIME_LEFT'])?><br/>
        </p>
        <form action="cancel.php" method="post">
            <input type="hidden" name="item" value="<?=htmlspecialchars($element['ITEM_CAPTION'])?>"/>
            <input type="hidden" name="itemId" value="<?=$element['AUCTION_ID']?>"/>
            <input type="submit" value="Cancel Listing"/>
        </form>
        <form action="update.php" method="post">
            <input type="hidden" name="id" value="<?=htmlspecialchars($element['AUCTION_ID'])?>"/>
            <input type="submit" value="Update Listing"/>
        </form>
    </div>

<?php
endforeach;
?>
        </div>
    </body>
</html>