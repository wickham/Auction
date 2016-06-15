<?php session_start(); ?>
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

  <!-- USER FOR THIS SESSION -->
  <?php $_SESSION['userName'] = 'Arthur Dent'; ?>

<!-- Terms and Conditions -->
   <div class="regForm">
     <form>
        <ul>
         <li><p> THE LEGAL AGREEMENTS SET OUT BELOW GOVERN YOUR USE OF ACME AUCTIONS, INC AND ALL OF ITS SUBSIDIERIES.
          All auctions are final. Returns and withdrawls must be claimed within 30 days of an auction's end.</p> </li>
         <li><input type="radio" name="terms" /> I agree to the terms and conditions stated above </li>
        </ul> 
     </form> 
   </div>  

<!-- Registration form -->
  <div class="regForm">
    <form method="post" action="myAccount.php">
      <ul> 
         <li>First Name: <input type="text" name="fname" required="required"/> </li>
         <li>Last Name: <input type="text" name="lname" required="required"/></li>
         <li>Email address: <input type="email" name="userEmail" required="required"/></li>
      </ul>
         <h4> Usernames must be at least 6 letters/numbers with no spaces. </h4>
      <ul>   
         <li>Username: <input type="text" name="username" required="required"/></li>
         <li>Password: <input type="password" name="password" required="required"/></li>
         <li>Confirm Password: <input type="password" name="password" required="required"/></li>
      </ul>    

      <!-- Submit button -->
      <input type="submit" value="Enter">
    </form> 
  </div>   


      <footer><h4> Have items that you wish to sell? <a href="login.php">Post an item for auction </a></h4></footer>
    </div>  
  </body>
</html>