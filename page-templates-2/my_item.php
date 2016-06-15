<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <meta charset="utf-8"/>
        <title>Item Details</title>
        <link rel="stylesheet" type="text/css" href="stylesheet.css"/>
    </head>
    <body>
        <ul class="navbar">
            <li><a href="#">&lt;-- Return(NotWorkingYet)</a></li>
            <li><a href="mainpage.php">Home</a></li>
            <li><a href="browse.php">Browse</a></li>
            <li><a href="list_item.php">List Item</a></li>
            <li><a href="logout_confirm.php">Logout</a></li>
            <li class="go_right">* Directly linking under the assumption that you've logged in :)</li>
        </ul>
        <div class="content">
        <h1>Generic Item Details</h1>
        <form action="browse.php">
            <input type="submit" value="<-- Back to Listings"/>
        </form>
         <div class="item_other">
                <a href="item.php"><img class="item_pic" alt="generic image" src="http://www.c4gallery.com/artist/bale-allen-tumbleweeds/bale-creek-allen-tumbleweed-4.jpg"/></a>
                <p>
                    <a href="item.php">Tumblweed! Great Condition!</a><br/>
                    Ending price: $5.00<br/>
                    This auction has ended<br/>
                    Other item details<br/>
                </p>
             <form action="cancel.php">
                    <input type="submit" value="Cancel Listing"/>
                </form>
                <form action="update.php">
                    <input type="submit" value="Update Listing"/>
                </form>
            </div>
        </div>
    </body>
</html>
