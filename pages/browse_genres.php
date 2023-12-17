<?php
function genersData($conn){
    $sql = "SELECT * FROM `genres` ORDER BY GenreName DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $output= "";
    foreach ($result as $row){
        $output .= '<div class="col-md-3">
                        <div class="card mb-3">
                            <img src="./assets/images/genres/square-medium/'.$row["GenreID"].'.jpg" height="250" alt="">
                            <div class="card-body">
                                <p>'.$row["GenreName"].'</p>
                                <p><sma>'.substr($row["Description"], 0, 100).'</sma></p>
                                <a href="?page=genre&Id='.$row["GenreID"].'" class="btn btn-sm btn-primary">Show More</a>
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

    <div class="row">
        <?= genersData($conn)?>
    </div>
</div>