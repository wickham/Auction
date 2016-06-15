<?php
session_start();
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== "on") {
    header('HTTP/1.1 403 Forbidden: TLS Required');
    // Optionally output an error page here
    exit(1);
}
if(isset($_FILES['photo']))
{
	if($_FILES['photo']['size'] == 0)
	{
		$_SESSION['list_message'] = htmlspecialchars("Try again with a smaller image.");
		header('Location: new.php');
	}
	else
	{
		require('/u/z/users/cs105/wickham/PHP/openZdatabase.php');
		$insertItem = $database->prepare('
			INSERT INTO AUCTION
			(AUCTION_ID,STATUS,SELLER,CLOSE_TIME,ITEM_CATEGORY,ITEM_CAPTION,ITEM_DESCRIPTION,ITEM_PHOTO)
			VALUES (NEXT_SEQ_VALUE("AUCTION"),1,:seller,:closeTime,:category,:caption,:desc,:photo);
			');
		$caption = $_POST['item_name'];
		$description = $_POST['description'];
		$category = $_POST['category'];
		$picture = fopen($_FILES['photo']['tmp_name'], 'rb');
		$insertItem->bindValue(':closeTime',$_POST['closeTime'],PDO::PARAM_INT);
		$insertItem->bindValue(':seller',$_SESSION['user'],PDO::PARAM_INT);
		$insertItem->bindValue(':category',$category,PDO::PARAM_INT);
		$insertItem->bindValue(':caption',$caption,PDO::PARAM_STR);
		$insertItem->bindValue(':desc',$description,PDO::PARAM_STR);
		$insertItem->bindValue(':photo',$picture,PDO::PARAM_LOB);
		$status = $insertItem->execute();
		$insertItem->closeCursor();

		if($status)
		{
			header('Location: bidsuccess.php?message=' . htmlspecialchars("Your item was listed!"));
		}
		else
		{
			header('Location: bidfail.php?message=' . htmlspecialchars("Try again"));
		}
	}
}
?>