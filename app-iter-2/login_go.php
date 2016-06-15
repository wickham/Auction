<?php
session_start();
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== "on") {
    header('Location: https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
    exit(1);
}
require('/u/z/users/cs105/wickham/PHP/openZdatabase.php');
require('password.php');
$login_success = $database->prepare('
	SELECT PERSON_ID, PASSWORD, CONCAT(FORENAME, \' \', SURNAME) AS USER
	FROM PERSON
	WHERE EMAIL_ADDRESS = :email;
');
$login_success->bindValue(':email',$_POST['username'],PDO::PARAM_STR);
$login_success->execute();
$login_info = $login_success->fetch();
$_SESSION['username'] = $login_info['USER'];

if (password_verify($_POST['password'],$login_info['PASSWORD']))
	{ 
		$_SESSION['user'] = $login_info['PERSON_ID'];
		header('Location: sell.php');
	}
else {
	$_SESSION['login_message'] = htmlspecialchars("Invalid email or password!");
	header('Location: index.php');
}
?>