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
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en"><head>
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

      <?php
            // Continue only if the auction is not closed
            $closed = 5;
            if($thisAuction['STATUS'] != $closed):
        ?>          

      <!-- Instructions for updating listing -->
          <h2> <?= $_SESSION['userName'] ?>, please update the information below and press submit to confirm. Only enter the information that
            you wish to change. </h2>

      <!-- Item Details Form -->
          <h3> Item Details </h3>
            <form method="post" action="updateSuccess.php" enctype="multipart/form-data">
              <ul>
                 <li>Name: <input type="text" name="itemName" value="<?=htmlspecialchars($thisAuction['ITEM_CAPTION'])?>"/></li>
                 <li>Image of Item: <input type="file" name="itemPic" accept="image/jpeg"/> </li>
                 <li>Category: <?=htmlspecialchars($thisAuction['CATEGORY'])?></li>
                 <li>Condition: <input type="text" name="itemCondition" value="<?=htmlspecialchars($thisAuction['ITEM_CONDITION'])?>"/> </li>
                 <li>Starting Price: $ <input type="number" name="startPrice" min="1" value="<?=htmlspecialchars($thisAuction['ITEM_PRICE'])?>"/> </li>
                 <li>Required Incease in Bid Amount: $ <input type="number" name="bidInc" min="1" value="1" value="<?=htmlspecialchars($thisAuction['INC_PRICE'])?>"/>  </li>
                 <li><h5> Note: The reserve price is the lowest amount that you will sell the item for. This price is hidden from potential buyers. If this price is not
                            met by the end of the auction, you have the right to refuse selling the item. </h5></li>
                 <li> Reserve Price: $ <input type="number" name="reservePrice" value="<?=htmlspecialchars($thisAuction['RESERVE_PRICE'])?>"/></li>
                 <li><h5> Note: Auctions must last for at least two minutes. </h5></li>
                 <li> Start Time: <?=htmlspecialchars($thisAuction['OPEN_TIME'])?></li>
                 <li> Current End: <?=htmlspecialchars($thisAuction['CLOSE_TIME'])?></li>
                 <li>End Date-Time of Auction: Date(yyyy-mm-dd) <input type="text" name="auctionEndDate" /> </li>
                 <li>Time(hour:minute:second) <input type="text" name="auctionEndTime" /></li>
                 <li><h5> Note: Please use 256 characters or less. </h5></li>
                 <li>Description: 
                     <br />
                     <textarea name="itemInfo" value="<?=htmlspecialchars($thisAuction['ITEM_DESCRIPTION'])?>" 
                      rows="4" cols="40"> </textarea></li>
              </ul>
              <input type="reset" value="Reset" />
              <input type="hidden" name="auctionId" value="<?=htmlspecialchars($thisAuction['AUCTION_ID'])?>" />
              <input type="submit" value=" Submit "/>

            </form>  
            <form method="post" action="myAccount.php">
               <input type="submit" value=" Cancel Update "/>
            </form>  
        <?php 
          else: ?>
          <h2> Sorry, but this item has already been closed. It can no longer be updated. </h2>
        <?php   
          endif;
        ?>

      <footer><h4> Have items that you wish to sell? <a href="login.php">Post an item for auction </a></h4></footer>
    </div>  
  </body>
</html>