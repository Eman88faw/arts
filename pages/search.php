<?php

$columns = array('FirstName','LastName','Title','YearOfWork');

// Only get the column if it exists in the above columns array, if it doesn't exist the database table will be sorted by the first item in the columns array.
$column = isset($_GET['column']) && in_array($_GET['column'], $columns) ? $_GET['column'] : $columns[0];

// Get the sort order for the column, ascending or descending, default is ascending.
$sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC';

// Get the result...
if ($articles = $conn->query('SELECT FirstName,LastName, Title,YearOfWork,artists.ArtistID,ArtWorkID
                                FROM `artists`,`artworks`
                                WHERE artists.ArtistID=artworks.ArtistID ORDER BY ' .  $column . ' ' . $sort_order)) {
    // Some variables we need for the table.
    $up_or_down = str_replace(array('ASC','DESC'), array('up','down'), $sort_order);
    $asc_or_desc = $sort_order == 'ASC' ? 'desc' : 'asc';
    $add_class = ' class="highlight"';
}

$articles = $conn->query('SELECT FirstName,LastName, Title,YearOfWork,artists.ArtistID,ArtWorkID
                                FROM `artists`,`artworks`
                                WHERE artists.ArtistID=artworks.ArtistID ORDER BY ' .  $column . ' ' . $sort_order);

if(isset($_GET['search']) AND !empty($_GET['search']) ) {

    $q = $_GET['search'];
    $articles = $conn->query('SELECT FirstName,LastName,Title,YearOfWork,artists.ArtistID,ArtWorkID FROM artists,artworks WHERE artists.ArtistID=artworks.ArtistID AND ( artists.FirstName LIKE "%'.$q.'%" OR artists.LastName LIKE "%'.$q.'%" OR artworks.Title LIKE "%'.$q.'%")ORDER BY ' .  $column . ' ' . $sort_order);
    if($articles->rowCount() == 0) {
        $articles = $conn->query('SELECT FirstName,LastName,Title,YearOfWork,artists.ArtistID,ArtWorkID FROM artists,artworks WHERE (CONCAT(artists.FirstName," ",artists.LastName)LIKE "%'.$q.'%" )AND artists.ArtistID=artworks.ArtistID ORDER BY ' .  $column . ' ' . $sort_order);
    }

}

?>

<div class="title bg-dark ">
    <div class="container">
        <h1 class="my-4 mt-5  text-white py-2">Search For  </h1>
    </div>
</div>
<div class="container">
    <div class="row" style="background: #FBFCFC ;grid-column:2/5; color: black;">

        <?php if($articles->rowCount() > 0) {?>
        <div class="table-responsive mb-5" style="max-width: 100%;max-height: 400px;overflow: auto">
            <table class="table table-bordered fixTableHead table-responsive table-striped" >
                <thead class="bg-primary striped-top">
                    <tr>
                        <th class="bg-primary"><a class="text-white" href="/?search=<?=$_GET['search'];?>&column=FirstName&order=<?php echo $asc_or_desc; ?>">  FirstName<i class="fas fa-sort<?php echo $column == 'FirstName' ? '-' . $up_or_down : ''; ?>"></i></a></th>
                        <th  class="bg-primary"><a class="text-white" href="/?search=<?=$_GET['search'];?>&column=LastName&order=<?php echo $asc_or_desc; ?>">  LastName<i class="fas fa-sort<?php echo $column == 'LastName' ? '-' . $up_or_down : ''; ?>"></i></a></th>
                        <th  class="bg-primary"><a class="text-white" href="/?search=<?=$_GET['search'];?>&column=Title&order=<?php echo $asc_or_desc; ?>">     Titel<i class="fas fa-sort<?php echo $column == 'Title' ? '-' . $up_or_down : ''; ?>"></i></a></th>
                        <th  class="bg-primary"><a class="text-white" href="/?search=<?=$_GET['search'];?>&column=YearOfWork&order=<?php echo $asc_or_desc; ?>">YearOfWork<i class="fas fa-sort<?php echo $column == 'MSRP' ? '-' . $up_or_down : ''; ?>"></i></a></th>
                    </tr>
                </thead>

                <tbody>
                <?php
                while($row = $articles->fetch())
                {
                    ?>
                    <tr>
                        <td><a href="?page=artist&Id=<?=$row['ArtistID']?>"><?=$row['FirstName']?></a></td>
                        <td><a href="?page=artist&Id=<?=$row['ArtistID']?>"><?=$row['LastName']?></a></td>
                        <td><a href="?page=work&Id=<?=$row['ArtWorkID']?>"><?=$row['Title']?></a></td>
                        <td><?php  echo $row['YearOfWork']; ?></td>
                    </tr>
                <?php }

                } else {?>
                    <div style="padding: 100px;">
                        <h2> No Results For <a style="color:#3366ff;"><?= $q?></a>  Please try again</h2>
                    </div>
                <?php }?>
                </tbody>


            </table>
        </div>

    </div>
</div>
