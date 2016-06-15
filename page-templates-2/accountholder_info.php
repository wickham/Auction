<?php
session_start();
require('/u/tcorley/openZdatabase.php');
if (!isset($_SESSION['user']))
{
    $_SESSION['login_message'] = htmlspecialchars("You need to log in before you can do that!");
    header('Location: index.php');
}
$getPayment = $database->prepare('
	SELECT 
		P.EXPIRATON_DATE,
		C.CC_TYPE AS CARD,
		P.LAST_FOUR
		FROM PAYMENT P
		JOIN CC_COMPANY C ON P.CARD_TYPE = C.CARD_ID
		WHERE P.CARD_OWNER = :user;
	');
$getPayment->bindValue(':user',$_SESSION['user'],PDO::PARAM_INT);
$getPayment->execute();
$payments = $getPayment->fetchAll();
$getPayment->closeCursor();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <meta charset="utf-8"/>
        <title>Account Holder Information</title>
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
        <h1>Your Account Info</h1>
        <div class="content">
        	

    	</div>
    </body>
</html>