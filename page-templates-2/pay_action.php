<?php
session_start();
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== "on") {
    header('HTTP/1.1 403 Forbidden: TLS Required');
    // Optionally output an error page here
    exit(1);
}

require('/u/tcorley/openZdatabase.php');
$updatePaid = $database->prepare('
	UPDATE AUCTION
	SET PAID_FOR = TRUE
	WHERE AUCTION_ID = :auction;
	');
$updatePaid->bindValue(':auction',$_POST['id'],PDO::PARAM_INT);
$status = $updatePaid->execute();
$updatePaid->closeCursor();


if($status)
{
	$_SESSION['youtube'] = "yes";
	header('Location: success.php?message=' . htmlspecialchars("You have paid for your item!"));
}
else
{
	header('Location: failure.php?message=' . htmlspecialchars("The payment was not processed. Try again in a little while.:("));
}

?>