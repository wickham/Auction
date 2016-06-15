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

<!-- Instructions for bid submission -->
    <h3> <?= $_SESSION['userName'] ?>, please verify the purchasing information for the item <?=htmlspecialchars($thisAuction['ITEM_CAPTION'])?>. </h3>
    <br />

<!-- Credit card information that was entered-->
  <h2> Credit Card information: </h2>
    <ul>
      <li>Card Type: <?=$_POST['card']?> </li>
      <li>Name on Card: <?=$_POST['cardName']?> </li>
      <li>Card Number: <?=$_POST['cardNumber']?> </li>
      <li>Expiration Date: <?=$_POST['cardExpirationDate']?> </li>
    </ul>

<!-- Shipping information that was entered-->
  <h2> Shipping Address: </h2>
    <ul>
      <li><?=$_POST['shipName']?> </li>
      <li><?=$_POST['shipAddress']?> </li>
      <li><?=$_POST['shipCity']?> , <?=$_POST['shipState']?></li>
      <li><?=$_POST['shipZip']?></li>
    </ul>    

    <h3><a href="myAccount.php"> Confirm purchase </a></h3>
    <h3><a href="myAccount.php"> Cancel Purchase </a></h3>

      <footer><h4> Have items that you wish to sell? <a href="login.php">Post an item for auction </a></h4></footer>
    </div>  
  </body>
</html>