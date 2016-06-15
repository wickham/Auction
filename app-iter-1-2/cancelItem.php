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
        JOIN AUCTION_STATUS ON AUCTION_STATUS.AUCTION_STATUS_ID = AUCTION.STATUS
        JOIN ITEM_CATEGORY ON ITEM_CATEGORY.ITEM_CATEGORY_ID = AUCTION.ITEM_CATEGORY
        JOIN PERSON ON PERSON.PERSON_ID = AUCTION.SELLER
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

      <!-- Instructions for confirming cancellation -->
          <h2> <?= $_SESSION['userName'] ?>, you selected to cancel the listing, 
               <?=htmlspecialchars($thisAuction['ITEM_CAPTION'])?>, 
               from  Acme Auctions. </h2>

       <?php
         // determine whether reserve price has been met
            if($thisAuction['RESERVE_PRICE'] > $thisAuction['ITEM_PRICE']){ 
               $reserveCond = htmlspecialchars(" not met."); 
            }
            else{
               $reserveCond = htmlspecialchars(" met."); 
            }

            // Continue only if the auction is not closed
         $closed = 5;
         if($thisAuction['STATUS'] != $closed):
       ?>          
          <h2> Please verify the information below and confirm this cancellation. </h2>
          <br />
       
      <!-- Item info as entered by user -->
        <div class="itemWhole">
          <div class="itemProperties">
          <h4> Information for <?=htmlspecialchars($thisAuction['ITEM_CAPTION'])?></h4>
          <!-- Picture from http://www.lfrankjewelry.com/site/pave-ruby-studs.html -->    
              <img src="showPhoto.php?auctionId=<?=htmlspecialchars($thisAuction['AUCTION_ID'])?>" alt="<?=htmlspecialchars($thisAuction['ITEM_CAPTION'])?>"/> 
          <ul>
            <li> Category: <?=htmlspecialchars($thisAuction['CATEGORY'])?> </li>
            <li> Condition: <?=htmlspecialchars($thisAuction['ITEM_CONDITION'])?></li>
            <li> Current Price: $ <?=htmlspecialchars($thisAuction['ITEM_PRICE'])?>  </li>
            <li> Required increase in bid amount: $ <?=htmlspecialchars($thisAuction['INC_PRICE'])?> </li>
            <li> Reserve Price: $ <?=htmlspecialchars($thisAuction['RESERVE_PRICE'])?> 
              (Reserve price <?=htmlspecialchars($reserveCond)?> )</li>
            <li> Start Date-Time: <?=htmlspecialchars($thisAuction['OPEN_TIME'])?>  </li>
            <li> End Date-Time: <?=htmlspecialchars($thisAuction['CLOSE_TIME'])?>  </li>
            <li> Description: <?=htmlspecialchars($thisAuction['ITEM_DESCRIPTION'])?></li>
          </ul>   
        </div>
          <div class="buttons">
            <h3>
          <form method="post" action="cancelConfirm.php">  
            <input type="hidden" name="auctionId" value="<?=htmlspecialchars($thisAuction['AUCTION_ID'])?>" />
            <input type="submit" value=" Cancel Item Listing " />
          </form>
           </h3>
          <h3>
          <form method="post" action="myAccount.php">  
            <input type="submit" value=" Keep Item Listing " />
          </form>
        </h3>
          </div>
        </div>  

        <?php
          else: ?>
            <h2> Sorry, but this item has already been closed </h2>

        <?php
          endif;
        ?>

      <footer><h4> Have items that you wish to sell? <a href="login.php">Post an item for auction </a></h4></footer>
    </div>  
  </body>
</html>