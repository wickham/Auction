<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en"><head>
    <title>Acme Auctions</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="style.css"/>
    <link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'/>
    <link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'/>
  </head>
  <body>
    <div id="page">
      <!-- Header (Title and Search Bar) -->
        <header>
          <h1> Acme Auctions </h1>

      <!-- Search bar -->
        <form action="searchResults.php" method="get" id="searchBar">
          <input type="text" name="searchName" placeholder="Type here..."/>
          <!-- Submit button for searching -->
          <input type="submit" value="Search" name="searchButton"/>
        </form>
      </header>       

    <!-- Login and Register for the site -->
      <div id="userStatus">
        <div id="login"><a href="login.php"> Login </a></div>
        <div id="register"><a href="register.php"> Register </a></div>
      </div>  

    <!-- List of main pages -->
      <nav>
        <ul>
          <li><a href="index.php"> Home </a></li>
          <li><a href="auctions.php"> All Auction Listings </a> </li>
          <li><a href="clothing.php"> Clothing </a></li>
          <li><a href="jewelry.php"> Jewelry </a></li>
          <li><a href="furniture.php"> Furniture </a></li>
          <li><a href="advSearch.php"> Advanced Search </a></li>
        </ul>
      </nav>  
        
        <h2><a href="auctions.php"> Browse our Auction Categories </a></h2>
          <dl> 
            <dt> <a href="jewelry.php"> Jewelry </a> </dt>
              <dd> Precious gems in necklaces, rings, and earrings</dd>
             <dt><a href="furniture.php"> Furniture </a></dt>
              <dd> Couches, tables, chairs, and other items to brighten the home</dd>
            <dt><a href="clothing.php"> Clothing </a></dt>
              <dd> Dresses, suits, casual wear, and more</dd>
          </dl>

        <h2> <a href="login.php"> List an item for auction </a></h2>

      <footer><h4> Have items that you wish to sell? <a href="login.php">Post an item for auction </a></h4></footer>
    </div>  
  </body>
</html>