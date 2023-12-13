<?php
include "connDB.php";
include 'includes/header.php';
$ar = "select * from artists order by FirstName ASC";
$c = $conn->query($ar);

function Bestart($conn)
{
    $best = "SELECT title, ar.ArtWorkID, ar.ArtWorkID,ImageFileName, count(ar.ArtWorkID) AS TotalQuantity 
			FROM ArtWorks ar
			GROUP BY ar.ArtWorkID ORDER BY count(ar.ArtWorkID) DESC LIMIT 5";
    var_dump($best);
    $bestsell = $conn->query($best);

    return $bestsell;
}
function slides($conn)
{
    $output = '';
    $slider_indicators ='';

    $count = 0;
    $bestsell = Bestart($conn);
    while ($row = $bestsell->fetch()) {
        if ($count == 0) {
            if(strlen($row['ImageFileName']) < 6){
                $ima = "./assets/images/works/large/0" . $row['ImageFileName'] . ".jpg";
            }
            else{
                $ima = "./assets/images/works/large/" . $row['ImageFileName'] . ".jpg";
            }

            //$img = Check($ima);
            $output .= ' <div class="carousel-item active" >
									<a href="?page=work&Id=' . $row['ArtWorkID'] . '"><img class="d-block mx-auto" 
									src="' . $ima . '"
									  alt="' . $row['title'] . '"style="width:auto;height:100%"></a>
								</div>';
            $slider_indicators .= '<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="'.$count.'" class="active" aria-current="true" aria-label="Slide '.($count+1).'"></button>';
        } else {
            if(strlen($row['ImageFileName']) <6 ){
                $ima = "./assets/images/works/large/0" . $row['ImageFileName'] . ".jpg";
            }
            else{
                $ima = "./assets/images/works/large/" . $row['ImageFileName'] . ".jpg";
            }
            $img = Check($ima);
            $output .= ' <div class="carousel-item">
									<a href="?page=work&Id=' . $row['ArtWorkID'] . '"><img class="d-block mx-auto" 
									src="' . $img . '"
									  alt="' . $row['title'] . '"style="width:auto;height:100%"></a>
								</div>';
            $slider_indicators .= '<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="'.$count.'" class="" aria-current="true" aria-label="Slide '.($count+1).'"></button>';

        }
        $count++;
    }
    $slider = ' <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
        '.$slider_indicators.'
        </div>
        <div class="carousel-inner">
           '.$output.'
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>';
    return $slider;
}

function ArtworksperArtist($conn)
{
    $best = "SELECT a.LastName,a.ArtistID,avg(r.Rating)  FROM reviews r, artists a, artworks aw 
					where a.ArtistID=aw.ArtistID
					and aw.ArtWorkID=r.ArtWorkId
					GROUP by a.ArtistID  
					ORDER BY AVG(r.Rating) DESC";
    $Top = $conn->query($best);
    var_dump($Top);
    return $Top;
}
function famousArtists($conn)
{
    $output = "";
    $result = ArtworksperArtist($conn);

    while ($row = $result->fetch()) {
        $ima = "./assets/images/artists/medium/" . $row['ArtistID'] . ".jpg";
        $img = Check($ima);
        $output .= ' <div class="item">
									<a href="myownart.php?Id=' . $row['ArtistID'] . '"><img class="d-block w-100" 
									src="' . $img . '"
									  alt="' . $row['LastName'] . '"style="width:1000;height:360px"></a>
								</div>';
    }
    return $output;
}
function twoLastReviews($conn)
{
    $output = "";
    $last = "SELECT * FROM reviews r, artworks aw,customers c
						where aw.ArtWorkID=r.ArtWorkId
						and c.CustomerID=r.CustomerID
						ORDER BY r.ReviewDate DESC limit 2";
    $Top = $conn->query($last);
    while ($row = $Top->fetch()) {
        $s = $row['Rating'];
        $stars = star($s);
        $output .= '<center><div class="card" style="width:50%;margin-top:5px;">
                                    <div class="card-body">
                                        <div class=" icons text-center">' . $stars . '
                                        </div>
                                        <a href="maps.php?Id=' . $row['ArtWorkID'] . '"><h5 class="card-title">' . $row['LastName'] . '</h5>
                                        </a>
										<i class="fa fa-quote-right"></i>
										<p id="card-text">
										' . substr($row['Comment'], 0, 103) . '
										</p>
                                        <i class="fa fa-quote-right"></i>
                                    </div>
                                </div></center>';
    }

    return $output;
}
?>

<div class="slider">
    <?php echo slides($conn); ?>
</div>

<div class="container py-5">
    <!--    Top Works Start-->

    <?php include('includes/sections/top-work.php');?>
    <!--    Top Works end-->
    <!--    most-reviewed artists start-->
    <?php include('includes/sections/most-reviewed.php');?>
    <!--    most-reviewed artists end-->
    <!-- most-recent reviews start -->
    <?php include('includes/sections/most-recent-reviews.php');?>
    <!-- most-recent reviews end -->

</div>