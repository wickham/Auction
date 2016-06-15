<?php
require('/u/z/users/cs105/wickham/PHP/openZdatabase.php');
require('password.php');
session_start();
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== "on") {
    header('Location: https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
    exit(1);
}
// verify the variables before preceding
$errors = array();
$email = $_POST['username'];
if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email))
{
  $errors[] = "Invalid email format."; 
}
$first = $_POST['first'];
if (!preg_match("/^[a-zA-Z ]*$/",$first))
{
	$errors[] = "Only letters and white space allowed in first name."; 
}
$last = $_POST['last'];
if (!preg_match("/^[a-zA-Z ]*$/",$last))
{
	$errors[] = "Only letters and white space allowed in last name."; 
}

if($_POST['password'] !== $_POST['password_confirm'])
{
	$errors[] = "Incorrect passwords";
}

if (!$_POST['TOC'])
{
	$errors[] = "You didn't agree to the Terms and Conditions!";
}

$checkEmail = $database->prepare('
	SELECT 1
	FROM PERSON
	WHERE EMAIL_ADDRESS = :email;
	');
$checkEmail->bindValue(':email',$email,PDO::PARAM_STR);
$checkEmail->execute();
$isEmail = $checkEmail->fetch();
$checkEmail->closeCursor();
if($isEmail)
{
	$errors[] = "Email address is already being used, please try again";
}
if (sizeof($errors))
{
	$_SESSION['errorarray'] = $errors;
	header('Location: register.php');
}
else
{
	//encrypt password
	$encrypted = password_hash($_POST['password'],PASSWORD_BCRYPT);

	$addUser = $database->prepare('
		INSERT INTO PERSON
		(PERSON_ID, SURNAME, FORENAME, EMAIL_ADDRESS, PASSWORD)
		VALUES (NEXT_SEQ_VALUE("PERSON"), :last, :first, :email, :password);
		');
	$addUser->bindValue(':last',$last,PDO::PARAM_STR);
	$addUser->bindValue(':first',$first,PDO::PARAM_STR);
	$addUser->bindValue(':email',$email,PDO::PARAM_STR);
	$addUser->bindValue(':password',$encrypted,PDO::PARAM_STR);
	$status = $addUser->execute();
	$addUser->closeCursor();

	$_SESSION['login_message'] = htmlspecialchars("You are registered! Login to continue!");
	header('Location: index.php');

}

?>