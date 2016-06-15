<?php
require '/u/z/users/cs105/wickham/PHP/openZdatabase.php';
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
$photoFile = fopen($_FILES['photo']['tmp_name'], 'rb'); 
	 if(strlen($photoFile) != 0 ){
		 
	   header('Content-Type: image/jpeg');	
	   header('Content-Length: '.strlen($photoFile));	
	   echo $photoFile;
	 } 
	 else{
       $photoCon = file_get_contents("noPhoto.jpg");
       header('Content-Type: image/jpeg');
       header('Content-Length: '.strlen($photoCon));

	   echo $photoCon;
     
	}