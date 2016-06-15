<?php
session_start();
if (!isset($_SESSION['user']))
{
    $_SESSION['login_message'] = htmlspecialchars("You can't...get to fail without trying anything");
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
        <title>Failure:(</title>
    </head>
    <body>
        <div class="content">
        <h1>Failure:(</h1>
        <p><?=htmlspecialchars($message)?></p>
        <form action="mainpage.php">
            <input type="submit" value="Back to main"/>
        </form>
        </div>
    </body>
</html>