<?php
session_start();
define("BURL", "http://localhost/arts/");
define("ASSETS", "http://localhost/arts/assets/"); 

define("BL", __DIR__ . "/");


// Connect to Database
include_once(BL . "connDB.php");
// Check if the wish list array is already in the session ---> Ahmed 
if (!isset($_SESSION['wishlist'])) {
    // If not, initialize an empty wish list array
    $_SESSION['wishlist'] = array();
    // [id, Title, Image, type, created_at]
}

// wishlist
function wishListItem()
{
    $result = "";
    $wishlistItem = $_SESSION['wishlist'];
    if(count($wishlistItem) > 0){
        foreach ($wishlistItem as $key => $item) {

            if ($item['type'] == 'work' && strlen($item["image"]) < 6) {
                $img = "./assets/images/" . $item['type'] . "s/medium/0" . $item["image"] . ".jpg";
            } else {
                $img = "./assets/images/" . $item['type'] . "s/medium/" . $item["image"] . ".jpg";
            }
            $result .= '<div class="whichListItem w-100 d-flex border-bottom mb-2 justify-content-between align-items-center pb-2">
                            <a href="?page=' . $item["type"] . '&Id=' . $item["id"] . '" >
                                <img src="' . $img . '" alt="">
                                <p>' . $item["title"] . '</p>
                            </a>
                            <form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
                                <input type="hidden" name="key" value="' . $key . '">
                                <input type="hidden" name="action" value="removeFromWishlist">
                                <button type="submit" class="addtowishlist btn btn-danger btn-sm" >
                                    <img src="./assets/images/remove.svg" width="24" height="24" class="img-icon" alt="">
                                </button>  
                            </form>
                        </div>';
        }
        $result .= '<div class="whichListItem w-100 d-flex mb-2 justify-content-center align-items-center pb-0">
                            <a href="?page=wishlist" class="btn btn-sm btn-primary">
                                SHOW ALL
                            </a>
                        </div>';
    }else{
        $result = "<div class='no-items text-center card-body'><h6>No Items Found.</h6><h6><small>please put any artwork or artist to dislay here</small></h6></div>";
    }


    return $result;
}

// Add to wishlist
function addToWishlist($item)
{
    if (!isInWishlist($item)) {
        $_SESSION['wishlist'][] = $item;
    }
}

// Function to remove an item from the wish list
function removeFromWishlist($item)
{
    // Use array_search to find the index of the item in the wish list
//    $index = array_search($item, $_SESSION['wishlist']);
    // If the item is found, remove it from the wish list
    unset($_SESSION['wishlist'][intval($item)]);
}

// Function to check if an item is in the wish list

function isInWishlist($item)
{
    return in_array($item, $_SESSION['wishlist']);
}

function getIndexInWishlist($item)
{
    return array_search($item, $_SESSION['wishlist']);
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(!empty($_POST['action'])){
        $ite = intval($_POST['key']);
        removeFromWishlist($ite);
    }
    if (!empty($_POST['id']) && !empty($_POST['type']) && empty($_POST['action'])) {
        $item = [
            "id" => $_POST['id'],
            "title" => $_POST['title'],
            "type" => $_POST['type'],
            "image" => $_POST['image'],
        ];
        addToWishlist($item);
    }
}





// Function to update the session variable with the current wish list

// check Data is Not Null takes table name and column name and column value
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

function get_column_from_tablke($conn, $table, $idName, $id, $column)
{
    $sql = "SELECT `$column` FROM `$table` WHERE `$idName`=$id LIMIT 1";
    $stmt = $conn->query($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result[0][$column];
}

