<?php
$info=$_GET['Id'];
$anfrage="select * from Subjects where SubjectID='$info'";
$c=$conn->prepare($anfrage);
$c->execute();
$anfrage="select a.ImageFileName,a.ArtWorkID, a.Title from Artworks a,Artworksubjects aw where a.ArtworkId=aw.ArtworkId and aw.SubjectID='$info'";
$co=$conn->prepare($anfrage);
$co->execute();
?>
<div class="title bg-dark ">
    <div class="container">
        <h1 class="my-5  text-white py-2">Subject Works </h1>
    </div>
</div>

<div class="container mb-5">
    <div class="row">

        <?php while($a=$c->fetch()){?>
            <div class="col-md-4">
                <?php
                $temp="./assets/images/subjects/square-medium/".$a['SubjectId'].".jpg";
                $r=Check($temp);
                ?>
                <img id="myImg" src="<?=$r;?>" class="card-img-top">

            </div>
            <div class="col-md-8">
                <h3 style="color:black"><?=$a['SubjectName']?></h3>
            </div>


        <?php }?>
    </div>

</div>
<div class="container">
    <div class="row">

                <?php while($b=$co->fetch()){

                $img="./assets/images/works/large/".$b['ImageFileName'].".jpg";
                $myimg=Check($img);
                ?>
            <div class="col-md-3">
                <div class="card text-center mb-3">
                    <img id="myImg" src="<?=$myimg;?>" class="card-img-top" style="height: 250px; width: auto; max-width: 100%; margin: 0 auto">
                    <div class="card-body">
                        <a href="/?page=work&Id=<?=$b['ArtWorkID']?>">
                            <?=$b["Title"]?>
                        </a>
                    </div>
                </div>
            </div>




        <?php }?>
    </div>
</div>
