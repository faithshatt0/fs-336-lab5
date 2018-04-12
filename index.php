<?php
    session_start();
    include 'functions.php';
    include 'database.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <title>Products Page</title>
    </head>
    <body>
    <div class='container'>
        <div class='text-center'>
            
            <!-- Bootstrap Navagation Bar -->
            <nav class='navbar navbar-default - navbar-fixed-top'>
                <div class='container-fluid'>
                    <div class='navbar-header'>
                        <a class='navbar-brand' href='#'>Shopping Land</a>
                    </div>
                    <ul class='nav navbar-nav'>
                        <li><a href='index.php'>Home</a></li>
                        <li><a href='scart.php'>Cart</a></li>
                    </ul>
                </div>
            </nav>
            <br /> <br /> <br />
            
            <!-- Search Form -->
            <form enctype="text/plain">
                <div class="form-group">
                    <h1>OtterMart</h1>
                    </br>
                    Product: <input type="text" name="query" id="pName" placeholder="Name">
                    </br></br>
                    Category 
                    <select name="category">
                        <?php echo getCategoriesHTML(); ?>
                    </select>
                    </br></br>
                    Price: From  
                    <input type="text" name="fromPrice" id="fromprice" size="4">
                    To <input type="text" name="toPrice" id="toprice" size="4">
                    </br></br>
                    Order Results By: 
                    <input type="radio" name="order" value="byName" /> Product Name
                    </br>
                    <input type="radio" name="order" value="byPrice" /> Price
                    </br></br>
                    <input type="checkbox" name="displayPics" value="true" /> Display Product Pictures
                    </br></br>
                </div>
                <input type="submit" name="search-submitted" value="Search" class="btn btn-default">
                <br /><br />
            </form>
            
            <!-- Display Search Results -->
            
            </br>
            <?php
                
                if(isset($_POST['itemName'])) {
                    //creating an array to hold an items properties
                    $newItem = array();
                    $newItem['name'] = $_POST['itemName'];
                    $newItem['id'] = $_POST['itemId'];
                    $newItem['price'] = $_POST['itemPrice'];
                    $newItem['image'] = $_POST['itemImage'];
                    
                    array_push($_SESSION['cart'], $newItem);
                }
                
                if(!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = array();
                }
                
                $category = '';
                $query = '';
                $toPrice = '';
                $fromPrice = '';
                $order = '';
                $displayPics = false;
                
                if (isset($_GET["category"]) && !empty($_GET["category"])) {
                    $category = $_GET["category"]; 
                }
                
                if (isset($_GET["fromPrice"]) && !empty($_GET["fromPrice"])) {
                    $fromPrice =  $_GET["fromPrice"]; 
                }
                
                if (isset($_GET["toPrice"]) && !empty($_GET["toPrice"])) {
                    $toPrice = $_GET["toPrice"];
                }
                
                if (isset($_GET["order"]) && !empty($_GET["order"])) {
                    $order = $_GET["order"];
                }
                
                if (isset($_GET["displayPics"]) && !empty($_GET["displayPics"])) {
                    $displayPics = true;
                }
                
                if (isset($_GET['query'])) {
                    $query = $_GET['query'];
                }

                if(isset($_GET['search-submitted'])) {
                    $items = getMatchingItems($query, $category, $fromPrice, $toPrice, $order, $displayPics);
                }
                
                displayResults($items);
            ?>
            </br>
        </div>
    </div>
    </body>
</html>