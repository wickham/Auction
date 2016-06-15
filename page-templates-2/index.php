<?php session_start(); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <link rel="stylesheet" type="text/css" href="stylesheet.css"/>
        <link href='http://fonts.googleapis.com/css?family=Gafata' rel='stylesheet' type='text/css'/>
        <title>Login</title>
	<meta charset="utf-8"/>
    </head>
    <body>
        <div id="bg">
            <img source="Austin.jpeg"/>
        <div>
        <ul class="navbar">
            <li><a href="mainpage.php">Home</a></li>
            <li><a href="browse.php">Browse</a></li>
            <li><a href="list_item.php">List Item</a></li>
<?php
if (isset($_SESSION['user']) && !empty($_SESSION['user'])):
?>
            <li><a href="logout_confirm.php">Logout</a></li>
            <li class="go_right">You are <?=htmlspecialchars($_SESSION['username'])?> for this session :)</li>
<?php
endif;
?>
        </ul>
        <div class="centered">
            <h1>Longhorn Auction</h1>
<?php
if ($_SESSION['login_message']):
?>
            <p class="warning"><?=htmlspecialchars($_SESSION['login_message'])?></p>
<?php
unset($_SESSION['login_message']);
endif;
?>

            <form action="login.php" method="post">
                <table>
                <tr>    
                    <td>Email:</td> 
                    <td><input type="text" name="username"/></td>
                </tr>
                <tr>
                    <td>Password:</td> 
                    <td><input type="password" name="pwd"/></td>
                </tr>
                </table>
                    <input type="submit" value="login!"/>
            </form>
            <p>No account? Register <a href="register.php">here</a>.</p>
        </div>
    </body>
</html>
