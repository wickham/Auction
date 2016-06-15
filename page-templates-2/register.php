<?php session_start(); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <meta charset="utf-8"/>
        <link rel="stylesheet" type="text/css" href="stylesheet.css"/>
        <link href='http://fonts.googleapis.com/css?family=Gafata' rel='stylesheet' type='text/css'/>
        <title>Create Account</title>
    </head>
    <body>
        <ul class="navbar">
            <li><a href="mainpage.php">Home</a></li>
            <li><a href="browse.php">Browse</a></li>
            <li><a href="list_item.php">List Item</a></li>
            <li><a href="logout_confirm.php">Logout</a></li>
        </ul>
        <div class="centered">
        <h1>Create an Account</h1>
<?php
if ($_SESSION['errorarray']):
?>
            <p class="warning">Please fix the following error(s):</p>
<?php
foreach ($_SESSION['errorarray'] as $error):
?>
            <p class="warning"><?=htmlspecialchars($error)?></p>
<?php
endforeach;
?>

<?php
unset($_SESSION['errorarray']);
endif;
?>
        <form action="register_action.php" method="post">
            <table border="1">
                <tr>
                    <td>Email:  </td>
                    <td><input type="text" required="required" name="username"/></td>
                </tr>
                <tr>
                    <td>First Name: </td>
                    <td><input type="text" required="required" name="first"/></td>
                </tr>
                <tr>
                    <td>Last Name: </td>
                    <td><input type="text" required="required" name="last"/></td>
                </tr>
                <tr>
                    <td>Password: </td>
                    <td><input type="password" required="required" name="pwd"/></td>
                </tr>
                <tr>
                    <td>Retype Password:</td>
                    <td><input type="password" required="required" name="pwd_confirm"/></td>
                </tr>
            </table>
            <input type="checkbox" name="TOC"/>I have read and agree to the <a href="tac.php">Terms and Conditions</a><br/> 
            <input type="submit" value="Register!"/>
        </form>
        </div>
    </body>
</html>
