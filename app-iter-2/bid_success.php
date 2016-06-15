<?php
session_start();
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== "on") {
    header('HTTP/1.1 403 Forbidden: TLS Required');
    exit(1);
}

require('/u/z/users/cs105/wickham/PHP/openZdatabase.php');

$bidder = $_SESSION['user'];
$amount = $_POST['user_bid'];
$auction = $_POST['auction'];
$highBid = $database->prepare('
	SELECT AMOUNT as HIGH_BID, BIDDER, BID_ID
	FROM BID
	WHERE AMOUNT = (
		SELECT MAX(AMOUNT)
		FROM BID
		WHERE AUCTION = :auction
		);
	');
$highBid->bindValue(':auction',$auction,PDO::PARAM_INT);
$highBid->execute();
$getValue = $highBid->fetch();
$highBid->closeCursor();

if ($getValue['HIGH_BID'] >= $amount) 
{
	$message = 'Current price is $' . $getValue['HIGH_BID'] . '. Please bid higher than this!';
	$_SESSION['bid_error'] = htmlspecialchars($message);
	header('Location: bid.php?id=' . $auction);
}
else 
{
	$placeBid = $database->prepare('
		INSERT INTO BID
		(BID_ID,BIDDER,AUCTION,AMOUNT)
		VALUES (NEXT_SEQ_VALUE("BID"),:bidder,:auction,:amount);
		');
	$placeBid->bindValue(':bidder',$bidder,PDO::PARAM_INT);
	$placeBid->bindValue(':auction',$auction,PDO::PARAM_INT);
	$placeBid->bindValue(':amount',$amount,PDO::PARAM_INT);
	$status = $placeBid->execute();
	$placeBid->closeCursor();

	if($status)
	{
		header('Location: success.php?message=' . htmlspecialchars("Your bid was successful!"));
	}
	else
	{
		header('Location: failure.php?message=' . htmlspecialchars("Your bid was unsuccessful. Try again."));
	}
}
?>