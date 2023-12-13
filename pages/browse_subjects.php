<?php
$sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC';

$anfrage="select * from Subjects ORDER BY SubjectName $sort_order";
$c=$conn->prepare($anfrage);
$c->execute();

?>


<div class="title bg-dark ">
    <div class="container">
        <h1 class="my-5  text-white py-2">Browse Subjects </h1>
    </div>
</div>
<div class="container">
    <div class="row text-end my-3">

        <span>
           <span style="line-height: 40px"> SORT :</span>
            <a href="?page=browse_subjects&order=ASC" class="float-end ml-3">
                <img src="<?=ASSETS?>images/sort-a-to-z.svg" width="40" alt="">
            </a>
            <a href="?page=browse_subjects&order=DESC" class="float-end me-1">
                 <img src="<?=ASSETS?>images/sort-z-to-a.svg" width="40" alt="">
            </a>
        </span>
    </div>
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