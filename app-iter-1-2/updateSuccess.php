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
$openAuctionQuery->bindValue(':auctionId', $thisAuctionId);
$openAuctionQuery->execute();
$thisAuction = $openAuctionQuery->fetch();
$openAuctionQuery->closeCursor();

$updateQuery = $database->prepare('
    UPDATE AUCTION 
      SET 
       ITEM_CAPTION = :itemName,
       ITEM_CONDITION = :bidInc, 
       ITEM_PRICE = :itemPrice, 
       INC_PRICE = :bidInc,
       CLOSE_TIME = :closeTime,
       RESERVE_PRICE = :reservePrice,
       ITEM_PHOTO = :photo, 
       ITEM_DESCRIPTION = :itemInfo
      WHERE AUCTION_ID = :auctionId;  
    '); 
$updateAuctionId = $_REQUEST['auctionId'];
$updateQuery->bindValue(':auctionId', $thisAuctionId);
if (isset ( $_REQUEST['itemName'] ) ){
  $updateName = $_REQUEST['itemName'] ;
}
else{
  $updateName = $thisAuction['ITEM_CAPTION'];
}
$updateQuery->bindValue(':itemName', $updateName);

if (isset ( $_REQUEST['itemCondition'] ) ){
  $itemCondition = $_REQUEST['itemCondition'] ;
}
else{
  $itemCondition = $thisAuction['ITEM_CONDITION'];
}
$updateQuery->bindValue(':bidInc', $itemCondition);


if (isset ( $_REQUEST['startPrice'] ) ){
  $price = $_REQUEST['startPrice'] ;
}
else{
  $price = $thisAuction['ITEM_PRICE'];
}
$updateQuery->bindValue(':itemPrice', $price);

if (isset($_POST['bidInc'])){
  $incPrice = $_POST['bidInc'];
}
else{
  $incPrice = $thisAuction['bidInc'];
}
$updateQuery->bindValue(':bidInc', $incPrice);

if (isset ( $_REQUEST['reservePrice'] ) ){
  $reserve = $_REQUEST['reservePrice'] ;
}
else{
  $reserve = $thisAuction['RESERVE_PRICE'];
}
$updateQuery->bindValue(':reservePrice', $reserve);

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
$updateQuery->bindValue(':closeTime', $closeT);

if (isset ( $_REQUEST['itemInfo'] )){
  $desc = $_REQUEST['itemInfo'] ;
}
else{
  $desc = $thisAuction['ITEM_DESCRIPTION'];
}
$updateQuery->bindValue(':itemInfo', $desc);

// error check file
 if(isset($_FILES['itemPic']) || $thisAuction['itemInfo'] != NULL){
    $photoFile = fopen($_FILES['itemPic']['tmp_name'], 'rb');
 }
 else{
     $photoFile =  file_get_contents("images/sweater.jpg");
 }

$updateQuery->bindValue(':photo', $photoFile, PDO::PARAM_LOB);
$updateQuery->execute();
$updateQuery->closeCursor();
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

      <!-- Instructions for updating listing -->
          <h2> <?= $_SESSION['userName'] ?>, you have updated the item, <?=$updateName?>. </h2>

          <div class="itemWhole">
              <h4> <?=$updateName?> </h4>
                <img src="showPhoto.php?auctionId=<?=htmlspecialchars($thisAuctionId)?>" alt="<?php $updateName?>"/> 
                  <dl>
                    <dt> Category </dt>  
                      <dd> <?=htmlspecialchars($thisAuction['CATEGORY'])?> </dd>
                    <dt> Auction status </dt>  
                      <dd> <?=htmlspecialchars($thisAuction['STATNAME'])?>  </dd>
                    <dt> Condition </dt>  
                      <dd> <?=htmlspecialchars($itemCondition)?> </dd>
                    <dt> Current Price </dt>  
                      <dd> $ <?=$price?> </dd>
                    <dt> Required increase in Price  </dt>  
                      <dd> $ <?=htmlspecialchars($bidIncrement)?> </dd>
                    <dt> Auction Start Date-Time</dt>  
                      <dd> <?=htmlspecialchars($thisAuction['OPEN_TIME'])?> </dd> 
                    <dt> Description </dt>  
                      <dd> <?=$desc?></dd>     
                    <?php
                       // make sure that user did not enter crazy numbers for the date and time
                       if(($hour < 25 && $hour >= 0) && ($min >= 2 && min < 60) && ($sec >= 0 && $sec < 61) && 
                          ($year >= 2014) && ($mon < 13 && $mon >= 1) && ($day >= 1 && $day < 32)):
                    ?>
                        <dt> Auction End Date-Time </dt>  
                          <dd> <?=htmlspecialchars($thisAuction['CLOSE_TIME'])?>   </dd>
                    <?php 
                       else:
                    ?>  
                       <li> Sorry, but you entered an invalid date/time. Please update this item or it will be assigned a default close time.
                            Note: Auctions must be at least 2 MINUTES.</li> 
\                     
                    <?php
                        endif;
                    ?>                                    
             
                   </dl>  
                 <h4> The reserve price for this item is $<?=$reserve?>. It has
                      <?php if($reserve > $price){ echo "not"; }?> been met. </h4> 
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