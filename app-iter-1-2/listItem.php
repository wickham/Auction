<?php
session_start();
require '/u/briana21/Desktop/CS105/openZdatabase.php';
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

    <!-- Item Details Form -->
        <h2> Item Details </h2>
          <form method="post" action="listSuccess.php" enctype="multipart/form-data">
            Name: <input type="text" name="itemName" required="required"/>
            <br />
            Category: 
              <input type="radio" name="category" value="clothing" checked/> Clothing
              <input type="radio" name="category" value="furniture" /> Furniture
              <input type="radio" name="category" value="jewelry" /> Jewelry
            <br />
            Condition: <input type="text" name="itemCondition" required="required"/> 
            <br />
            Starting Price: $ <input type="number" name="startPrice" min="1" value="1" required="required"/> 
            <br />
            Required Incease in Bid Amount: $ <input type="number" name="bidInc" min="1" value="1" required="required"/> 
            <h5> Note: The reserve price is the lowest amount that you will sell the item for. This price is hidden from potential buyers. If this price is not
            met by the end of the auction, you have the right to refuse selling the item. </h5>
            Reserve Price: $ <input type="number" name="reservePrice" value="10" required="required"/>
            <h5> Note: Auctions must last for at least two minutes. </h5> <!-- Format: 2014-02-11 22:24:50 -->
            End Date-Time of Auction: Date(year-month-day) <input type="text" name="auctionEndDate" value="yyyy-mm-dd"required="required"/> 
            Time(hour:minute:second) <input type="text" name="auctionEndTime" value="hr:min:sec" required="required"/>
            <br />
            Image of Item: <input type="file" name="itemPic" accept="image/*" required="required"/>
            <h5> Note: Please use 256 characters or less. </h5>
            Description: 
            <br />
            <textarea name="itemInfo" rows="4" cols="40" required="required"> </textarea>
            <br />
            <!-- Submit button stores information and reset button clears all entry fields -->
            <input type="reset" value="Reset" />
            <input type="submit" value="Submit Item" name="submit"/>
          </form>  

      <footer><h4> Have items that you wish to sell? <a href="login.php">Post an item for auction </a></h4></footer>
    </div>  
  </body>
</html>