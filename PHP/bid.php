<?php
require '/u/z/users/cs105/wickham/PHP/openZdatabase.php';
$openAuctionQuery = $database->prepare('
    SELECT
        AUCTION.STATUS,
        CONCAT(PERSON.FORENAME, \' \', PERSON.SURNAME) AS SELLER,
        AUCTION.OPEN_TIME,
        AUCTION.CLOSE_TIME,
        ITEM_CATEGORY.NAME AS ITEM_CATEGORY,
        AUCTION.ITEM_CAPTION,
        AUCTION.ITEM_DESCRIPTION
        FROM AUCTION
            JOIN ITEM_CATEGORY ON AUCTION.ITEM_CATEGORY = ITEM_CATEGORY.ITEM_CATEGORY_ID
            JOIN PERSON ON AUCTION.SELLER = PERSON.PERSON_ID
        WHERE AUCTION.AUCTION_ID = :auctionId;
    ');
$thisAuctionId = $_REQUEST['id'];
$openAuctionQuery->bindValue(':auctionId', $thisAuctionId, PDO::PARAM_INT);
$openAuctionQuery->execute();
$thisAuction = $openAuctionQuery->fetch();
$openAuctionQuery->closeCursor();
?>
<!DOCTYPE html>
<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
  <head>
   <title>Listing 1 TITLE</title>
    <meta charset="utf-8" />
    <link href="firstStyle.css" type="text/css" rel="stylesheet" />
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

<div>
<a href="buy.php">Back</a>
</div>
<div>
<br/>
<h1> <?=htmlspecialchars($thisAuction['ITEM_CAPTION'])?></h1>
<img src="<?=htmlspecialchars($thisAuction['ITEM_PHOTO'])?>" alt="<?=htmlspecialchars($thisAuction['ITEM_CAPTION'])?>"/>
<dl>
      <dt><h3> Category: </h3></dt>
        <dd> <?=htmlspecialchars($thisAuction['CATEGORY'])?> </dd>
      <dt> <h3>Description: </h3> </dt>
        <dd> <?=htmlspecialchars($thisAuction['ITEM_DESCRIPTION'])?> </dd>
      <dt> <h3>Condition: </h3></dt>
        <dd> Excellent </dd>
      <dt><h3>Current Bid: </h3></dt>
        <dd> $ <?=htmlspecialchars($thisAuction['ITEM_PRICE'])?> </dd>
      <dt><h3>Minimum Bid: </h3></dt>
        <dd>$ 10.00</dd>
      <dt> <h3>Auction Started: </h3> </dt>
        <dd> <?=htmlspecialchars($thisAuction['OPEN_TIME'])?> </dd>        
      <dt><h3>Auction Ends: </h3></dt>
        <dd> <?=htmlspecialchars($thisAuction['CLOSE_TIME'])?> </dd>
    </dl>   

  <?php 
    if( $_SESSION['userName'] == $thisAuction['SELLER']):
  ?>    
Bid Ammount:<form> 
<input type="number" name="bid" /></form> <form action="bid_success.php" method="get">
<button class="button">Bid</button>
</form>
</div>


 <div> 
 <h3> The reserve price for this item is $<?=htmlspecialchars($thisAuction['RESERVE_PRICE'])?>! It has 
<?php if($thisAuction['RESERVE_PRICE'] > $thisAuction['ITEM_PRICE']){ echo "not"; }?> been reached! </h3>       
<h3>
          <form method="post" action="sellitem.php">
              <input type="hidden" name="auctionId" value="<?php $thisAuction['AUCTION_ID']?>" />
              <input type="submit" value=" Update Item"/>
          </form>        
</h3> 
<h3>
          <form method="post" action="close.php">
              <input type="hidden" name="auctionId" value="<?php $thisAuction['AUCTION_ID']?>" />
              <input type="submit" value=" Cancel listing "/>
          </form>
        </h3> 
    </div> 
   <?php 
      endif;
      if($_SESSION['userName'] != $thisAuction['SELLER']):
    ?>
    <dl>
            <dt> <h3>Seller: </h3></dt>
        <dd><?=htmlspecialchars($thisAuction['SELLER'])?></dd>
    </dl>    

    <h4><a href="searchUser.html"> View <?=htmlspecialchars($thisAuction['SELLER'])?>'s other listings</a></h4>  
 
 <div id="bid"> 
    <h3>Bid on Item</h3> 
        <?php 
          if ($_SESSION['userName'] != NULL) : 
        ?>
        <form method="post" action="bidConfirm.php">
          Amount: $ <input type="number" min="1" value="1" name="newBid" />
          <input type="hidden" name="auctionId" value="<?=htmlspecialchars($thisAuction['AUCTION_ID'])?>" />
          <input type="submit" value=" Submit "/>
        </form>
     
        <?php endif; 
          if($_SESSION['userName'] == NULL):
        ?> 
        <h2>Only registered users can place bids.<br/><a href="login.php">Log in</a> to place bids and buy items! </h2>
        <?php endif; endif;  ?> 
     </div>
</body>
</html>