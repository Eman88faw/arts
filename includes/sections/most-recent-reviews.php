<?php
include_once "connDB.php";
function MostReview($conn)
{
    $output = "";
    $last = "";

    $sql = "SELECT * FROM reviews r, artworks aw,customers c
						where aw.ArtWorkID=r.ArtWorkId
						and c.CustomerID=r.CustomerID
						ORDER BY r.ReviewDate ASC limit 3";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($result as $row){
        $s = $row['Rating'];
        $stars = star($s);
        $html = '<div class="col-md-4">
            <div class="card">
                <div class="card-image">
                    <img src="/assets/images/works/small/0'.$row["ImageFileName"].'.jpg" class="card-img-top" alt="...">
                      '.$stars.'
                </div>
    
                <div class="card-body">
                    <h5 class="card-title">' . $row['LastName'] . '
                     <small class="float-end">
                        <img src="/assets/images/eye.svg" width="20" alt=""> ' .$s.'
                    </small>
                    </h5>
                    <p id="card-text">' . substr($row['Comment'], 0, 100) . '</p>
                    <a href="?workPage='.$row["ArtWorkID"].'" class="btn btn-dark">Show Details</a>
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
        <h3>Most Recent Reviews</h3>
    </div>
    <?= MostReview($conn);?>
<!--    <div class="col-md-4">-->
<!--        <div class="card">-->
<!--            <div class="card-image">-->
<!--                <img src="/images/slider/3.jpg" class="card-img-top" alt="...">-->
<!--                <small class="d-block stars">-->
<!--                    <img src="/images/star.svg" class="star-icon" alt="">-->
<!--                    <img src="/images/star.svg" class="star-icon" alt="">-->
<!--                    <img src="/images/star.svg" class="star-icon" alt="">-->
<!--                    <img src="/images/star.svg" class="star-icon" alt="">-->
<!--                    <img src="/images/star-half.svg" class="star-icon" alt="">-->
<!--                </small>-->
<!--            </div>-->
<!---->
<!--            <div class="card-body">-->
<!--                <h5 class="card-title">-->
<!--                    Card title-->
<!--                    <small class="float-end">-->
<!--                        <img src="/images/eye.svg" width="20" alt=""> 200-->
<!--                    </small>-->
<!--                </h5>-->
<!--                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>-->
<!--                <a href="#" class="btn btn-dark">Show Details</a>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="col-md-4">-->
<!--        <div class="card">-->
<!--            <div class="card-image">-->
<!--                <img src="/images/slider/2.jpg" class="card-img-top" alt="...">-->
<!--                <small class="d-block stars">-->
<!--                    <img src="/images/star.svg" class="star-icon" alt="">-->
<!--                    <img src="/images/star.svg" class="star-icon" alt="">-->
<!--                    <img src="/images/star.svg" class="star-icon" alt="">-->
<!--                    <img src="/images/star.svg" class="star-icon" alt="">-->
<!--                    <img src="/images/star-half.svg" class="star-icon" alt="">-->
<!--                </small>-->
<!--            </div>-->
<!---->
<!--            <div class="card-body">-->
<!--                <h5 class="card-title">Card title-->
<!--                    <small class="float-end">-->
<!--                        <img src="/images/eye.svg" width="20" alt="">-->
<!--                        200-->
<!--                    </small>-->
<!--                </h5>-->
<!--                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>-->
<!--                <a href="#" class="btn btn-dark">Show Details</a>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="col-md-4">-->
<!--        <div class="card">-->
<!--            <div class="card-image">-->
<!--                <img src="/images/slider/1.jpg" class="card-img-top" alt="...">-->
<!--                <small class="d-block stars">-->
<!--                    <img src="/images/star.svg" class="star-icon" alt="">-->
<!--                    <img src="/images/star.svg" class="star-icon" alt="">-->
<!--                    <img src="/images/star.svg" class="star-icon" alt="">-->
<!--                    <img src="/images/star.svg" class="star-icon" alt="">-->
<!--                    <img src="/images/star-half.svg" class="star-icon" alt="">-->
<!--                </small>-->
<!--            </div>-->
<!---->
<!--            <div class="card-body">-->
<!--                <h5 class="card-title">-->
<!--                    Card title-->
<!--                    <small class="float-end">-->
<!--                        <img src="/images/eye.svg" width="20" alt="">-->
<!--                        200-->
<!--                    </small>-->
<!--                </h5>-->
<!--                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>-->
<!--                <a href="#" class="btn btn-dark">Show Details</a>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->

</div>