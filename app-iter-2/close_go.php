<?php
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== "on") {
    header('HTTP/1.1 403 Forbidden: TLS Required');
    // Optionally output an error page here
    exit(1);
}

require('/u/z/users/cs105/wickham/PHP/openZdatabase.php');
$placeBid = $database->prepare('
	DELETE FROM AUCTION WHERE AUCTION_ID = :auctionId;
	');

$auction = $_POST['cancel'];
$placeBid->bindValue(':auctionId',$auction,PDO::PARAM_INT);
$status = $placeBid->execute();
$placeBid->closeCursor();

if($status)
{
	$message = "Cancellation successful!";
	header('Location: bidsuccess.php?message=' . htmlspecialchars($message));
}
else
{
	$message = "Cancellation was unsuccessful. Try again in a little while.";
	header('Location: bidfail.php?message=' . htmlspecialchars($message));
}
?>