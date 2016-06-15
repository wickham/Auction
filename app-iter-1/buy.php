<?php
require '/u/z/users/cs105/wickham/PHP/openZdatabase.php';


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
Signed in as, (User)<br/>
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
<br/>
<div class="red">
<h1>Current Auctions</h1>

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
        A.OPEN_TIME,
        A.CLOSE_TIME,
        C.NAME AS ITEM_CATEGORY,
        A.ITEM_CAPTION,
        A.ITEM_DESCRIPTION
        FROM AUCTION A
            JOIN ITEM_CATEGORY C ON A.ITEM_CATEGORY = C.ITEM_CATEGORY_ID
            JOIN PERSON P ON A.SELLER = P.PERSON_ID
        WHERE A.STATUS = 1;
    ');
	
$openAuctionQuery->execute();
foreach ($openAuctionQuery->fetchAll() as $auction):

?>
 
<br/>
<div class="items">
<h3><?=htmlspecialchars($auction['ITEM_CAPTION'])?></h3>
<img src="auctionPhoto.php?id=<?=htmlspecialchars($auction['AUCTION_ID'])?>" class="icons" />
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
</dl> 
<form method="post" action="bid.php?id=<?=htmlspecialchars($auction['AUCTION_ID'])?>">  
<input type="hidden" name="auctionId" value="<?=htmlspecialchars($auction['AUCTION_ID'])?>" />
<input class="button" type="submit" value="Bid"/>
</form>
</div>           
<?php
endforeach;
$openAuctionQuery->closeCursor();
?>


</div>
</body>
</html>