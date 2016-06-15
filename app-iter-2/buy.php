<?php
session_start();
require('/u/z/users/cs105/wickham/PHP/openZdatabase.php');
$openAuctionQuery = $database->prepare('
    SELECT
        A.AUCTION_ID,
		CONCAT(P.FORENAME, \' \', P.SURNAME) AS SELLER,
        A.STATUS,
        A.OPEN_TIME,
        A.CLOSE_TIME,
        CONCAT(
            FLOOR(HOUR(TIMEDIFF(A.CLOSE_TIME, NOW())) / 24), " days ",
            MOD(HOUR(TIMEDIFF(A.CLOSE_TIME, NOW())), 24), " hours ",
            MINUTE(TIMEDIFF(A.CLOSE_TIME, NOW())), " minutes") AS TIME_LEFT,
        C.NAME AS ITEM_CATEGORY,
        A.ITEM_CAPTION,
        A.ITEM_PHOTO,
        (
            SELECT 
                MAX(AMOUNT)
            FROM BID
                JOIN AUCTION ON AUCTION_ID = AUCTION
            WHERE AUCTION = A.AUCTION_ID
        ) AS BID_AMOUNT
    FROM AUCTION A
        JOIN ITEM_CATEGORY C ON A.ITEM_CATEGORY = C.ITEM_CATEGORY_ID
		JOIN PERSON P ON A.SELLER = P.PERSON_ID
    WHERE A.STATUS = 1;
    ');
$openAuctionQuery->execute();
$thisAuction = $openAuctionQuery->fetchAll();
$openAuctionQuery->closeCursor();

$categoriesQuery = $database->prepare('
    SELECT
        ITEM_CATEGORY_ID,
        NAME
        FROM ITEM_CATEGORY;
    ');
$categoriesQuery->execute();
$categories = $categoriesQuery->fetchAll();
$categoriesQuery->closeCursor();

$elementsToDisplay = 10;
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
  <head>
    <title>Current Auctions.</title>
    <link rel="stylesheet" href="firstStyle.css" type="text/css" />
    <meta charset="utf-8" />
  </head>


<body>
<div class="top">
<nav>
<?php
if (isset($_SESSION['user']) && !empty($_SESSION['user'])):
?>
Signed in as, <?=htmlspecialchars($_SESSION['username'])?><br/>
<span class="account">
<a href="sell.php">My Account</a>
<br/>
<a href="logout.php">Logout</a>
</span>
<?php
endif;
?>
<form>
<span class="search">
<input type="text" placeholder="Search" name="search" />
</span>
</form>
</nav>
</div>
<br/>
<div class="red">
<h1>Current Auctions</h1>
<p><?=count($thisAuction)?> out of <?=count($thisAuction)?> results</p>

<a href="sell.php">Back</a>
</div>
<div class="top">
<nav>
<form>
<span class="search">
<br/>
Search our database: 

<input type="search" name="searchdata" />
<input type="submit" value="Search" class="button" />

</span>
</form>
<br/>
<form action="searching.php" method="get" target="new">
<span class="account">
Sort by: <select name="category">
<?php
foreach ($categories as $category):
?>
                     <option value="<?=$category['ITEM_CATEGORY_ID']?>"><?=htmlspecialchars($category['NAME'])?></option>
<?php
endforeach;
?>  
         </select>
<button class="button">Search</button>

</span>
</form>
</nav>
</div>


 
<br/>
<?php
foreach ($thisAuction as $element):
?>
<div class="items">
<a href="bid.php?id=<?=$element['AUCTION_ID']?>"><img class="icons" alt="<?=htmlspecialchars($element['ITEM_CAPTION'])?>" src="photo.php?photoId=<?=$element['AUCTION_ID']?>"/></a>
        <p>
<h1><a href="bid.php?id=<?=$element['AUCTION_ID']?>"><?=htmlspecialchars($element['ITEM_CAPTION'])?></a></h1><br/>
			Current price: <?=htmlspecialchars(($element['BID_AMOUNT']) ? '$' . $element['BID_AMOUNT'] : 'No bids yet')?><br/>
			Seller: <?=htmlspecialchars($element['SELLER'])?><br/>
			 Category: <?=htmlspecialchars($element['ITEM_CATEGORY'])?><br/>
            <br/>
           
            Open Time: <?=htmlspecialchars($element['OPEN_TIME'])?><br/>
            Time Left: <?=htmlspecialchars($element['TIME_LEFT'])?><br/>
        </p>
    </div>

<?php
endforeach;
?>
</div> 
        <div class="top">
            <nav>
<?php
if (count($thisAuction) > $elementsToDisplay):
?>
                <a href="buy.php">Next Page</a>
<?
endif;
?>

          


</div>
</body>
</html>