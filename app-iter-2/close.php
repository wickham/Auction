<?php
session_start();
if (!isset($_SESSION['user']))
{
    $_SESSION['login_message'] = htmlspecialchars("You have to log in in order to cancel something!");
    header('Location: index.php');
}
$cancelItem = $_POST['item'];
$itemId = $_POST['itemId'];
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
<title>Redirecting</title>
<link rel="stylesheet" href="firstStyle.css" type="text/css" />
<meta charset="utf-8" />
</head>


    <body>

     <div class="red">
        <h1>Confirm Cancel Listing: <?=htmlspecialchars($cancelItem)?>?</h1>
        <form action="close_go.php" method="post">
            <input type="hidden" name="cancel" value="<?=htmlspecialchars($itemId)?>"/>
            <input type="submit" value="yes"/>
        </form>
        <form action="selling.php">
            <input type="submit" value="no"/>
        </form>
        </div>
    </body>
</html>