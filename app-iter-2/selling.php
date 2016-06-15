<?php
session_start();
if (!isset($_SESSION['user']))
{
    $_SESSION['login_message'] = htmlspecialchars("You need to log in before you can do that!");
    header('Location: index.php');
}
require('/u/z/users/cs105/wickham/PHP/openZdatabase.php');
$user = $_SESSION['user'];
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

<h1> <?=htmlspecialchars($_SESSION['username'])?>, these are your current listings:</h1>

<a href="sell.php">Back</a>
</div>

<br/>
<?php
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
<?php
foreach ($sellersItems as $element):
?>
<div>

<p>
            <a href="sellitem.php?id=<?=$element['AUCTION_ID']?>"><?=htmlspecialchars($element['ITEM_CAPTION'])?></a><br/>
            Current price: <?=htmlspecialchars(($element['BID_AMOUNT']) ? '$' . $element['BID_AMOUNT'] : 'No bids yet')?><br/>
            Category: <?=htmlspecialchars($element['ITEM_CATEGORY'])?><br/>
            Auction Start: <?=htmlspecialchars($element['OPEN_TIME'])?><br/>
            Time Left: <?=htmlspecialchars($element['TIME_LEFT'])?><br/>
        </p>
<form action="sellitem.php" method="post">
            <input type="hidden" name="id" value="<?=htmlspecialchars($element['AUCTION_ID'])?>"/>
            <input type="submit" value="Update Listing"/>
        </form>
<form action="close.php" method="post">
            <input type="hidden" name="item" value="<?=htmlspecialchars($element['ITEM_CAPTION'])?>"/>
            <input type="hidden" name="itemId" value="<?=$element['AUCTION_ID']?>"/>
            <input type="submit" value="Close Listing"/>
        </form>
</div>
<?php
endforeach;
?>
<br/>


</body>
</html>