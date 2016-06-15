<?php
require('/u/tcorley/openZdatabase.php');
$auction = $_POST['id'];
$endAuction = $database->prepare('
	UPDATE AUCTION
	SET STATUS = 3 
	WHERE AUCTION_ID = :id;
	');
$endAuction->bindValue(':id',$auction,PDO::PARAM_INT);
$status = $endAuction->execute();
$endAuction->closeCursor();
header('Location: bid.php?id=' . htmlspecialchars($auction));
?>