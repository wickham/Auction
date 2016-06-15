<?php
session_start();
if (!isset($_SESSION['user']))
{
    $_SESSION['login_message'] = htmlspecialchars("You need to log in before you can update an item!");
    header('Location: index.php');
}
require('/u/tcorley/openZdatabase.php');
$openAuctionQuery = $database->prepare('
    SELECT
        A.AUCTION_ID,
        A.STATUS,
        A.SELLER AS ITEM_OWNER,
        CONCAT(P.FORENAME, \' \', P.SURNAME) AS SELLER,
        A.OPEN_TIME,
        A.CLOSE_TIME,
        CONCAT(
            FLOOR(HOUR(TIMEDIFF(A.CLOSE_TIME, NOW())) / 24), " days ",
            MOD(HOUR(TIMEDIFF(A.CLOSE_TIME, NOW())), 24), " hours ",
            MINUTE(TIMEDIFF(A.CLOSE_TIME, NOW())), " minutes") AS TIME_LEFT,
        C.NAME AS ITEM_CATEGORY,
        A.ITEM_CAPTION,
        A.ITEM_DESCRIPTION,
        A.ITEM_PHOTO
        FROM AUCTION A
            JOIN ITEM_CATEGORY C ON A.ITEM_CATEGORY = C.ITEM_CATEGORY_ID
            JOIN PERSON P ON A.SELLER = P.PERSON_ID
        WHERE A.AUCTION_ID = :auctionId;
    ');
$thisAuctionId = $_POST['id'];
$openAuctionQuery->bindValue(':auctionId', $thisAuctionId, PDO::PARAM_INT);
$openAuctionQuery->execute();
$thisAuction = $openAuctionQuery->fetch();
$openAuctionQuery->closeCursor();
$categoriesQuery = $database->prepare('
    SELECT
        ITEM_CATEGORY_ID,
        NAME
        FROM ITEM_CATEGORY;
    ');
$categoriesQuery->execute();
$categories = $categoriesQuery->fetchAll();
$categoriesQuery->closeCursor();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <meta charset="utf-8"/>
        <title>Update Item</title>
        <link rel="stylesheet" type="text/css" href="stylesheet.css"/>
        <link href='http://fonts.googleapis.com/css?family=Gafata' rel='stylesheet' type='text/css'/>
    </head>
    <body>
        <ul class="navbar">
            <li><a href="mainpage.php">Home</a></li>
            <li><a href="browse.php">Browse</a></li>
            <li><a href="list_item.php">List Item</a></li>
            <li><a href="logout_confirm.php">Logout</a></li>
            <li class="go_right">You are <?=htmlspecialchars($_SESSION['username'])?> for this session :)</li>
        </ul>
        <div class="centered">
            <h1>Update Listing</h1>
            <form enctype="multipart/form-data" action="update_action.php" method="post">
                Item name: <input type="text" name="item_name" value="<?=htmlspecialchars($thisAuction['ITEM_CAPTION'])?>"/><br/>
                Category: <select name="category" required="required">
<?php
foreach ($categories as $currCat):
?>
            <option value="<?=htmlspecialchars($currCat['ITEM_CATEGORY_ID'])?>"><?=htmlspecialchars($currCat['NAME'])?></option>
<?php
endforeach;
?>
                </select><br/>
                Description:  <input type="text" name="description" value="<?=htmlspecialchars($thisAuction['ITEM_DESCRIPTION'])?>"/><br/>
                Photo(re-upload less than 2MB): <input type="file" name="photo" required="required" accept="image/jpeg"/><br/>
                <input type="hidden" name="user" value="<?=htmlspecialchars($thisAuctionId)?>"/>
                <input type="submit" value="update item!"/>
            </form>
        </div>
    </body>
</html>

