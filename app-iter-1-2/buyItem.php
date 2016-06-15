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

<!-- Instructions for bid submission -->
    <h3> <?= $_SESSION['userName'] ?>, you are the highest bidder on the listing, <?=htmlspecialchars($thisAuction['ITEM_CAPTION'])?>, 
      from  Acme Auctions. </h3>
    <h3> Please enter your purchasing information below. </h3>
    <br />

<!-- Item that is being purchased -->
    <div class="item">
      <img src="showPhoto.php?auctionId=<?=htmlspecialchars($thisAuction['ITEM_PHOTO'])?>" alt="<?=htmlspecialchars($thisAuction['ITEM_CAPTION'])?>" /> 
       <dl>
            <dt> Item Won by <?= $_SESSION['userName'] ?> </dt>
              <dd> <?=htmlspecialchars($thisAuction['ITEM_CAPTION'])?></dd>
            <dt> Winning Price </dt>  
              <dd> $ <?=htmlspecialchars($thisAuction['ITEM_PRICE'])?> </dd>
            <dt> Auction End Date-Time </dt>  
              <dd> <?=htmlspecialchars($thisAuction['CLOSE_TIME'])?>  </dd>
           </dl> 
    </div>         

<!-- Credit card information -->
  <h3> Please Enter your Credit Card information: </h3>
    <form method="post" action="buyConfirm.php">
      <ul>
        <li>Card Type: 
        <select name="card">
           <option value="mastercard"> Mastercard</option>
           <option value="visa"> Visa</option>
           <option value="discover"> Discover</option>
           <option value="amerexpress"> American Express</option>
        </select></li>
        <li>Name on Card: <input type="text" name="cardName" /></li>
        <li>Card Number: <input type="text" name="cardNumber" /></li>
        <li>Expiration Date: <input type="text" name="cardExpirationDate" /></li>
      </ul>

<!-- Shipping information -->
  <h3> Please Enter your shipping information: </h3>
      <ul>
        <li>Name: <input type="text" name="shipName" /> </li>
        <li>Address: <input type="text" name="shipAddress" /></li>
       <li> City: <input type="text" name="shipCity" /></li>
        <li>State: <input type="text" name="shipState" /></li>
        <li>Zip Code: <input type="text" name="shipZip" /></li>
      </ul>
      <input type="hidden" name="auctionId" value="<?=htmlspecialchars($thisAuction['AUCTION_ID'])?>" />
      <input type="submit" value=" Submit Purchase Information "/>
    </form> 
    <br /> 

      <footer><h4> Have items that you wish to sell? <a href="login.php">Post an item for auction </a></h4></footer>
    </div>  
  </body>
</html>