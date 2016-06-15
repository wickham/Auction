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
        <meta charset="utf-8"/>
        <link rel="stylesheet" type="text/css" href="stylesheet.css"/>
        <link href='http://fonts.googleapis.com/css?family=Gafata' rel='stylesheet' type='text/css'/>
        <title>Cancel listing</title>
    </head>
    <body>
        <ul class="navbar">
            <li><a href="#">&lt;-- Return(NotWorkingYet)</a></li>
            <li><a href="mainpage.php">Home</a></li>
            <li><a href="browse.php">Browse</a></li>
            <li><a href="list_item.php">List Item</a></li>
            <li><a href="logout_confirm.php">Logout</a></li>
            <li class="go_right">You are <?=htmlspecialchars($_SESSION['username'])?> for this session :)</li>
        </ul>
        <div class="content">
        <h1>Cancel listing: <?=htmlspecialchars($cancelItem)?>?</h1>
        <form action="cancel_action.php" method="post">
            <input type="hidden" name="cancel" value="<?=htmlspecialchars($itemId)?>"/>
            <input type="submit" value="yes"/>
        </form>
        <form action="user_activity.php">
            <input type="submit" value="no"/>
        </form>
        </div>
    </body>
</html>