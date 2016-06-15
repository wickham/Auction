<?php session_start();
require '/u/z/users/cs105/wickham/PHP/openZdatabase.php';
require 'password.php';
?><!DOCTYPE html>
<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>Buy, Sell, Manage!</title>
<meta charset="utf-8" />
<link href="firstStyle.css" type="text/css" rel="stylesheet" />

</head>

<body>

<div class="login">
<?php
if (isset($_SESSION['user']) && !empty($_SESSION['user'])):
?>
<nav>
Signed in as, <?=htmlspecialchars($_SESSION['username'])?><br/>
<span class="account">
<a href="sell.php">My Account</a>
<br/>
<a href="logout.php">Logout</a>
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
    
<form class="fields" action="login_go.php" method="post">
Username:
<input type="text" name="username" size="20" />
Password:
<input type="password" name="password" size="20" />

<input class="button" type="submit" value="Login" />
</form>
<form class="fields" type="text" method="post" action="register.php">
<input class="button" type="submit" value="Register" />
</form>
</div>

<div class="red">
<p><h1> Acme Auctions - Welcome!</h1></p>
<?php
if ($_SESSION['login_message']):
?>
            <p class="closed"><?=htmlspecialchars($_SESSION['login_message'])?></p>
<?php
unset($_SESSION['login_message']);
endif;
?>
<h1>Current Auctions</h1>

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
Sort by: <select name="item_category" size="1">

<option value="clothes">Clothes</option>
<option value="electronics">Electronics</option>
<option value="other">Other Options</option>
</select>
<button class="button">Search</button>

</span>
</form>
</nav>
</div>
<?php 
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

foreach ($openAuctionQuery->fetchAll() as $auction):
?> 

<div class="items">
<h3><?=htmlspecialchars($auction['ITEM_CAPTION'])?></h3>
<img src="photo.php?photoId=<?=htmlspecialchars($auction['AUCTION_ID'])?>" class="icons" />
<br/>
<dl>
<dt>Category:</dt>
<dd><?=htmlspecialchars($auction['ITEM_CATEGORY'])?></dd>
<dt> Seller:</dt>
<dd><?=htmlspecialchars($auction['SELLER'])?></dd>
<dt>Item Description:</dt>
<dd><h4><?=htmlspecialchars($auction['ITEM_DESCRIPTION'])?></h4></dd>
<br/>
<dt>Open Time:</dt>
<dd><?=htmlspecialchars($auction['OPEN_TIME'])?></dd>
<dt>Closing Time:</dt>
<dd> <?=htmlspecialchars($auction['CLOSE_TIME'])?> </dd>
<br/>
<dt>Current bid:</dt>
<dd><?=htmlspecialchars(($auction['BID_AMOUNT']) ? '$' . $auction['BID_AMOUNT'] : 'No bids yet')?></dd>
</dl> 
<form method="post" action="login.php">  
<input type="hidden" name="auctionId" value="<?=htmlspecialchars($currAuc['AUCTION_ID'])?>" />
<input class="button" type="submit" value="Bid"/>
</form>
</div>           
 
            </div>
<?php
endforeach;
$openAuctionQuery->closeCursor();
?>


</div>
</body>
</html>