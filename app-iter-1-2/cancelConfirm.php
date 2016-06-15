<?php
session_start();
require '/u/briana21/Desktop/CS105/openZdatabase.php';
$deleteQuery = $database->prepare('
    DELETE 
      FROM AUCTION 
      WHERE AUCTION_ID = :auctionId;  
    '); 

$thisAuctionId = $_REQUEST['auctionId'];
$deleteQuery->bindValue(':auctionId', $thisAuctionId);
$deleteQuery->execute();
$deleteQuery->closeCursor();
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

      <!-- Instructions for user -->
          <h2> <?= $_SESSION['userName'] ?>, the requested auction has been cancelled. </h2>

          <div class="buttons">
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