<?php
//include_once "config.php";
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