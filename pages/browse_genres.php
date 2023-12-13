<?php
function genersData($conn){
    $sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC';

    $sql = "SELECT * FROM `genres` ORDER BY GenreName $sort_order";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $output= "";
    foreach ($result as $row){
        $output .= '<div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body">
                                <p>'.$row["GenreName"].'</p>
                                <p><sma>'.substr($row["Description"], 0, 100).'</sma></p>
                                <a href="?page=genre&Id='.$row["GenreID"].'">Show More</a>
                            </div>
                        </div>
                    </div>';
    }

    return $output;
}
?>

<div class="title bg-dark ">
    <div class="container">
        <h1 class="my-4 mt-5  text-white py-2">Browse Genres</h1>
    </div>
</div>
<div class="container mb-4">
    <div class="row text-end my-3">
        <span>
           <span style="line-height: 40px"> SORT :</span>
            <a href="?page=browse_genres&order=ASC" class="float-end ml-3">
                <img src="<?=ASSETS?>images/sort-a-to-z.svg" width="40" alt="">
            </a>
            <a href="?page=browse_genres&order=DESC" class="float-end me-1">
                 <img src="<?=ASSETS?>images/sort-z-to-a.svg" width="40" alt="">
            </a>
        </span>
    </div>
    <div class="row">
        <?= genersData($conn)?>
    </div>
</div>