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

<!-- Advanced Search bar -->
  <h2> Advanced Search</h2>
    <form action="advResults.php" method="get">
      <ul>
        <!-- To be implemented in next iteration -->
<!--       <li>Only show auctions that began less than:
        <select name="hours">
         <option value="1hr"> an hour ago</option>
         <option value="2hr"> 2 hours ago</option>
         <option value="3hr"> 3 hours ago</option>
         <option value="1day"> 1 day ago</option>         
         <option value="any"> Any Time </option>
        </select> </li> -->

      <li>Categories:</li>
      <li><input type="checkbox" name="category1" value="Clothing" checked/> Clothing</li>
      <li><input type="checkbox" name="category2" value="Furniture" /> Furniture</li>
      <li><input type="checkbox" name="category3" value="Jewelry" /> Jewelry</li>


      <li>Seller: <input type="text" name="sellerName" /></li>
      <li> Note: For the item name search to be valid, the full title of the item must be entered. </li>
      <li>Item name: <input type="text" name="searchItemName" /></li>
    </ul>
      <!-- Submit button -->
     <div class="transfer"><input type="submit" value="Search"/></div> 
    </form>  

      <footer><h4> Have items that you wish to sell? <a href="login.html">Post an item for auction </a></h4></footer>
    </div>  
  </body>
</html>