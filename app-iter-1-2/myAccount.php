<?php
session_start();
require '/u/briana21/Desktop/CS105/openZdatabase.php';
$userQuery = $database->prepare('
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
        WHERE AUCTION.SELLER = 1;   
    ');
$userQuery->execute();
$userData = $userQuery->fetchAll();
$userQuery->closeCursor();  
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

        <h2> Welcome, <?= $_SESSION['userName'] ?></h2><br />

         <!-- User Options Quick bar -->
        <nav id="userQuickBar">
            <h4>
             <form method="get" action="listItem.php">
    <!--             <input type="hidden" name="sellerName" value="1" />
     -->            <input type="submit" value="List an item"/>
             </form>
            </h4>     
          <?php 
            $wonItem = 3;

            // For every won auction, make a payment button
             foreach ($userData as $currItem):
               if($currItem['STATUS'] == $wonItem): 
          ?>
           <h4> 
             <form method="post" action="buyItem.php">
                <input type="hidden" name="auctionId" value="<?=htmlspecialchars($currItem['AUCTION_ID'])?>" />
                <input type="submit" value="Pay for purchase of <?=htmlspecialchars($currItem['ITEM_CAPTION'])?>"/>
             </form>
           </h4>     
          <?php 
              endif;  
              endforeach;
          ?>      
        </nav>

        <h2> My Listed Items </h2>

         <!--  While there are auctions, display them
          if none, print that there are none -->    
        <?php 
            $currCategoryCount = 0;
            $openItem = 1; $closedItem = 5;
            $clothing = 1; $furniture = 2; $jewelry = 3;
            $hasCloth = 0;$hasFurn = 0;$hasJewel = 0;
            foreach ($userData as $currItem) :
              // determine if the item is currently listed for sale
              if($currItem['STATUS'] == $openItem || $currItem['STATUS'] == $closedItem):
                // determine the item's scategory
                if($currItem['ITEM_CATEGORY'] == $clothing){
                    $hasCloth++;
                    $currCategoryCount = $hasCloth;
                }
                elseif ($currItem['ITEM_CATEGORY'] == $furniture) {
                   $hasFurn++;
                   $currCategoryCount = $hasFurn; 
                }
                elseif ($currItem['ITEM_CATEGORY'] == $jewelry) {
                   $hasJewel++;
                   $currCategoryCount = $hasJewel;
                } 
        ?>
           
          <?php 
          // if this is the first instance of a category, print the category sign
            if ($currCategoryCount == 1) : ?>
             <h3> <?=htmlspecialchars($currItem['CATEGORY'])?> </h3>
          <?php endif;?> 

           <div class="itemWhole">
            <div class="itemProperties">
            <h4> <?=htmlspecialchars($currItem['ITEM_CAPTION'])?> </h4>
              <img src="showPhoto.php?auctionId=<?=$currItem['AUCTION_ID']?>" alt="<?=htmlspecialchars($currItem['ITEM_CAPTION'])?>"/> 
                <dl>
                  <dt> Auction status </dt>  
                    <dd> <?=htmlspecialchars($currItem['STATNAME'])?>  </dd>
                  <dt> Condition </dt>  
                    <dd> New </dd>
                  <dt> Current Price </dt>  
                    <dd> $ <?=htmlspecialchars($currItem['ITEM_PRICE'])?> </dd>
                  <dt> Required increase in Price  </dt>  
                    <dd> $ 20 </dd>
                  <dt> Auction Start Date-Time</dt>  
                    <dd> <?=htmlspecialchars($currItem['OPEN_TIME'])?> </dd>                
                  <dt> Auction End Date-Time </dt>  
                    <dd> <?=htmlspecialchars($currItem['CLOSE_TIME'])?>   </dd>
                  <dt> Description </dt>  
                    <dd> <?=htmlspecialchars($currItem['ITEM_DESCRIPTION'])?> </dd>                
                 </dl>  
               <h4> The reserve price for this item is $<?=htmlspecialchars($currItem['RESERVE_PRICE'])?>. It has
                    <?php if($currItem['RESERVE_PRICE'] > $currItem['ITEM_PRICE']){ echo "not"; }?> been met. </h4> 
              </div>      
              <div class="buttons">        
               <h3>
                  <form method="post" action="updateItem.php">
                      <input type="hidden" name="auctionId" value="<?=htmlspecialchars($currItem['AUCTION_ID'])?>" />
                      <input type="submit" value=" Update Item information "/>
                  </form>
                </h3> 
                <h3>
                  <form method="post" action="cancelItem.php">
                      <input type="hidden" name="auctionId" value="<?=htmlspecialchars($currItem['AUCTION_ID'])?>" />
                      <input type="submit" value=" Cancel this listing "/>
                  </form>
                </h3> 
              </div>  
            </div>   
          <br />
        
        <?php
           endif;
           endforeach;

           if($hasCloth == 0):?> 
            <h3> Clothing </h3>
            <ul> 
               <li> No Listings</li>  
               <li>  
                <form method="get" action="listItem.php">
                   <input type="hidden" name="sellerName" value="1" />
                   <input type="submit" value="List an item"/>
                </form>
               </li> 
            </ul>  
           <?php endif; 
           if($hasFurn == 0):?> 
            <h3> Furniture </h3>
            <ul> 
               <li> No Listings</li>  
               <li>  
                <form method="get" action="listItem.php">
                  <input type="hidden" name="sellerName" value="1" />
                   <input type="submit" value="List an item"/>
                </form>
               </li> 
            </ul>
           <?php endif;
           if($hasJewel == 0):?> 
             <h3> Jewelry </h3>
             <ul> 
               <li> No Listings</li>  
               <li>  
                <form method="get" action="listItem.php">
                   <input type="hidden" name="sellerName" value="1" />
                   <input type="submit" value="List an item"/>
                </form>
               </li> 
          </ul>
           <?php endif;?> 
          <br />          
    <!--             
        <h2> My Active Auctions that I have Bid on</h2>


          <ul> 
            <li> No active auctions </li>
            <li><a href="auctions.html"> Browse All Active Auctions </a></li>
          </ul>

        <br /> -->

        <h2> My Bidding Results </h2>
          <!--  While there are auctions, display them
          if none, print that there are none -->    
          <?php 
              $currCategoryCount = 0;
              $hasCloth = 0;$hasFurn = 0;$hasJewel = 0;
              foreach ($userData as $currItem) :
                // determine if the item is currently listed for sale
                if($currItem['STATUS'] == $wonItem):
                  // determine the item's scategory
                  if($currItem['ITEM_CATEGORY'] == $clothing){
                      $hasCloth++;
                      $currCategoryCount = $hasCloth;
                  }
                  elseif ($currItem['ITEM_CATEGORY'] == $furniture) {
                     $hasFurn++;
                     $currCategoryCount = $hasFurn; 
                  }
                  elseif ($currItem['ITEM_CATEGORY'] == $jewelry) {
                     $hasJewel++;
                     $currCategoryCount = $hasJewel;
                  } 
          ?>
             
            <?php 
            // if this is the first instance of a category, print the category sign
              if ($currCategoryCount == 1) : ?>
               <h3> <?=htmlspecialchars($currItem['CATEGORY'])?> </h3>
            <?php endif;?> 
             <div class="item">
                <img src="showPhoto.php?auctionId=<?php htmlspecialchars($currItem['AUCTION_ID'])?>" alt="<?=htmlspecialchars($currItem['ITEM_CAPTION'])?>"/> 
                <h4> <?=htmlspecialchars($currItem['ITEM_CAPTION'])?> </h4>
                <dl>
                  <dt> Outcome </dt> 
                    <dd> WINNER </dd>
                  <dt> Winning Price </dt> 
                    <dd> $ <?=htmlspecialchars($currItem['ITEM_PRICE'])?>  </dd>
                  <dt> Auction End Date-Time </dt> 
                    <dd> <?=htmlspecialchars($currItem['CLOSE_TIME'])?>   </dd>
                 </dl>  
                <h4>
                  <form method="post" action="buyItem.php">
                      <input type="hidden" name="auctionId" value="<?=htmlspecialchars($currItem['AUCTION_ID'])?>" />
                      <input type="submit" value=" Pay for Purchase "/>
                  </form>
                </h4>  
              </div>
           <?php
           endif;
           endforeach;

           if($hasCloth == 0):?> 
            <h3> Clothing </h3>
            <ul> 
               <li> No Listings</li>  
               <li><h4><a href="auctions.php"> Browse All Active Auctions </a></h4></li>
            </ul>  
           <?php endif; 
           if($hasFurn == 0):?> 
            <h3> Furniture </h3>
            <ul> 
               <li> No Listings</li>  
               <li><a href="auctions.php"> Browse All Active Auctions </a></li>
            </ul>
           <?php endif;
           if($hasJewel == 0):?> 
             <h3> Jewelry </h3>
             <ul> 
               <li> No Listings</li>  
               <li><a href="auctions.php"> Browse All Active Auctions </a></li>
          </ul>
           <?php endif;?> 
          <br />      


      <footer><h4> Have items that you wish to sell? <a href="login.php">Post an item for auction </a></h4></footer>
    </div>  
  </body>
</html>