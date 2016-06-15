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
        AUCTION.ITEM_CAPTION,
        AUCTION.ITEM_PRICE,
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

$deleteQuery = $database->prepare('
    DELETE 
      FROM BID 
      WHERE BID_ID = :bidId;  
    '); 
$thisAuctionId = $_REQUEST['auctionId'];
$thisBidId = $_REQUEST['bidId'];
$deleteQuery->bindValue(':bidId', $thisBidId);
$deleteQuery->execute();
$deleteQuery->closeCursor();

$updateQuery = $database->prepare('
    UPDATE AUCTION 
      SET  
       ITEM_PRICE = :itemPrice
      WHERE AUCTION_ID = :auctionId;  
    '); 
$updateAuctionId = $_REQUEST['auctionId'];
$updateQuery->bindValue(':auctionId', $thisAuctionId);
$updatePrice = $_REQUEST['oldPrice'] ;
$updateQuery->bindValue(':itemPrice', $updatePrice);
$updateQuery->execute();
$updateQuery->closeCursor();

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

<!-- Instructions for updating listing -->
    <h3> <?= $_SESSION['userName'] ?>, you have cancelled your bid. The price is now $<?=$updatePrice?>. </h3>


        <!-- Return to item page -->
      <h3>
       <form method="post" action="itemPage.php">
          <input type="hidden" name="auctionId" value="<?=htmlspecialchars($thisAuction['AUCTION_ID'])?>" />        
          <input type="submit" value=" Return to Item "/>
        </form>  
      </h3>  
        <!-- Return to main account page -->
      <h3>
       <form method="post" action="myAccount.php">
          <input type="submit" value=" Return to My Account "/>
        </form>  
      </h3>  
    </div>



      <footer><h4> Have items that you wish to sell? <a href="login.php">Post an item for auction </a></h4></footer>
    </div>  
  </body>
</html>