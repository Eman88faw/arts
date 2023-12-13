<?php
include('./includes/header.php');
$info = $_GET['Id'];
$anfrage = "select * from Genres where GenreID='$info'";
$c = $conn->prepare($anfrage);
$c->execute();
$anfrage = "select a.ArtWorkID,a.title,a.ImageFileName from Artworks a,ArtworkGenres ag where a.ArtworkId=ag.ArtworkId and ag.GenreID='$info'";
$co = $conn->prepare($anfrage);
$co->execute();
?>

<div class="title bg-dark ">
    <div class="container">
        <h1 class="my-5  text-white py-2">Genre Works </h1>
    </div>
</div>
<div class="container mb-5">
       <div class="row">


           <?php while ($a = $c->fetch()) { ?>
               <div class="col-md-4">
                   <?php
                   $temp = "./assets/images/genres/square-medium/" . $a['GenreID'] . ".jpg";
                   $r = Check($temp);
                   ?>
                   <img id="myImg" class="card-image" src="<?= $r; ?>" style="">


               </div>
               <div class="col-md-8">
                   <div class="Beschreibung" style="width:100%;">
                       <h3 style="color:black">Name : <?= $a['GenreName'] ?></h3>
                       <h4 style="color:black">Description :</h4>
                       <p style="color:black"><?= $a['Description'] ?></p>

                   </div>
               </div>



           <?php } ?>
       </div>
</div>
<div class="container">
    <div class="row">
        <?php while ($b = $co->fetch()) {
            $img = "./assets/images/works/large/0" . $b['ImageFileName'] . ".jpg";
            $myimg = Check($img);
            ?>

            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <a href="/arts/?page=work&Id=<?= $b['ArtWorkID'] ?>">
                            <img id="myImg" src="<?= $myimg; ?>" alt="<? $b['title'] ?>" style="height: 250px; width: auto;max-width:100%; margin: 0 auto;display: block">
                        </a>
                    </div>
                </div>
            </div>
            <!-- The Modal -->


        <?php } ?>
    </div>
</div>

