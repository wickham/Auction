<?php
  require '/u/briana21/Desktop/CS105/openZdatabase.php';
	 $pictureQuery = $database->prepare('
	     SELECT
	         AUCTION.AUCTION_ID,
	         AUCTION.ITEM_PHOTO
	            FROM AUCTION
             WHERE AUCTION.AUCTION_ID = :auctionId;   
     '); 
     $thisAuctionId = $_REQUEST['auctionId'];
     $pictureQuery->bindValue(':auctionId', $thisAuctionId);
	 $pictureQuery->execute();
	 $auction = $pictureQuery->fetch();
	 $pictureQuery->closeCursor();

     $photoFile = $auction['ITEM_PHOTO'];

	 if(strlen($photoFile) != 0 ){
	   header('Content-Type: image/jpeg');	
	   header('Content-Length: '.strlen($photoFile));	
	   echo $photoFile;
	 } 
	 else{
       $placeHolder = file_get_contents("images/no_image.jpg");
       header('Content-Type: image/jpeg');
       header('Content-Length: '.strlen($placeHolder));

	   echo $placeHolder;
     
	}