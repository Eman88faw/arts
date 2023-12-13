<?php
include_once "config.php";
include 'includes/header.php';

function artists($conn)
{
    $sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC';

    $output = "";
    $last = "";

    $sql = "SELECT * FROM artworks order by YearOfWork $sort_order";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row){
        $ima = "./assets/images/works/medium/0" . $row['ImageFileName'] . ".jpg";
        if(!file_exists($ima)){
            $ima = "./assets/images/works/medium/" . $row['ImageFileName'] . ".jpg";
        }
//        var_dump($ima);
//        $img = Check($ima);
        $output .= '<div class="col-md-3">
                        <div class="item mb-3">
                            <a href="/?page=work&Id=' . $row['ArtWorkID'] . '">
                                <img class="d-block w-100" src="' . $ima . '" alt="' . $row['Title'] . '"style="width:1000;height:360px">
                            </a>
                        </div>
                    </div>';
    }
    return $output;
}
?>

<div class="title bg-dark ">
    <div class="container">
        <div class="row">
            <h1 class="my-4 mt-5 text-white py-2">Browse Artworks</h1>
        </div>
    </div>
</div>
<div class="container">
    <div class="row text-end my-3">
        <span>
           <span style="line-height: 40px"> SORT :</span>
            <a href="?page=browse_artworks&order=ASC" class="float-end ml-3">
                <img src="<?=ASSETS?>images/sort-a-to-z.svg" width="40" alt="">
            </a>
            <a href="?page=browse_artworks&order=DESC" class="float-end me-1">
                 <img src="<?=ASSETS?>images/sort-z-to-a.svg" width="40" alt="">
            </a>
        </span>
    </div>
    <div class="row">
        <?php echo artists($conn);?>
    </div>
</div>