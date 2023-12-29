
<?php
function wishlist($conn){
    if(isset($_SESSION["user_id"])){
        $id = intval($_GET['Id']);
        $item = [
            "id" => $id,
            "title" => get_column_from_tablke($conn, "artists", "ArtistID", $id, "FirstName") .' '. get_column_from_tablke($conn, "artists", "ArtistID", $id, "LastName"),
            "type" => "artist",
            "image" => $id,
        ];
        $r = isInWishlist($item);
        if(!$r){
            $output = '<form action="?page=artist&Id='.$id.'" method="post">
                        <input type="hidden" name="id" value="'.$item['id'].'">
                        <input type="hidden" name="title" value="'.$item['title'].'">
                        <input type="hidden" name="type" value="'.$item['type'].'">
                        <input type="hidden" name="image" value="'.$item['id'].'">
                        <button type="submit" class="addtowishlist btn btn-dark" >
                            Add To Wishlist
                            <img src="./assets/images/heart.svg" width="30" height="30" class="" alt="">
                        </button>  
                    </form>';
        }
        else{
            $output = '<form action="?page=artist&Id='.$id.'" method="post">
                        <input type="hidden" name="key" value="'.getIndexInWishlist($item).'">
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
function artistAYData($conn){
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
                                    <a href="?page=work&Id=' . $row['ArtWorkID'] . '" title="'.$row['Title'].'" alt="'.$row['Title'].'">
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
<div class=" bg-dark mb-3">
    <div class="container title">
        <h1 class="text-white ">Artist </h1>
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