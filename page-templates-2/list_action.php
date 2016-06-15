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
		$_SESSION['list_message'] = htmlspecialchars("Photo error...Please try again with a smaller image.");
		header('Location: list_item.php');
	}
	else
	{
		require('/u/tcorley/openZdatabase.php');
		$insertItem = $database->prepare('
			INSERT INTO AUCTION
			(AUCTION_ID,STATUS,SELLER,CLOSE_TIME,ITEM_CATEGORY,ITEM_CAPTION,ITEM_DESCRIPTION,ITEM_PHOTO)
			VALUES (NEXT_SEQ_VALUE("AUCTION"),1,:seller,DATE_ADD(NOW(),INTERVAL :duration DAY),:category,:caption,:desc,:photo);
			');
		$caption = $_POST['item_name'];
		$description = $_POST['description'];
		$category = $_POST['category'];
		$picture = fopen($_FILES['photo']['tmp_name'], 'rb');
		$insertItem->bindValue(':duration',$_POST['duration'],PDO::PARAM_INT);
		$insertItem->bindValue(':seller',$_SESSION['user'],PDO::PARAM_INT);
		$insertItem->bindValue(':category',$category,PDO::PARAM_INT);
		$insertItem->bindValue(':caption',$caption,PDO::PARAM_STR);
		$insertItem->bindValue(':desc',$description,PDO::PARAM_STR);
		$insertItem->bindValue(':photo',$picture,PDO::PARAM_LOB);
		$status = $insertItem->execute();
		$insertItem->closeCursor();

		if($status)
		{
			header('Location: success.php?message=' . htmlspecialchars("Your item was listed!"));
		}
		else
		{
			header('Location: failure.php?message=' . htmlspecialchars("We can't list your item...Try again?"));
		}
	}
}
?>