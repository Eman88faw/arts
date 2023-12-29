<?php
include_once "config.php";
include 'includes/header.php';
function sortText(){
    $sortColumn = isset($_GET['column']) ? $_GET['column'] : 'Title';
    $sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC';
    $sortText = "Artists Sorted By <b>$sortColumn</b> Ordered By <b>$sort_order</b>";
    return $sortText;
}
function artists($conn)
{
    $sortColumn = isset($_GET['column']) ? $_GET['column'] : 'Title';
    $sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC';

    $output = "";
    $last = "";

    $sql = "SELECT * FROM artworks order by $sortColumn $sort_order";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row){
        if(strlen($row['ImageFileName']) < 6){
            $ima = "./assets/images/works/medium/0" . $row['ImageFileName'] . ".jpg";
        }
        else{
            $ima = "./assets/images/works/medium/" . $row['ImageFileName'] . ".jpg";
        }
//        var_dump($ima);
//        $img = Check($ima);
        $output .= '<div class="col-md-3 mb-3">
                        <div class="item mb-3">
                            <a href="?page=work&Id=' . $row['ArtWorkID'] . '">
                                <img class="d-block w-100" src="' . $ima . '" alt="' . $row['Title'] . '"style="height:250px">
                            </a>
                            <h6 class="text-center bg-dark p-2 text-white">'.$row['Title'].'</h6>
                        </div>
                    </div>';
    }
    return $output;
}
?>

<div class=" bg-dark  mb-3">
    <div class="container title">
        <div class="row">
            <h1 class="text-white">Browse Artworks</h1>
        </div>
    </div>
</div>
<div class="container">

    <div class="row text-end my-3 bg-light align-items-center border-bottom">
        <div class="col-9 text-start">
            <p class="mb-0 text-success"><?= sortText();?></p>
        </div>
        <div class="col-3 text-end">
            <span style="line-height: 40px"> SORT :</span>
            <?php if(isset($_GET['order']) && strtolower($_GET['order']) == 'asc' || !isset($_GET['order'])):?>
                <a href="?page=browse_artworks&column=Title&order=DESC" class="float-end ml-3">
                    Title
                    <img src="<?= ASSETS ?>images/sort-a-to-z.svg" width="40" alt="">
                </a>
                <a href="?page=browse_artworks&column=ArtistId&order=DESC" class="float-end me-1">
                    Artist
                    <img src="<?= ASSETS ?>images/sort-a-to-z.svg" width="40" alt="">
                </a>
                <a href="?page=browse_artworks&column=YearOfWork&order=DESC" class="float-end me-1">
                    Year
                    <img src="<?= ASSETS ?>images/sort-a-to-z.svg" width="40" alt="">
                </a>
            <?php else:;?>
                <a href="?page=browse_artworks&column=Title&order=ASC" class="float-end ml-3">
                    Title
                    <img src="<?= ASSETS ?>images/sort-z-to-a.svg" width="40" alt="">
                </a>
                <a href="?page=browse_artworks&column=ArtistId&order=ASC" class="float-end me-1">
                    Artist
                    <img src="<?= ASSETS ?>images/sort-z-to-a.svg" width="40" alt="">
                </a>
                <a href="?page=browse_artworks&column=YearOfWork&order=ASC" class="float-end me-1">
                    Year
                    <img src="<?= ASSETS ?>images/sort-z-to-a.svg" width="40" alt="">
                </a>
            <?php endif;?>
        </div>

    </div>
    <div class="row">
        <?php echo artists($conn);?>
    </div>
</div>