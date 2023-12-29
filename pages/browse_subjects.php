<?php
$anfrage="select * from Subjects ORDER BY SubjectName DESC";
$c=$conn->prepare($anfrage);
$c->execute();

?>


<div class=" bg-dark mb-3">
    <div class="container title">
        <h1 class="text-white ">Browse Subjects </h1>
    </div>
</div>
<div class="container">
    <div class="row">
        <?php while($a=$c->fetch()){?>

            <div class="col-md-3">
                <div class="card text-center mb-3">
                    <?php
                        $img="./assets/images/subjects/square-medium/".$a['SubjectId'].".jpg";
                        $myimg=Check($img);
                    ?>
                    <img src="<?=$myimg;?>">
                    <div class="card-body">
                        <h3><?=$a['SubjectName']?></h3>
                        <a class="btn btn-sm btn-primary" href="?page=subject&Id=<?=$a['SubjectId']?>">
                            Show More
                        </a>
                    </div>
                </div>



            </div>
        <?php }?>
    </div>

</div>