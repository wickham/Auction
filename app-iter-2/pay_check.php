<?php
session_start();
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== "on") {
    header('HTTP/1.1 403 Forbidden: TLS Required');
    // Optionally output an error page here
    exit(1);
}

require('/u/z/users/cs105/wickham/PHP/openZdatabase.php');
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
	$message = "Cancellation successful!";
	header('Location: success.php?message=' . htmlspecialchars($message));
}
else
{
	$message = "Cancellation was unsuccessful. Try again in a little while.";
	header('Location: fail.php?message=' . htmlspecialchars($message));
}
?>
