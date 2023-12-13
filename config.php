<?php
session_start();
define("BURL", "http://localhost/arts/");
define("ASSETS", "http://localhost/arts/assets/");

define("BL", __DIR__."/");
// Connect to Database
include_once(BL."connDB.php");

// Add to wishlist
function add_to_wishlist($conn){
    if(isset($_POST)){
        $work_id = intval($_POST["work_id"]);
        $user_id = intval($_SESSION["user_id"]);
        if(isset($_POST["action"])){
            $sqlget = "DELETE FROM wishlist WHERE work_id=$work_id AND customer_id=$user_id";
            $stmtInsertwishlist = $conn->prepare($sqlget);
            $stmtInsertwishlist->execute();
        }
        else{

            $sqlget = "SELECT * FROM wishlist WHERE work_id=$work_id AND customer_id=$user_id";
            $stmtInsertwishlist = $conn->prepare($sqlget);
            $stmtInsertwishlist->execute();
            $rr = $stmtInsertwishlist->fetchAll(PDO::FETCH_ASSOC);
            if(count($rr) == 0){
                $sql = "INSERT INTO wishlist (work_id, customer_id) values ($work_id, $user_id)";
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
        $work_id = intval($_GET["Id"]);
        $user_id = intval($_SESSION["user_id"]);
        $sqlget = "SELECT * FROM wishlist WHERE work_id=$work_id AND customer_id=$user_id";
        $stmtInsertwishlist = $conn->prepare($sqlget);
        $stmtInsertwishlist->execute();
        $rr = $stmtInsertwishlist->fetchAll(PDO::FETCH_ASSOC);
        if(count($rr) > 0){
            return true;
        }
        else{
            return false;
        }
}