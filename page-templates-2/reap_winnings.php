<?php
require('/u/tcorley/openZdatabase.php');
$winningLot = $database->prepare('
	UPDATE AUCTION
	SET STATUS = 3, PAID_FOR = FALSE
	WHERE TIMEDIFF(CLOSE_TIME,NOW()) <= 0;
	');
$winningLot->execute();
$winningLot->closeCursor;
?>