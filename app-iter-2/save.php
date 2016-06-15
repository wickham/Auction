<?php
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== "on") {
    header('HTTP/1.1 403 Forbidden: TLS Required');
    // Optionally output an error page here
    exit(1);
}

require('/u/z/users/cs105/wickham/PHP/openZdatabase.php');
$insertItem = $database->prepare('
	UPDATE AUCTION
	SET ITEM_CATEGORY = :category, ITEM_CAPTION = :caption,ITEM_DESCRIPTION = :desc,ITEM_PHOTO = :photo
	WHERE AUCTION_ID = :auction;
	');
$auction = $_POST['user'];
$caption = $_POST['item_name'];
$description = $_POST['description'];
$category = $_POST['category'];
$picture = fopen($_FILES['photo']['tmp_name'], 'rb');
$insertItem->bindValue(':auction',$auction,PDO::PARAM_INT);
$insertItem->bindValue(':category',$category,PDO::PARAM_INT);
$insertItem->bindValue(':caption',$caption,PDO::PARAM_STR);
$insertItem->bindValue(':desc',$description,PDO::PARAM_STR);
$insertItem->bindValue(':photo',$picture,PDO::PARAM_LOB);
$status = $insertItem->execute();
$insertItem->closeCursor();

if($status)
{
	header('Location: selling.php');
}
else
{
	header('Location: bidfail.php?message="We can\'t update your item...Try again?"');
}
?>