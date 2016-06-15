<?php
session_start();
if ($_SESSION['user'] == "")
{
    $_SESSION['login_message'] = htmlspecialchars("Please log in again.");
    header("Location: index.php"); 
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <meta charset="utf-8"/>
        <link rel="stylesheet" type="text/css" href="stylesheet.css"/>
        <link href='http://fonts.googleapis.com/css?family=Gafata' rel='stylesheet' type='text/css'/>
        <title>Main Page</title>
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
        <h1>Welcome, <?=htmlspecialchars($_SESSION['username'])?>!</h1>
        <p><a href="browse.php">Browse Listings</a></p>
        <p>--or--</p>
        <p><a href="list_item.php">List Item</a></p>
        <p>--or--</p>
        <p><a href="user_activity.php">Your Current Activity</a></p>
        <p>--or--</p>
        <p><a href="logout_confirm.php">Logout</a></p>
        </div>
    </body>
</html>
