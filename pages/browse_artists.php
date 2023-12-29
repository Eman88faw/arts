<?php
include 'includes/header.php';

function sortText(){
    $sortColumn = isset($_GET['column']) && $_GET['column'] == 'FirstName' ? 'FirstName' : 'LastName';
    $sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC';
    $sortText = "Artists Sorted By <b>$sortColumn</b> Ordered By <b>$sort_order</b>";
    return $sortText;
}
function artists($conn)
{

    $sortColumn = isset($_GET['column']) && $_GET['column'] == 'FirstName' ? 'FirstName' : 'LastName';
    $sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC';

    $output = "";

    $sql = "SELECT * FROM artists ORDER BY `$sortColumn` $sort_order";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);



    foreach ($result as $row) {
        if($sortColumn == "FirstName"){
            $artistName = '<p class="mt-2 mb-0">' . $row['FirstName'] . ' ' . $row['LastName'] . '</p>';

        }
        else{
            $artistName = '<p class="mt-2 mb-0">' . $row['LastName'] . ' ' . $row['FirstName'] . '</p>';
        }
        $ima = "./assets/images/artists/medium/" . $row['ArtistID'] . ".jpg";
        $img = Check($ima);
        $output .= '<div class="col-md-3">
                        <div class="card mb-3 text-center">
                            <img class="d-block w-100" src="' . $img . '" alt="' . $row['LastName'] . '" style="height: 250px;width:auto" >
                            <div class="card-body">
                                '.$artistName.'
                                <a class="btn mt-2 btn-sm btn-primary" href="?page=artist&Id=' . $row['ArtistID'] . '" >
                                    Show
                                </a>
                            </div>
                        </div>
                    </div>';
    }
    return $output;
}

?>

<div class=" bg-dark  mb-3">
    <div class="container title">
        <div class="row">
            <h1 class="text-white">Browse Artists</h1>
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
                <a href="?page=browse_artists&column=FirstName&order=DESC" class="float-end ml-3">
                    First Name
                    <img src="<?= ASSETS ?>images/sort-a-to-z.svg" width="40" alt="">
                </a>
                <a href="?page=browse_artists&column=LastName&order=DESC" class="float-end me-1">
                    Last Name
                     <img src="<?= ASSETS ?>images/sort-a-to-z.svg" width="40" alt="">
                </a>
            <?php else:;?>
                <a href="?page=browse_artists&column=FirstName&order=ASC" class="float-end ml-3">
                    First Name
                    <img src="<?= ASSETS ?>images/sort-z-to-a.svg" width="40" alt="">
                </a>
                <a href="?page=browse_artists&column=LastName&order=ASC" class="float-end me-1">
                    Last Name
                     <img src="<?= ASSETS ?>images/sort-z-to-a.svg" width="40" alt="">
                </a>
            <?php endif;?>
        </div>
    </div>
    <div class="row">
        <?php echo artists($conn); ?>
    </div>
</div>

