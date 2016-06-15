<?php
session_start();
require '/u/briana21/Desktop/CS105/openZdatabase.php';
$auctionQuery = $database->prepare('
    SELECT
        AUCTION.AUCTION_ID,
        CONCAT(PERSON.FORENAME, \' \', PERSON.SURNAME) AS SELLER,
        AUCTION.ITEM_CATEGORY,
        AUCTION.ITEM_CAPTION,
        AUCTION.OPEN_TIME,
        AUCTION.ITEM_PRICE,
        AUCTION.CLOSE_TIME,
        AUCTION.ITEM_PHOTO
        FROM AUCTION
           JOIN PERSON ON AUCTION.SELLER = PERSON.PERSON_ID;
    ');
$auctionQuery->execute();
$auctions = $auctionQuery->fetchAll();
$auctionQuery->closeCursor();
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
    <div id="login"><a href="login.php"> Login </a></div>
    <div id="register"><a href="register.php"> Register </a></div>
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

  <div id="searchCrit">
    <h2> Search Criteria</h2>
    <!-- Print out each category and add commas when more than one is chosen -->
      <p>Categories: <?=htmlspecialchars($_REQUEST['category1'])?> 
        <?php
           if(htmlspecialchars($_REQUEST['category2']) != NULL){
              echo ", ";
           }
        ?>              
        <?=htmlspecialchars($_REQUEST['category2'])?>
        <?php
           if(htmlspecialchars($_REQUEST['category3']) != NULL){
              echo ", ";
           }
        ?>  
        <?=htmlspecialchars($_REQUEST['category3'])?>
        <?php
           if(htmlspecialchars($_REQUEST['category1']) == NULL && htmlspecialchars($_REQUEST['category2']) == NULL 
               && htmlspecialchars($_REQUEST['category3']) == NULL){
              echo " No name specified ";
           }
        ?>
      </p>

      <p>Seller: <?php 
             $seller;
             if($_REQUEST['sellerName'] != NULL) { 
              $seller = $_REQUEST['sellerName'] ;
              echo htmlspecialchars($_REQUEST['sellerName']);
               } 
               else{ echo "none specified"; $seller = NULL;} ?></p>

      <p>Item name: <?=htmlspecialchars($_REQUEST['searchItemName'])?> 
        <?php
           $itemName;
           if(htmlspecialchars($_REQUEST['searchItemName']) == NULL){
              echo " No name specified ";
              $itemName == NULL;
           }
           else{
              $itemName == $_REQUEST['searchItemName'];
           } 
        ?>   </p>
  </div>

<h2> Matching auctions </h2>
 <h3> Clothing </h3>
     <!--  While there are matching clothing auctions, display them
      if none, print that there are none -->
    <?php 
        $clothing = 1;
        $isPresent = false;
        foreach ($auctions as $currAuc) :
           if(($currAuc['ITEM_CATEGORY'] == $clothing) && (htmlspecialchars($_REQUEST['category1']) != NULL) &&
            ($itemName ==$currAuc['ITEM_CAPTION'] || $itemName == NULL) && ($seller==$currAuc['SELLER'] || $seller == NULL)): 
            $isPresent = true;
    ?>
              <div class="item">
                <img src="showPhoto.php?auctionId=<?=htmlspecialchars($currAuc['ITEM_PHOTO'])?>" alt="<?=htmlspecialchars($currAuc['ITEM_CAPTION'])?>"/>
                <h4> <?=htmlspecialchars($currAuc['ITEM_CAPTION'])?> </h4>
                  <dl>
                    <dt> Current Price </dt> 
                      <dd> $ <?=htmlspecialchars($currAuc['ITEM_PRICE'])?> </dd>
                    <dt> Close Time for Auction </dt> 
                      <dd> <?=htmlspecialchars($currAuc['CLOSE_TIME'])?> </dd>
                    <dt> Added By </dt> 
                    <?php
                        if($_SESSION['userName'] != NULL):
                    ?>
                      <dd> <?=htmlspecialchars($currAuc['SELLER'])?> </dd>
                    <?php
                        else:
                    ?>
                      <dd> Please login to view seller information </dd>  
                    <?php endif; ?>   
                  </dl>  
                  <form method="post" action="itemPage.php">  
                    <input type="hidden" name="auctionId" value="<?=htmlspecialchars($currAuc['AUCTION_ID'])?>" />
                    <input type="submit" value="Bid on this item"/>
                  </form>                </div>           

    <?php 
          endif;  
          endforeach;

        // if there are no pending clothing auctions, print none pending
        if($isPresent == false){
           echo "<ul><li>No matching auctions</li></ul>";
        }
    ?> 

      <h3> Furniture </h3>
     <!--  While there are furniture auctions, display them
      if none, print that there are none -->    
    <?php 
        $furniture = 2;
        $isPresent = false;
        foreach ($auctions as $currAuc) :
           if(($currAuc['ITEM_CATEGORY'] == $furniture) && (htmlspecialchars($_REQUEST['category2']) != NULL) &&
            ($itemName ==$currAuc['ITEM_CAPTION'] || $itemName == NULL) && ($seller==$currAuc['SELLER'] || $seller == NULL)):           
            $isPresent = true;
    ?>
              <div class="item">
                <img src="showPhoto.php?auctionId=<?=htmlspecialchars($currAuc['ITEM_PHOTO'])?>" alt="<?=htmlspecialchars($currAuc['ITEM_CAPTION'])?>"/>
                <h4> <?=htmlspecialchars($currAuc['ITEM_CAPTION'])?> </h4>
                  <dl>
                    <dt> Current Price </dt> 
                      <dd> $ <?=htmlspecialchars($currAuc['ITEM_PRICE'])?> </dd>
                    <dt> Close Time for Auction </dt> 
                      <dd> <?=htmlspecialchars($currAuc['CLOSE_TIME'])?> </dd>
                    <dt> Added By </dt> 
                    <?php
                        if($_SESSION['userName'] != NULL):
                    ?>
                      <dd> <?=htmlspecialchars($currAuc['SELLER'])?> </dd>
                    <?php
                        else:
                    ?>
                      <dd> Please login to view seller information </dd>  
                    <?php endif; ?>   
                  </dl>  
                  <form method="post" action="itemPage.php">  
                    <input type="hidden" name="auctionId" value="<?=htmlspecialchars($currAuc['AUCTION_ID'])?>" />
                    <input type="submit" value="Bid on this item"/>
                  </form>  
              </div>           

    <?php 
          endif;  
          endforeach;

        // if there are no pending furniture auctions, print none pending
        if($isPresent == false){
           echo "<ul><li>No matching auctions</li></ul>";
        }
    ?>  


     <h3> Jewelry </h3>
     <!--  While there are jewelry auctions, display them
      if none, print that there are none -->    
    <?php 
        $jewelry = 3;
        $isPresent = false;
        foreach ($auctions as $currAuc) :
           if(($currAuc['ITEM_CATEGORY'] == $jewelry) && (htmlspecialchars($_REQUEST['category3']) != NULL) &&
            ($itemName ==$currAuc['ITEM_CAPTION'] || $itemName == NULL) && ($seller==$currAuc['SELLER'] || $seller == NULL)):           
            $isPresent = true;
    ?>
              <div class="item">
                <img src="showPhoto.php?auctionId=<?=htmlspecialchars($currAuc['ITEM_PHOTO'])?>" alt="<?=htmlspecialchars($currAuc['ITEM_CAPTION'])?>"/>
                <h4> <?=htmlspecialchars($currAuc['ITEM_CAPTION'])?> </h4>
                  <dl>
                    <dt> Current Price </dt> 
                      <dd> $ <?=htmlspecialchars($currAuc['ITEM_PRICE'])?> </dd>
                    <dt> Close Time for Auction </dt> 
                      <dd> <?=htmlspecialchars($currAuc['CLOSE_TIME'])?> </dd>
                    <dt> Added By </dt> 
                    <?php
                        if($_SESSION['userName'] != NULL):
                    ?>
                      <dd> <?=htmlspecialchars($currAuc['SELLER'])?> </dd>
                    <?php
                        else:
                    ?>
                      <dd> Please login to view seller information </dd>  
                    <?php endif; ?>   
                  </dl>  
                  <form method="post" action="itemPage.php">  
                    <input type="hidden" name="auctionId" value="<?=htmlspecialchars($currAuc['AUCTION_ID'])?>" />
                    <input type="submit" value="Bid on this item"/>
                  </form>  
              </div>           

    <?php 
          endif;  
          endforeach;

        // if there are no pending jewelry auctions, print none pending
        if($isPresent == false){
           echo "<ul><li>No matching auctions</li></ul>";
        }
    ?> 
      <footer><h4> Have items that you wish to sell? <a href="login.html">Post an item for auction </a></h4></footer>
    </div>  
  </body>
</html>