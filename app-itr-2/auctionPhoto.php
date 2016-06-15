<?php
require '/u/z/users/cs105/wickham/app-itr-2/openZdatabase.php';
$auctionPhotoQuery = $database->prepare('
    SELECT
        ITEM_PHOTO
        FROM AUCTION
        WHERE AUCTION_ID = :auctionId;
    ');
$thisAuctionId = $_REQUEST['id'];
$auctionPhotoQuery->bindValue(':auctionId', $thisAuctionId, PDO::PARAM_INT);
$auctionPhotoQuery->execute();
$auctionPhotoResult = $auctionPhotoQuery->fetch();
$auctionPhotoQuery->closeCursor();

$photoContents = $auctionPhotoResult['ITEM_PHOTO'];
if (strlen($photoContents) == 0) 
{
	$photoContents = file_get_contents('noPhoto.jpg');
}

header('Content-Type: image/jpeg');
header('Content-Length: '.strlen($photoContents));
echo $photoContents;

if ($auctionPhotoResult === false) {
    error_log("{$__FILE__}:{$__LINE__}: Auction photo fetch failed. id={escapeshellarg($thisAuctionId)}");
    header("HTTP/1.1 500 Internal Server Error");
    echo '<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
  <head>
    <title>Internal Server Error</title>
    <meta charset="utf-8" />
  </head>
  <body>
  
    <h1>404 - Error</h1>
    <p>Unable to respond to your request</p>
    <p><h4>Auction photo fetch failed.</h4></p>
	
  </body>
</html>
';
    exit(1);
}

