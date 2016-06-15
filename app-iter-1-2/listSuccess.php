<?php
session_start();
require '/u/briana21/Desktop/CS105/openZdatabase.php';
$newIdQuery = $database->prepare('SELECT NEXT_SEQ_VALUE(:seqGenName);');
$newIdQuery->bindValue(':seqGenName', 'AUCTION');
$newIdQuery->execute();
$newAuctionId = $newIdQuery->fetchColumn(0);
$newIdQuery->closeCursor();

$insertAuctionStmt = $database->prepare('
    INSERT INTO AUCTION
        ( AUCTION_ID,STATUS,
          ITEM_CONDITION, 
          SELLER, OPEN_TIME,
          CLOSE_TIME,
          ITEM_PRICE,
          INC_PRICE,
          RESERVE_PRICE,
          ITEM_CATEGORY ,
          ITEM_CAPTION ,
          ITEM_DESCRIPTION,
          ITEM_PHOTO)
        VALUES (:aucId, :stat, :condit, :seller, CURRENT_TIMESTAMP, :close, :price, :inc, :reserve, :cate, :capt, :info, :photo);
    ');
$insertAuctionStmt->bindValue(':aucId', $newAuctionId);
$insertAuctionStmt->bindValue(':stat', "1");
$insertAuctionStmt->bindValue(':condit', $_POST['itemCondition']);
$insertAuctionStmt->bindValue(':seller', "1");

// Split time and confirm correctness
list($hr, $mn, $sc) = explode(":", $_REQUEST['auctionEndTime']); // (hour:minute:second) 
list($yr, $mn, $dy) = explode("-", $_REQUEST['auctionEndDate']);// (yyyy-mm-dd)
if( ($hr != NULL) && ($mn != NULL) && ($sc != NULL) && ($yr != NULL) && ($mn != NULL) && ($dy != NULL)
   ){ // if all of the values were entered with the correct delimeter
      $closeT = htmlspecialchars($_REQUEST['auctionEndTime'].$_REQUEST['auctionEndDate']);
      $hour = $hr;
      $min = $mn;
      $sec = $sc;
      $year = $yr; 
      $mon = $mn; 
      $day = $dy;    
}
else{
  $closeT = date(DATE_ATOM, mktime(1, 2, 1, 7, 1, 2014)); // Sets default close time to future date
}

$insertAuctionStmt->bindValue(':close', $closeT);
$insertAuctionStmt->bindValue(':price', $_REQUEST['startPrice']);
$insertAuctionStmt->bindValue(':inc', $_REQUEST['bidInc']);
$insertAuctionStmt->bindValue(':reserve', $_REQUEST['reservePrice']);
if($_REQUEST['category'] == "clothing"){
   $category = 1;
}
else if($_REQUEST['category'] == "furniture"){
   $category = 2;
}
else{
   $category = 3;
}
$insertAuctionStmt->bindValue(':cate', $category);
$insertAuctionStmt->bindValue(':capt', $_REQUEST['itemName']);
$insertAuctionStmt->bindValue(':info', $_REQUEST['itemInfo']);

// error check file
if(isset($_FILES['itemPic']) ){
   $photoFile = fopen($_FILES['itemPic']['tmp_name'], 'rb');
}
else{
     $photoFile =  file_get_contents("images/sweater.jpg");
}
$insertAuctionStmt->bindValue(':photo', $photoFile, PDO::PARAM_LOB);
$insertAuctionStmt->execute();
$insertAuctionStmt->closeCursor();    
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

      <!-- Instructions for confirming submission -->
          <h3> <?= $_SESSION['userName'] ?>, you submitted the item, <?=$_POST['itemName']?>, for listing on Acme Auctions. </h3>
          <h3> Please verify the information below and click continue to list your item. </h3>
          <h3> If you do not want to list this item, please hit cancel. </h3>
          <br />
       
      <!-- Item info as entered by user -->
          <h2> Information for <?=$_POST['itemName']?></h2>
          <div class="itemWhole">
           
              
            <img src="showPhoto.php?auctionId=<?php htmlspecialchars($newAuctionId)?>" alt="<?=$_POST['itemName']?>"/>

       <ul>
            <li> Name: <?=$_POST['itemName']?>  <!-- <span class="edit"> Edit </span> --> </li>
            <li> Category: <?=$_POST['category']?>   <!-- <span class="edit"> Edit </span> --> </li> 
            <li> Condition: <?=$_POST['itemCondition']?>   <!-- <span class="edit"> Edit </span> --> </li>
            <li> Starting Price: $ <?=$_POST['startPrice']?>   <!-- <span class="edit"> Edit </span>  --></li>
            <li> Required increase in bid amount: $ <?=$_POST['bidInc']?>   <!-- <span class="edit"> Edit </span> --> </li> 
            <li> Reserve Price: $ <?=$_POST['reservePrice']?>    <!-- <span class="edit"> Edit </span> --> </li>
            <li> Description: <?=$_POST['itemInfo']?> </li> 
            <?php
               // make sure that user did not enter crazy numbers for the date and time
               if(($hour < 25 && $hour >= 0) && ($min >= 2 && min < 60) && ($sec >= 0 && $sec < 61) && 
                  ($year >= 2014) && ($mon < 13 && $mon >= 1) && ($day >= 1 && $day < 32)):
            ?>
               <li> End Date-Time: <?=$_POST['auctionEndDate']?> , <?=$_POST['auctionEndTime']?> <!-- <span class="edit"> Edit </span> --> </li> 
            <?php 
               else:
            ?>  
               <li> Sorry, but you entered an invalid date/time. Please update this item or it will be assigned a default close time.
                    Note: Auctions must be at least 2 MINUTES.</li> 
            <?php
                endif;
            ?> 
          </ul>    
      <!--     <span class="edit"> Change Picture </span> 
       --> 
          <br /> 

          <div class="buttons">
              <!-- List item on website - return to main accounts page -->
            <h3>
             <form method="post" action="myAccount.php">
                <input type="submit" value=" List Item "/>
              </form>  
            </h3>  
           <!-- Update Listing Information -->
            <h3>
              <form method="post" action="updateItem.php">
                  <input type="hidden" name="auctionId" value="<?=htmlspecialchars($newAuctionId)?>" />
                  <input type="submit" value=" Update Item information "/>
              </form>
            </h3> 
              <!-- Cancel listing -->
            <h3>
             <form method="post" action="cancelItem.php">
                <input type="hidden" name="auctionId" value="<?=htmlspecialchars($newAuctionId)?>" />
                <input type="submit" value=" Cancel Item Listing "/>
              </form>  
            </h3>  
          </div>
       </div> 

      <footer><h4> Have items that you wish to sell? <a href="login.php">Post an item for auction </a></h4></footer>
    </div>  
  </body>
</html>