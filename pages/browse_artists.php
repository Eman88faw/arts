<?php
include 'includes/header.php';

function artists($conn)
{
    $sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC';

    $output = "";

    $sql = "SELECT * FROM artists ORDER BY FirstName $sort_order";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);


    foreach ($result as $row){

        $ima = "./assets/images/artists/medium/" . $row['ArtistID'] . ".jpg";
        $img = Check($ima);
        $output .= '<div class="col-md-3">
                        <div class="card mb-3 text-center">
                            <img class="d-block w-100" src="' . $img . '" alt="' . $row['LastName'] . '" style="height: 250px;width:auto" >
                            <div class="card-body">
                                <p class="mt-2 mb-0">' . $row['FirstName'] . ' ' . $row['LastName'] . '</p>
                                <a class="btn mt-2 btn-sm btn-primary" href="/?page=artist&Id=' . $row['ArtistID'] . '" >
                                    Show
                                </a>
</div>
                        </div>
                    </div>';
    }
    return $output;
}
?>

<div class="title bg-dark ">
    <div class="container">
    <div class="row">
        <h1 class="my-4 mt-5 text-white py-2">Browse Artists</h1>
    </div>
    </div>
</div>
<div class="container">
    <div class="row text-end my-3">
        <span>
           <span style="line-height: 40px"> SORT :</span>
            <a href="?page=browse_artists&order=ASC" class="float-end ml-3">
                <img src="<?=ASSETS?>images/sort-a-to-z.svg" width="40" alt="">
            </a>
            <a href="?page=browse_artists&order=DESC" class="float-end me-1">
                 <img src="<?=ASSETS?>images/sort-z-to-a.svg" width="40" alt="">
            </a>
        </span>
    </div>
    <div class="row">
        <?php echo artists($conn);?>
    </div>
</div>

