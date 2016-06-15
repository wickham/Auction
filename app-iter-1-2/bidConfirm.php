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

$newBidQuery = $database->prepare('SELECT NEXT_SEQ_VALUE(:seqGenName);');
$newBidQuery->bindValue(':seqGenName', 'BID');
$newBidQuery->execute();
$newBidId = $newBidQuery->fetchColumn(0);
$newBidQuery->closeCursor();


$insertAuctionStmt = $database->prepare('
    INSERT INTO BID
        (  BID_ID ,
           BIDDER ,
           AUCTION,
           BID_TIME,
           AMOUNT)
        VALUES (:bidId, :bidder, :auction, :bidTime, :amount);
    ');
$insertAuctionStmt->bindValue(':bidId', $newBidId);
$insertAuctionStmt->bindValue(':bidder', "1");
$insertAuctionStmt->bindValue(':auction', $thisAuctionId);
$insertAuctionStmt->bindValue(':bidTime', strftime("%Y. %B %d. %A. %X %Z"));
$insertAuctionStmt->bindValue(':amount', $_REQUEST['newBid']);
$insertAuctionStmt->execute();
$insertAuctionStmt->closeCursor();    

$updateQuery = $database->prepare('
    UPDATE AUCTION 
      SET  
       ITEM_PRICE = :itemPrice
      WHERE AUCTION_ID = :auctionId;  
    '); 
$updateAuctionId = $_REQUEST['auctionId'];
$updateQuery->bindValue(':auctionId', $thisAuctionId);
if($_REQUEST['newBid'] > $thisAuction['RESERVE_PRICE'] && $_REQUEST['newBid'] > ($thisAuction['ITEM_PRICE'] + $thisAuction['INC_PRICE']) ){
   $updatePrice = $_REQUEST['newBid'] ;
}
else{
  $updatePrice = $thisAuction['ITEM_PRICE'];
}
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

  <div id="userStatus">
<!-- Logout button -->
    <div id="logout"><a href="index.php"> Logout </a></div>
<!-- User Account Information -->
    <div id="userHome"><a href="userHome.php"> My Auctions </a></div>
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
      $thisAuctionOldPrice = $thisAuction['ITEM_PRICE'];
    ?>  

<!-- Instructions for bid submission -->
    <h3> <?= $_SESSION['userName'] ?>, you decided to bid $ <?=$_REQUEST['newBid']?> on the listing,
       <?=$thisAuction['ITEM_CAPTION']?>, from  Acme Auctions. </h3>
<?php
   $closed = 5;
   if($thisAuction['STATUS'] == $closed){
      echo "Sorry, but this bid has already closed for bidding.";
      exit;
   }  
?>  
  <?php
     if($_REQUEST['newBid'] > $thisAuction['RESERVE_PRICE'] && $_REQUEST['newBid'] > ($thisAuction['ITEM_PRICE'] + $thisAuction['INC_PRICE'])): 
  ?>

    <h3> Please verify the information below and confirm this bid. </h3>
    <br />

<!-- Item info as entered by user -->
 <div class="itemWhole">
    <h2> <?=$thisAuction['ITEM_CAPTION']?></h2>
    <img src="showPhoto.php?auctionId=<?=htmlspecialchars($thisAuction['ITEM_PHOTO'])?>" alt="<?=$thisAuction['ITEM_CAPTION']?>"/>
    <dl>
      <dt> Category </dt>
        <dd> <?=$thisAuction['CATEGORY']?> </dd>
      <dt> Condition </dt>
        <dd> <?=$thisAuction['ITEM_CONDITION']?> </dd>
      <dt> Current Price </dt>
        <dd> $ <?=$thisAuction['ITEM_PRICE']?> </dd>
      <dt> Required increase in price </dt>
        <dd>$ <?=htmlspecialchars($thisAuction['INC_PRICE'])?></dd>
      <dt> Start Date of Auction </dt>
        <dd> <?=$thisAuction['OPEN_TIME']?> </dd>
      <dt> End Date of Auction </dt>
        <dd> <?=$thisAuction['CLOSE_TIME']?> </dd>
      <dt> Description </dt>
        <dd> <?=$thisAuction['ITEM_DESCRIPTION']?> </dd>
      <?php 
        if($_SESSION['userName'] != NULL): ?>  
      <dt> Added By </dt>
        <dd><?=$thisAuction['SELLER']?></dd>
      <?php endif;?>  
    </dl>  

    <div id="bidSection"> 
        
       <form method="post" action="itemPage.php">  
        <h4> Your Bid: $<?=$_REQUEST['newBid']?> </h4>
        <input type="hidden" name="auctionId" value="<?=htmlspecialchars($thisAuction['AUCTION_ID'])?>" />        
        <h3><input type="submit" value=" Confirm Bid "/></h3> 
       </form>
       <form method="post" action="bidCancel.php">  
        <input type="hidden" name="auctionId" value="<?=htmlspecialchars($thisAuction['AUCTION_ID'])?>" />   
         <input type="hidden" name="oldPrice" value="<?=htmlspecialchars($thisAuctionOldPrice)?>" />     
         <input type="hidden" name="bidId" value="<?php $newBidId ?>" />
        <h3><input type="submit" value=" Cancel Bid "/></h3> 
       </form>     
     </div>
  </div>  


  <?php endif;
    if($_REQUEST['newBid'] <= $thisAuction['RESERVE_PRICE'] || $_REQUEST['newBid'] <= ($thisAuction['ITEM_PRICE'] + $thisAuction['INC_PRICE'])): 
  ?>
      <h2> Sorry, but your bidding amount was not high enough for this auction. Please enter a new amount. </h2>
    <div id="bidSection"> 
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
        <?php endif;?> 
     </div>
    <br />
     

 <?php endif;?>

      <footer><h4> Have items that you wish to sell? <a href="login.php">Post an item for auction </a></h4></footer>
    </div>  
  </body>
</html>