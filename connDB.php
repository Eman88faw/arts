<?php
$servername = "localhost";
$username = "EmaFaw";
$password = "123456";
try {
    $conn = new PDO("mysql:host=$servername;dbname=art", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     /*
    // SQL query to check if the table exists
     $tableExistsQuery = "SHOW TABLES LIKE 'wishlist'";
    $tableExistsResult = $conn->query($tableExistsQuery);

    if ($tableExistsResult->rowCount() == 0) {
        // Table does not exist, create it
        $createTableQuery = "CREATE TABLE wishlist (
                                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                                item_id INT (11),
                                item_type VARCHAR,
                                customer_id INT (11),
                                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                FOREIGN KEY (customer_id) REFERENCES customers(CustomerID)
                            )";

        $conn->exec($createTableQuery);

    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();*/
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}


?>
