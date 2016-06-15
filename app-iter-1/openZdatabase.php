<?php
// openZdatabase.php -- PHP include to open database on 'Z' server
// Auction Web Application Project
//
// C S 105: PHP/SQL, Spring 2014, J. Thywissen
// The University of Texas at Austin
//

try {
    $database = new PDO('mysql:host=localhost;dbname=cs105_wickham;charset=utf8', 'wickham', 'gfkVscKjgl', array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_PERSISTENT => true
    ));
} catch(PDOException $e) {
    error_log("{$e->getFile()}:{$e->getLine()}: PDO open failed: {$e->getCode()}: {$e->getMessage()}");
    header("HTTP/1.1 500 Internal Server Error");
    echo '<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
  <head>
    <title>Internal Server Error</title>
    <meta charset="utf-8" />
  </head>
  <body>
    <h1>Internal Server Error</h1>
    <p>Sorry, this Web site has encountered an unexpected condition, and is currently unable to respond to your request.</p>
    <p>Please retry later.</p>
    <p><small></small></p>
  </body>
</html>
';
    exit(1);
}
?>