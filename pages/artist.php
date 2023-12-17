<?php
function wishlist($conn){
    if(isset($_SESSION["user_id"])){
        $id = intval($_GET['Id']);
        $r = check_in_wishlist($conn);
        if(!$r){
            $output = '<form action="?page=artist&Id='.$id.'" method="post">
                        <input type="hidden" name="item_id" value="'.$id.'">
                        <input type="hidden" name="item_type" value="artist">
                        <button type="submit" class="addtowishlist btn btn-dark" >
                            Add To Wishlist
                            <img src="./assets/images/heart.svg" width="30" height="30" class="" alt="">
                        </button>  
                    </form>';
        }
        else{
            $output = '<form action="?page=artist&Id='.$id.'" method="post">
                        <input type="hidden" name="item_id" value="'.$id.'">
                        <input type="hidden" name="item_type" value="artist">
                        <input type="hidden" name="action" value="removeFromWishlist">
                        <button type="submit" class="addtowishlist btn btn-dark" >
                            Remove From Wishlist
                            <img src="./assets/images/remove.svg" width="30" height="30" class="" alt="">
                        </button>  
                    </form>';
        }
    }
    else{
        $output = '';
    }
    return $output;
}
function artistData($conn){
    $id = intval($_GET['Id']);
    $sql = "SELECT * FROM `artists` WHERE ArtistID=$id";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $ima = "./assets/images/artists/medium/".$result[0]['ArtistID'].".jpg";
    $img = Check($ima);
    $output = '<div class="row">
                    <div class="col-4">
                        <div class="text-center bg-dark">
                            <img src="'.$img.'" title="'.$result[0]["FirstName"] .' '.$result[0]["LastName"].'" alt="'.$result[0]["FirstName"] .' '.$result[0]["LastName"].'">
                        </div>
                    </div>
                    <div class="col-8">
                        <h3><b>FUll Name</b> :  '.$result[0]["FirstName"] .' '.$result[0]["LastName"].'</h3>
                        <p><b>Year Of Birth</b> :  '.$result[0]["YearOfBirth"].'</p>
                        <p><b>Nationality</b> :  '.$result[0]["Nationality"].'</p>
                        <p><b>Details</b> :  '.$result[0]["Details"].'</p>
                        '.wishlist($conn).'
                    </div>
                </div>';
    return $output;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_GET['Id']);
    if (!empty($_POST['item_id']) && !empty($_POST['item_type'])) {
        add_to_wishlist($conn);
    }
}


function ArtworksperArtist($conn)
{
    $artist_works = "";
    $id = intval($_GET['Id']);
    $sql = "SELECT * From `artworks` WHERE `ArtistID`=$id";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row){
        $img ="";
        if(strlen($row["ImageFileName"]) < 6){
            $img = ASSETS."images/works/medium/0".$row["ImageFileName"].".jpg";
        }
        if(!file_exists($img) & strlen($row["ImageFileName"]) >= 6){
            $img = ASSETS."images/works/medium/" . $row["ImageFileName"] . ".jpg";
        }
        $artist_works .= '<div class="col-md-4">
                            <div class="card mb-3">
                                <div class="card-body p-0">
                                    <a href="/?page=work&Id=' . $row['ArtWorkID'] . '" title="'.$row['Title'].'" alt="'.$row['Title'].'">
                                        <img class="d-block w-100" src="' . $img . '" alt="' . $row['Title'] . '"style="width:1000;height:360px">
                                    </a>
                                    <h5 class="text-center py-2">'.$row["Title"].'</h5>
                                </div>
                                    
                                </div>
                            </div>';
    }
    return $artist_works;
}
?>
<div class="title bg-dark ">
    <div class="container">
        <h1 class="my-4 mt-5  text-white py-2">Artist </h1>
    </div>
</div>
<div class="container mb-4">
    <?= artistData($conn);?>
</div>
<div class="container">
    <div class="section-title">
        <h3>Works</h3>
    </div>
    <div class="row">
        <?= ArtworksperArtist($conn);?>
    </div>
</div>