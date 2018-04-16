<?php

mysql://bc97e9668307a6:1f45438a@us-cdbr-iron-east-05.cleardb.net/heroku_f1786d645f9bdda?reconnect=true

function getDatabaseConnection() {
    
    $host = "us-cdbr-iron-east-05.cleardb.net";
    $username = "bc97e9668307a6";
    $password = "1f45438a";
    $dbname = "heroku_f1786d645f9bdda"; 
    
    // Create connection
    $dbConn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $dbConn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $dbConn; 
}

function insertItemsIntoDB($items) {
    if (!$items) return;
    
    $db = getDatabaseConnection();
    
    foreach($items as $item) {
        $itemName = $item['name'];
        $itemPrice = $item['salePrice'];
        $itemImage = $item['thumbnailImage'];
        
        $sql = "INSERT INTO item (item_id, name, price, image_url) VALUES (NULL, :itemName, :itemPrice, :itemImage)";
        $statement = $db->prepare($sql);
        $statement->execute(array(
            itemName => $itemName,
            itemPrice => $itemPrice,
            itemImage => $itemImage
        ));
    }
}

function getMatchingItems($query, $category, $fromPrice, $toPrice, $order, $displayPics) {
    $db = getDatabaseConnection();
    $imgSQL = $displayPics ? ', item.image_url' : "";
    
    $sql = "SELECT DISTINCT item.item_id, item.name, item.price $imgSQL FROM item INNER JOIN item_category ON item.item_id = item_category.item_id INNER JOIN category ON item_category.category_id =category.category_id  WHERE 1";
    
    if(!empty($query)) {
        $sql .= " AND name LIKE '%$query%'";
    }
    
    if(!empty($category)) {
        $sql .= " AND category.category_name = '$category'";
    }
    
    if(!empty($fromPrice)) {
        $sql .= " AND item.price >= '$fromPrice'";
    }
    
    if(!empty($toPrice)) {
        $sql .= " AND item.price <= '$toPrice'";
    }
    
    if(!empty($order)) {
        if($order == 'byName') {
            $byThis = 'item.name';
        } else {
            $byThis = 'item.price';
        }
        
        $sql .= " ORDER BY $byThis";
    }
    
    $statement = $db->prepare($sql);
    
    $statement->execute();
    
    $records = $statement->fetchAll();
    
    return $records;
}

function getCategoriesHTML() {
    $db = getDatabaseConnection(); 
    $categoriesHTML = "<option value=''></option>";  // User can opt to not select a category 
    
    $sql = "SELECT category_name FROM category"; 
    
    $statement = $db->prepare($sql); 
    
    $statement->execute(); 
    
    $records = $statement->fetchAll(PDO::FETCH_ASSOC); 
    
    foreach ($records as $record) {
        $category = $record['category_name']; 
        $categoriesHTML .= "<option value='$category'>$category</option>"; 
    }
    
    return $categoriesHTML; 
}

function addCategoriesForItems($itemStart, $itemEnd, $category_id) {
    $db = getDatabaseConnection(); 
    
    for ($i = $itemStart; $i <= $itemEnd; $i++) {
        $sql = "INSERT INTO item_category (grouping_id, item_id, category_id) VALUES (NULL, '$i', '$category_id')";
        $db->exec($sql);
    }
}

?>