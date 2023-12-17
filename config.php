<?php
session_start();
define("BURL", "http://localhost/arts/");
define("ASSETS", "http://localhost/arts/assets/");

define("BL", __DIR__."/");
// Connect to Database
include_once(BL."connDB.php");

// Add to wishlist
function wishListItem($conn)
{
    $result = "";
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM `wishlist` WHERE `customer_id`=$user_id";
    $stmt = $conn->query($sql);
    $stmt->execute();
    $wishlistItem = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($wishlistItem as $item){
        $id = $item['item_id'];
        if($item['item_type'] == "work"){
            $sql = "SELECT ImageFileName, Title FROM `artworks` WHERE `ArtWorkID`=$id";
            $stmt = $conn->query($sql);
            $stmt->execute();
            $workItem = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if($workItem[0]["ImageFileName"] < 6){
                $img = "./assets/images/works/medium/0".$workItem[0]["ImageFileName"].".jpg";
            }
            else{
                $img = "./assets/images/works/medium/".$workItem[0]["ImageFileName"].".jpg";
            }
            $result .= '<a href="/?page=work&Id='.$id.'" class="w-100 d-flex border-bottom mb-2">
                            <img src="'.$img.'" alt="">
                            <p>'.$workItem[0]["Title"].'<small class="d-block">'.$item["created_at"].'</small></p>
                            
                        </a>';
        }
        else{
            $sql = "SELECT FirstName, LastName FROM `artists` WHERE `ArtistID`=$id";
            $stmt = $conn->query($sql);
            $stmt->execute();
            $workItem = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $img = "./assets/images/artists/medium/".$id.".jpg";

            $result .= '<a href="/?page=artist&Id='.$id.'" class="w-100 d-flex border-bottom mb-2">
                            <img src="'.$img.'" alt="">
                            <p>'.$workItem[0]["FirstName"].' '. $workItem[0]["LastName"] .' <small class="d-block">'.$item["created_at"].'</small></p>
                          
                        </a>';
        }
    }


    return $result;

}

// Add to wishlist
function add_to_wishlist($conn)
{
    if (isset($_POST)) {
        $item_id = intval($_POST["item_id"]);
        $item_type = strtolower($_POST["item_type"]);
        $user_id = intval($_SESSION["user_id"]);
        if (isset($_POST["action"])) {
            $sqlget = "DELETE FROM wishlist WHERE item_id=$item_id AND item_type='$item_type' AND customer_id=$user_id";
            $stmtInsertwishlist = $conn->prepare($sqlget);
            $stmtInsertwishlist->execute();
        } else {
            $sqlget = "SELECT * FROM wishlist WHERE item_id=$item_id AND item_type='$item_type' AND customer_id=$user_id";
            $stmtInsertwishlist = $conn->prepare($sqlget);
            $stmtInsertwishlist->execute();
            $rr = $stmtInsertwishlist->fetchAll(PDO::FETCH_ASSOC);
            if (count($rr) == 0) {
                $sql = "INSERT INTO wishlist (item_id, item_type, customer_id) values ($item_id, '$item_type', $user_id)";
                try {
                    $stmtInsertwishlist = $conn->prepare($sql);
                    $stmtInsertwishlist->execute();
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
            }
        }

    }
}

// check for work in wishlist
function check_in_wishlist($conn)
{
    if (isset($_SESSION["user_id"])) {
        $work_id = intval($_GET["Id"]);
        $user_id = intval($_SESSION["user_id"]);
        $item_type = "work";
        if($_GET["page"] == "artist"){
            $item_type = "artist";
        }
        $sqlget = "SELECT * FROM wishlist WHERE item_id=$work_id AND item_type='$item_type' AND customer_id=$user_id";
        $stmtInsertwishlist = $conn->prepare($sqlget);
        $stmtInsertwishlist->execute();
        $rr = $stmtInsertwishlist->fetchAll(PDO::FETCH_ASSOC);
        if (count($rr) > 0) {
            return true;
        } else {
            return false;
        }
    }

}

// check Data is Not Null
function chech_for_Data($conn, $table, $idName, $id)
{
    $sql = "SELECT * FROM `$table` WHERE `$idName`=$id";
    $stmt = $conn->query($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($result == null) {
        return false;
    } else {
        return true;
    }
}