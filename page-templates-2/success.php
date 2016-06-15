<?php
session_start();
if (!isset($_SESSION['user']))
{
    $_SESSION['login_message'] = htmlspecialchars("You can't be successful without logging in!");
    header('Location: index.php');
}
$message = $_REQUEST['message'];
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <meta charset="utf-8"/>
        <link rel="stylesheet" type="text/css" href="stylesheet.css"/>
        <link href='http://fonts.googleapis.com/css?family=Gafata' rel='stylesheet' type='text/css'/>
        <title>Success!</title>
    </head>
    <body>
        <ul class="navbar">
            <li><a href="mainpage.php">Home</a></li>
            <li><a href="browse.php">Browse</a></li>
            <li><a href="list_item.php">List Item</a></li>
            <li><a href="logout_confirm.php">Logout</a></li>
        </ul>
        <div class="content">
        <h1>Success!</h1>
        <p><?=htmlspecialchars($message)?></p>
        <form action="mainpage.php">
            <input type="submit" value="Back to main"/>
        </form>


<?php
if (isset($_SESSION['youtube']) && !empty($_SESSION['youtube'])):
?>
<iframe width="420" height="315" src="//www.youtube.com/embed/BybbEBOeKM8" frameborder="0" allowfullscreen></iframe>
<?php
unset($_SESSION['youtube']);
endif;
?>
        </div>
    </body>
</html>