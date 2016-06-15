<?php
session_start();
require '/u/briana21/Desktop/CS105/openZdatabase.php';
$openAuctionQuery = $database->prepare('
    SELECT
        AUCTION.AUCTION_ID,
        CONCAT(PERSON.FORENAME, \' \', PERSON.SURNAME) AS SELLER,
        AUCTION.ITEM_CATEGORY,
        ITEM_CATEGORY.NAME AS CATEGORY,
        AUCTION_STATUS.NAME AS STATNAME,
        AUCTION.STATUS,
        AUCTION.ITEM_CONDITION,
        AUCTION.ITEM_CAPTION,
        AUCTION.ITEM_PRICE,
        AUCTION.INC_PRICE,
        AUCTION.RESERVE_PRICE,
        AUCTION.OPEN_TIME,
        AUCTION.CLOSE_TIME,
        AUCTION.ITEM_DESCRIPTION,
        AUCTION.ITEM_PHOTO
        FROM AUCTION
           JOIN AUCTION_STATUS ON AUCTION.STATUS = AUCTION_STATUS.AUCTION_STATUS_ID
           JOIN ITEM_CATEGORY ON AUCTION.ITEM_CATEGORY = ITEM_CATEGORY.ITEM_CATEGORY_ID
           JOIN PERSON ON AUCTION.SELLER = PERSON.PERSON_ID
        WHERE AUCTION.AUCTION_ID = :auctionId;   
    '); 
$thisAuctionId = $_REQUEST['auctionId'];
$openAuctionQuery->bindValue(':auctionId', $thisAuctionId, PDO::PARAM_INT);
$openAuctionQuery->execute();
$thisAuction = $openAuctionQuery->fetch();
$openAuctionQuery->closeCursor();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
  <head>
    <title>Acme Auctions</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="style.css"/>
    <link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'/>
    <link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'/>
  </head>
  <body>
    <div id="page">

<!-- Header (Title and Search Bar) -->
  <header>
    <h1> Acme Auctions </h1>

 <!-- Search bar -->
    <form action="searchResults.php" method="get" id="searchBar">
      <input type="text" name="searchName" placeholder="Type here..."/>
      <!-- Submit button for searching -->
      <input type="submit" value="Search" name="searchButton"/>
    </form>
  </header>       

<!-- Login and Register for the site -->
  <div id="userStatus">
<!-- Logout button -->
    <div id="logout"><a href="index.php"> Logout </a></div>
<!-- User Account Information -->
    <div id="userHome"><a href="myAccount.php"> My Auctions </a></div>
  </div> 

<!-- List of main pages -->
  <nav>
    <ul>
      <li><a href="index.php"> Home </a></li>
      <li><a href="auctions.php"> All Auction Listings </a> </li>
      <li><a href="clothing.php"> Clothing </a></li>
      <li><a href="jewelry.php"> Jewelry </a></li>
      <li><a href="furniture.php"> Furniture </a></li>
      <li><a href="advSearch.php"> Advanced Search </a></li>
    </ul>
  </nav>  

<!-- Item info as entered by user -->
  <div class="itemWhole">  
    <h2> <?=htmlspecialchars($thisAuction['ITEM_CAPTION'])?></h2>
    <img src="showPhoto.php?auctionId=<?=htmlspecialchars($thisAuction['ITEM_PHOTO'])?>" alt="<?=htmlspecialchars($thisAuction['ITEM_CAPTION'])?>"/> 
    <dl>
      <dt> Category </dt>
        <dd> <?=htmlspecialchars($thisAuction['CATEGORY'])?> </dd>
      <dt> Condition </dt>
        <dd> <?=htmlspecialchars($thisAuction['ITEM_CONDITION'])?> </dd>
      <dt> Current Price </dt>
        <dd> $ <?=htmlspecialchars($thisAuction['ITEM_PRICE'])?> </dd>
      <dt> Required increase in price </dt>
        <dd>$ <?=htmlspecialchars($thisAuction['INC_PRICE'])?></dd>
      <dt> Auction Start Date-Time </dt>
        <dd> <?=htmlspecialchars($thisAuction['OPEN_TIME'])?> </dd>        
      <dt> End Date of Auction </dt>
        <dd> <?=htmlspecialchars($thisAuction['CLOSE_TIME'])?> </dd>
      <dt> Description </dt>
        <dd> <?=htmlspecialchars($thisAuction['ITEM_DESCRIPTION'])?> </dd>
    </dl>   

  <?php 
    if( $_SESSION['userName'] == $thisAuction['SELLER']):
  ?>    
    <h4> The reserve price for this item is $<?=htmlspecialchars($thisAuction['RESERVE_PRICE'])?>. It has 
      <?php if($thisAuction['RESERVE_PRICE'] > $thisAuction['ITEM_PRICE']){ echo "not"; }?> been met. </h4> 
        <h3>
          <form method="post" action="updateItem.php">
              <input type="hidden" name="auctionId" value="<?php $thisAuction['AUCTION_ID']?>" />
              <input type="submit" value=" Update Item information "/>
          </form>
        </h3> 
        <h3>
          <form method="post" action="cancelItem.php">
              <input type="hidden" name="auctionId" value="<?php $thisAuction['AUCTION_ID']?>" />
              <input type="submit" value=" Cancel this listing "/>
          </form>
        </h3> 
    </div> 
   <?php 
      endif;
      if($_SESSION['userName'] != $thisAuction['SELLER'] && $_SESSION['userName'] != NULL):
    ?>
    <dl>
      <dt> Added By </dt>
        <dd><?=htmlspecialchars($thisAuction['SELLER'])?></dd>
    </dl>    
   <form method="get" action="searchResults.php">
    <input type="hidden" name="searchName" value="<?=htmlspecialchars($thisAuction['SELLER'])?>" />        
    <h4><input type="submit" value="View other submissions from <?=htmlspecialchars($thisAuction['SELLER'])?>"/></h4>  
   </form> 
 

    <div id="bidSection"> 
    <h3> Bid on Item </h3> 
        <?php 
          if ($_SESSION['userName'] != NULL) : 
        ?>
        <form method="post" action="bidConfirm.php">
    <!--       <p> Note: This bid must be greater than the previous bid </p> -->
          Amount: $ <input type="number" min="1" value="1" name="newBid" />
          <input type="hidden" name="auctionId" value="<?=htmlspecialchars($thisAuction['AUCTION_ID'])?>" />
          <input type="submit" value=" Submit "/>
        </form>
     
        <?php endif; 
          if($_SESSION['userName'] == NULL):
        ?> 
          <h4> Sorry, only valid users can place bids. Please <a href="login.php">log in</a> to participate in this auction </h4>
        <?php endif; endif;  ?> 
     </div>
    <br />

      <footer><h4> Have items that you wish to sell? <a href="login.php">Post an item for auction </a></h4></footer>
    </div>  
  </body>
</html>