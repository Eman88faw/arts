<?php
include_once "connDB.php";
function star($s)
{
    $output = '';

    if(is_double($s)){
        $i = floor($s);
        for ($item = 0; $item < $i; $item++){
            $output.= '<img src="/assets/images/star.svg" class="star-icon" alt="">';
        }
        $output .= '<img src="/assets/images/star-half.svg" class="star-icon" alt="">';
    }
    else{
        for ($item = 0; $item < $s; $item++){
            $output.= '<img src="/assets/images/star.svg" class="star-icon" alt="">';
        }
    }
    $html = '<small class="d-block stars">'.$output.'</small>';
    return $html;
}
function TopRe($conn)
{
    $output = "";
    $sql = "SELECT * FROM reviews r, artworks aw,customers c
						where aw.ArtWorkID=r.ArtWorkId
						and c.CustomerID=r.CustomerID
						ORDER BY r.Rating DESC limit 3";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($result as $row){
        $s = $row['Rating'];
        $stars = star($s);
        $html = '<div class="col-md-4">
            <div class="card">
                <div class="card-image">
                    <img src="./assets/images/artists/medium/'.$row["ArtistID"].'.jpg" class="card-img-top" alt="...">
                      '.$stars.'
                </div>
    
                <div class="card-body">
                    <h5 class="card-title">' . $row['LastName'] . '</h5>
                    <p id="card-text">' . substr($row['Comment'], 0, 100) . '</p>
                    <a href="?page=artist&Id='.$row["ArtistID"].'" class="btn btn-dark">Show Details</a>
                </div>
            </div>
        </div>';

        $output .= $html;
    }

    return $output;
}


?>



<div class="row mt-5">
    <div class="section-title">
        <h3>Most Reviewed</h3>
    </div>
    <?php echo TopRe($conn);?>
</div>