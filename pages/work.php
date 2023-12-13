<?php
include_once "config.php";
$id = intval($_GET['Id']);
function workData($conn){
    $id = intval($_GET['Id']);
    $sql = "SELECT * FROM `artworks` WHERE ArtWorkID=$id Limit 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $ima = "./assets/images/works/large/".$result[0]['ImageFileName'].".jpg";
    $img = "./assets/images/works/large/0" . $result[0]['ImageFileName'] . ".jpg";
    if(!file_exists($img)){
        $img = "./assets/images/works/large/" . $result[0]['ImageFileName'] . ".jpg";
    }
//    $img = Check($ima);

    return $output = '<div class="container mb-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="text-center bg-info p-2 mb-3">
                                <img src="'.$img.'" title="'.$result[0]["Title"] .'" alt="'.$result[0]["Title"] .'" style="max-width: 100%">
                            </div>
                        </div>
                        <div class="col-12">
                            <h3><b>Title</b> :  '.$result[0]["Title"] .'</h3>
                            <p><b>Description</b> :  '.$result[0]["Description"].'</p>
                            <p><b>GoogleLink</b> :  '.$result[0]["GoogleLink"].'</p>
                            
                                
                                '. wishlist($conn).'
                                
                          
                            
                        </div>
                    </div>
                </div>';
}
function wishlist($conn){
    $id = intval($_GET['Id']);
    $r = check_in_wishlist($conn);
    if(!$r){
        $output = '<form action="?page=work&Id='.$id.'" method="post">
                        <input type="hidden" name="work_id" value="'.$id.'">
                        <button type="submit" class="addtowishlist btn btn-dark" >
                            Add To Wishlist
                            <img src="./assets/images/heart.svg" width="30" height="30" class="" alt="">
                        </button>  
                    </form>';
    }
    else{
        $output = '<form action="?page=work&Id='.$id.'" method="post">
                        <input type="hidden" name="work_id" value="'.$id.'">
                        <input type="hidden" name="action" value="removeFromWishlist">
                        <button type="submit" class="addtowishlist btn btn-dark" >
                            Remove From Wishlist
                            <img src="./assets/images/remove.svg" width="30" height="30" class="" alt="">
                        </button>  
                    </form>';
    }
    return $output;
}
function artistsWorks ($conn){
    // Artist Name
    $id = intval($_GET['Id']);
    $sql = "SELECT ArtistID FROM `artworks` WHERE ArtWorkID=$id Limit 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $IID = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $aid = $IID[0];
    $sqlArtist = "SELECT `FirstName`, `LastName` FROM artists WHERE ArtistID=$aid Limit 1";
//    var_dump($sqlArtist);
    $stmt2 = $conn->prepare($sqlArtist);
    $stmt2->execute();
    $result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    // Others Works To the Artist
    $sq = "SELECT `ImageFileName`, `ArtWorkID`,`Title` From artworks WHERE `ArtistID` =$aid";
    $stmtworks = $conn->prepare($sq);
    $stmtworks->execute();
    $resultWorks = $stmtworks->fetchAll(PDO::FETCH_ASSOC);

    $htmlWorks='';
    foreach ($resultWorks as $row){
        $img = "./assets/images/works/medium/0" . $row['ImageFileName'] . ".jpg";
        if(!file_exists($img)){
            $img = "./assets/images/works/medium/" . $row['ImageFileName'] . ".jpg";
        }
//        $imgr = Check($imas);
        $htmlWorks .= '<div class="col-md-4">
                            <div class="card mb-3">
                                <div class="card-body p-0">
                                    <a class="w-100" href="/?page=work&Id=' . $row['ArtWorkID'] . '" title="'.$row['Title'].'" alt="'.$row['Title'].'">
                                        <img class="d-block w-100" src="' . $img . '" alt="' . $row['Title'] . '" style="max-width: 100%">
                                    </a>
                                    <h5 class="text-center py-2">'.$row["Title"].'</h5>
                                </div>

                                </div>
                            </div>';
    }
    // output HTML
    $output = '<div class="container mb-4">
                    <div class="section-title">
                        <h3>'.$result2[0]["FirstName"] .' '.$result2[0]["LastName"].' Works</h3>
                    </div>
                    <div class="row">
                        '.$htmlWorks.'
                    </div>
                </div>
                ';

    return $output;
}

function reviews($conn){
    // reviews
    $id = intval($_GET['Id']);
    $reviewsSQL = "SELECT * FROM reviews WHERE ArtWorkId=$id";
//    var_dump($reviewsSQL);
    $stmtreviews = $conn->prepare($reviewsSQL);
    $stmtreviews->execute();
    $reviews = $stmtreviews->fetchAll(PDO::FETCH_ASSOC);
//    var_dump($reviews);
    $output = '';
    foreach ($reviews as $review){
        $settings = '';
        if($_SESSION['user_id'] == $review["CustomerId"]){
            $settings = '<div class="setting">
                            <a href="?page=work&Id='.$_GET['Id'].'&delete-review='.$review["ReviewId"].'">Delete</a>
                            <a href="update=">Edit</a>
                        </div>';
        }
        $output .= '<div class="rev-cont bg-light p-2 mb-2 d-flex">
                        <div class="review-text">
                            <h5>Date : '.$review["ReviewDate"].'</h5>
                            <span>Rating : '.$review["Rating"].'</span>
                            <p>Comment : '.$review["Comment"].'</p>
                            '.$settings.'
                        </div>
                    </div>';
    }
    return $output;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_GET['Id']);
    // Check if it's a POST request
    if (!empty($_POST['comment'])) {
        addReview($conn);
    }
    if (!empty($_POST['work_id'])) {
        add_to_wishlist($conn);
    }
}

function addReview($conn){
    $date = date("Y-m-d H:i:s");
    $uid = $_SESSION['user_id'];
    $wid = intval($_POST['ArtWorkId']);
    $comment = htmlspecialchars($_POST['comment']);
    $Rating = intval($_POST['rating']);

    $insertReviewSQL = "INSERT INTO reviews (ArtWorkId, Comment, CustomerId, ReviewDate, Rating) VALUES (:artworkId, :Comment, :customerId, :ReviewDate, :Rating)";

    try {
        $stmtInsertReview = $conn->prepare($insertReviewSQL);
        $stmtInsertReview->bindParam(':artworkId', $wid, PDO::PARAM_INT);
        $stmtInsertReview->bindParam(':Rating', $Rating, PDO::PARAM_INT);
        $stmtInsertReview->bindParam(':Comment', $comment, PDO::PARAM_STR);
        $stmtInsertReview->bindParam(':customerId', $uid, PDO::PARAM_INT);
        $stmtInsertReview->bindParam(':ReviewDate', $date);

        if ($stmtInsertReview->execute()) {
            $_SESSION['alerts'] = ["type" =>"Success", "message" => "Inserted review Successfully"];
//            header("Location: index.php?page=work&Id=$wid");
        } else {
            echo "";
            $_SESSION['alerts'] = ["type" =>"Error", "message" => "Error inserting review!"];
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }


}
// delete-review
if(isset($_GET["delete-review"])){
    $id = intval($_GET["delete-review"]);

    $insertReviewSQL = "DELETE FROM reviews WHERE ReviewId=$id";

    try {
        $stmtInsertReview = $conn->prepare($insertReviewSQL);

        if ($stmtInsertReview->execute()) {
            $_SESSION['alerts'] = ["type" =>"Success", "message" => "Deleted review Successfully"];
//            header("Location: index.php?page=work&Id=$wid");
        } else {
            echo "";
            $_SESSION['alerts'] = ["type" =>"Error", "message" => "Error Deleting review!"];
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<div class="title bg-dark ">
    <div class="container">
        <h1 class="my-5  text-white py-2">Works </h1>
    </div>
</div>

    <?= workData($conn);?>

<div class="container">
    <div class="row">
        <div class="section-title">
            <h3>Reviews</h3>
        </div>
        <div class="row">
            <div class="h-500">
                <?= reviews($conn);?>
            </div>
        </div>
    </div>
</div>
<div class="bg-light py-3 my-5">
    <div class="container">
        <div class="row my-5">
            <h3>ADD Review</h3>
            <form action="?page=work&Id=<?= $_GET['Id']?>" method="post">
                <input type="hidden" name="ArtWorkId" value="<?=$_GET['Id']?>">
                <div class="form-group col-4">
                    <label for="">Rating</label>
                    <select name="rating" class="form-control" id="">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>

                </div>
                <div class="form-group col-12">
                    <label for="">Comment</label>
                    <textarea name="comment" class="form-control" id="" cols="30" rows="5"></textarea>
                </div>
                <div class="form-group">
                    <input type="submit" value="ADD Review" class="btn btn-primary mt-3">
                </div>
            </form>
        </div>
    </div>
</div>
<?= artistsWorks ($conn);?>
