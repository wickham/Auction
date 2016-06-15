<?php
header('Content-Type: image/jpeg');
require('/u/z/users/cs105/wickham/PHP/openZdatabase.php');
$result = $database->prepare('
	SELECT ITEM_PHOTO FROM AUCTION WHERE AUCTION_ID = :auctionId;
	');
$result->bindValue(':auctionId',$_REQUEST['photoId'],PDO::PARAM_INT);
if ($result->execute()) {
    $photo=$result->fetch();
	header('Content-Length: '.strlen($photo['ITEM_PHOTO']));
	if(strlen($photo['ITEM_PHOTO']))
		echo $photo['ITEM_PHOTO']; 
	elseif ($photo['ITEM_PHOTO'] == 0 || !$photo['ITEM_PHOTO'])
		
		echo header("Location: ./noPhoto.jpg");
}
