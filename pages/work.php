<?php
include_once "config.php";
$id = intval($_GET['Id']);
$longitude = 0;
$latitude = 0;
function workData($conn){
    global $longtitude, $latitude;
    $id = intval($_GET['Id']);
    $sql = "SELECT * FROM `artworks` WHERE ArtWorkID=$id Limit 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(strlen($result[0]['ImageFileName']) < 6){
        $img = "0".$result[0]['ImageFileName'].".jpg";
    }else{
        $img = $result[0]['ImageFileName'] . ".jpg";
    }

    $galleryId = $result[0]["GalleryID"];
    $sqlGallery = "SELECT * FROM `galleries` WHERE GalleryID=$galleryId Limit 1";
    $stmtGallery = $conn->prepare($sqlGallery);
    $stmtGallery->execute();
    $resultGallery = $stmtGallery->fetchAll(PDO::FETCH_ASSOC);
    $longitude = $resultGallery[0]["Longitude"];
    $latitude = $resultGallery[0]["Latitude"];

    // Calculate bounds for the bounding box
    $delta = 0.002; // Adjust this value to zoom in or out
    $minLatitude = $latitude - $delta;
    $maxLatitude = $latitude + $delta;
    $minLongitude = $longitude - $delta;
    $maxLongitude = $longitude  + $delta;

//    $img = Check($ima);
    $imageLarge = "./assets/images/works/large/".$img;
    return $output = '<div class="container mb-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="text-center bg-info p-2 mb-3">
                                <img onclick=\'displayLarge("'.$imageLarge.'")\' src="./assets/images/works/medium/'.$img.'" class="imgWork" title="'.$result[0]["Title"] .'" alt="'.$result[0]["Title"] .'">
                            </div>
                        </div>
                        <div class="col-12">
                            <h3><b>Title</b>       :  '.$result[0]["Title"] .'</h3>
                            <p><b>Description</b>  :  '.$result[0]["Description"].'</p>
                            '. wishlist($conn).'
                        </div>
                        <div class="col-12 my-3">
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th>Excerpt</th>
                                    <td>'.$result[0]["Excerpt"].'</td>
                                </tr>
                                 <tr>
                                    <th>ArtWorkType</th>
                                    <td>'.$result[0]["ArtWorkType"].'</td>
                                </tr>
                                 <tr>
                                    <th>YearOfWork</th>
                                    <td>'.$result[0]["YearOfWork"].'</td>
                                </tr>
                                 <tr>
                                    <th>Diamention</th>
                                    <td>'.$result[0]["Width"].'*'.$result[0]["Height"].'</td>
                                </tr>
                                 <tr>
                                    <th>Medium</th>
                                    <td>'.$result[0]["Medium"].'</td>
                                </tr>
                                 <tr>
                                    <th>OriginalHome</th>
                                    <td>'.$result[0]["OriginalHome"].'</td>
                                </tr>
                                 <tr>
                                    <th>GalleryID</th>
                                    <td>'.$result[0]["GalleryID"].'</td>
                                </tr>
                                 <tr>
                                    <th>ArtWorkLink</th>
                                    <td>'.$result[0]["ArtWorkLink"].'</td>
                                </tr>
                                 <tr>
                                    <th>GoogleLink</th>
                                    <td>'.$result[0]["GoogleLink"].'</td>
                                </tr>

                            </table>
                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Look at map
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                    <iframe width="425" height="350" src="https://www.openstreetmap.org/export/embed.html?bbox='.$minLongitude.'%2C'.$minLatitude.'%2C'.$maxLongitude.'%2C'.$maxLatitude.'&amp;layer=mapnik" style="border: 1px solid black"></iframe><br/><small><a href="https://www.openstreetmap.org/#map=19/'.$latitude.'/'.$longitude.'View Larger Map</a></small>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
}
function wishlist($conn){
    if(isset($_SESSION["user_id"])){
        $id = intval($_GET['Id']);
        $r = check_in_wishlist($conn);
        if(!$r){
            $output = '<form action="?page=work&Id='.$id.'" method="post">
                        <input type="hidden" name="item_id" value="'.$id.'">
                        <input type="hidden" name="item_type" value="work">
                        <button type="submit" class="addtowishlist btn btn-dark" >
                            Add To Wishlist
                            <img src="./assets/images/heart.svg" width="30" height="30" class="" alt="">
                        </button>  
                    </form>';
        }
        else{
            $output = '<form action="?page=work&Id='.$id.'" method="post">
                        <input type="hidden" name="item_id" value="'.$id.'">
                        <input type="hidden" name="item_type" value="work">
                        <input type="hidden" name="action" value="removeFromWishlist">
                        <button type="submit" class="addtowishlist btn btn-dark" >
                            Remove From Wishlist
                            <img src="./assets/images/remove.svg" width="20" height="30" class="" alt="">
                        </button>  
                    </form>';
        }
    }
    else{
        $output = '';
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
    $sqlArtist = "SELECT `FirstName`, `LastName`,`ArtistID` FROM artists WHERE ArtistID=$aid Limit 1";
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
        $htmlWorks .= '<div class="col-md-3">
                            <div class="card mb-3">
                                <div class="card-body p-0">
                                
                                    <a class="w-100" href="/?page=work&Id=' . $row['ArtWorkID'] . '" title="'.$row['Title'].'" alt="'.$row['Title'].'">
                                        <img class="d-block w-100" src="' . $img . '" alt="' . $row['Title'] . '" style="max-width: 100%; height: 250px">
                                    </a>
                                    <h5 class="text-center py-2">'.$row["Title"].'</h5>
                                </div>

                                </div>
                            </div>';
    }
    // output HTML
    $output = '<div class="container mb-4">
                    <div class="section-title">
                                    
                        <h3>'.$result2[0]["FirstName"] .' '.$result2[0]["LastName"].' Works </h3>
                        <a class="float-end btn btn-sm btn-info position-relative" href="/?page=artist&Id='.$result2[0]["ArtistID"].'">'.$result2[0]["FirstName"] .' '. $result2[0]['LastName'].' Page</a>
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
    if(isset($_SESSION["user_id"])){
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
                            <a class="btn btn-sm btn-danger" href="?page=work&Id='.$_GET['Id'].'&delete-review='.$review["ReviewId"].'">
                                <img src="./assets/images/remove.svg" width="15" alt="">
                                Delete
                            </a>
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
    }
    else{
        $output = '<div class="w-100 bg-light p-3 text-center"><h4>you have no permission to show reviews. Please login to get this feature </h4><a class="d-inline-block mt-2 btn btn-primary btn-sm" href="?page=login">Login</a></div>';
    }

    return $output;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_GET['Id']);
    // Check if it's a POST request
    if (!empty($_POST['comment'])) {
        addReview($conn);
    }
    if (!empty($_POST['item_id']) && !empty($_POST['item_type'])) {
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
<?php if(isset($_SESSION["user_id"])): ?>
<div class="bg-light py-3 my-5">
    <div class="container">
        <div class="row my-5">
            <h3>ADD Review</h3>
            <form action="?page=work&Id=<?= $_GET['Id']?>" method="post">
                <input type="hidden" name="ArtWorkId" value="<?=$_GET['Id']?>">
                <div class="form-group col-4">
                    <label for=""  >Rating</label>
                    <select name="rating"  class="form-control" id="">
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
<?php endif;?>
<?= artistsWorks ($conn);?>


<div id="imgLargeView" class="imgLargeView">
    <span id="closeModal" onclick="closeModal();">X</span>
    <img src="" id="imageLarge" alt="">
</div>
