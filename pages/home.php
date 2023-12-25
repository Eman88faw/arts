<?php
include "connDB.php";
include 'includes/header.php';
$ar = "select * from artists order by FirstName ASC";
$c = $conn->query($ar);

function slides($conn)
{
        $items = "SELECT * FROM ArtWorks ORDER BY rand() LIMIT 5";
        $SlideItems = $conn->query($items);


    $output = '';
    $slider_indicators ='';

    $count = 0;
    while ($row = $SlideItems->fetch()) {
        if ($count == 0) {
            if(strlen($row['ImageFileName']) == 5){
                $ima = "./assets/images/works/large/0" . $row['ImageFileName'] . ".jpg";
            }
            else{
                $ima = "./assets/images/works/large/" . $row['ImageFileName'] . ".jpg";
            }

            $img = Check($ima);
            $output .= ' <div class="carousel-item active" >
                            <a href="?page=work&Id=' . $row['ArtWorkID'] . '">
                                <img class="d-block mx-auto" src="' . $img . '" style="width:auto;height:100%">
                            </a>
                        </div>';
            $slider_indicators .= '<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="'.$count.'" class="active" aria-current="true" aria-label="Slide '.($count+1).'"></button>';
        } else {
            if(strlen($row['ImageFileName']) == 5){
                $ima = "./assets/images/works/large/0" . $row['ImageFileName'] . ".jpg";
            }
            else{
                $ima = "./assets/images/works/large/" . $row['ImageFileName'] . ".jpg";
            }
            $img = Check($ima);
            $output .= ' <div class="carousel-item">
                            <a href="?page=work&Id=' . $row['ArtWorkID'] . '">
                                <img class="d-block mx-auto" src="' . $img . '" style="width:auto;height:100%">
                            </a>
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