<?php
include_once "connDB.php";
function TopWorks($conn)
{
    $data = '';
    $sql = "SELECT Title,ar.ArtWorkID, ar.ArtWorkID,ImageFileName,AVG(Rating)
				FROM ArtWorks ar, Reviews r where ar.ArtWorkID= r.ArtWorkID
				GROUP BY ar.ArtWorkID ORDER BY AVG(Rating) DESC LIMIT 3";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row){
        $double_number = doubleval($row["AVG(Rating)"]);
        $rate = intval($double_number);
        $stars = '';
        for ($i=0; $i < $rate; $i++){
            $stars .= '<img src="/assets/images/star.svg" class="star-icon" alt="">';
        }
        if($double_number > $rate){
            $stars.= '<img src="/assets/images/star-half.svg" class="star-icon" alt="">';
        }
        $html = '<div class="col-md-4">
            <div class="card">
                <div class="card-image">
                    <img src="/assets/images/works/small/0'.$row["ImageFileName"]. '.jpg" class="card-img-top" alt="...">
                    <small class="d-block stars">
                        '.$stars.'
                    </small>
                </div>
    
                <div class="card-body">
                    <h5 class="card-title">' .$row["Title"].'</h5>
                    <a href="?page=work&Id='.$row["ArtWorkID"].'" class="btn btn-dark">Show Details</a>
                </div>
            </div>
        </div>';

        $data .= $html;
    }
    return $data;
}
?>
<div class="row">
    <div class="section-title">
        <h3>Top Works</h3>
    </div>
    <?php echo TopWorks($conn);?>
</div>