<?php

$artworkToGenres = [];
$genresToArtworks = [];

try {
    global $customTable;
    // Prepare the SQL query
    $sql = "SELECT a.ArtworkID, g.genreName 
            FROM artworks a
            JOIN artworkgenres ag ON a.ArtworkID = ag.ArtworkID
            JOIN genres g ON ag.GenreID = g.GenreID";

    // Execute the query
    $stmt = $conn->query($sql);

    // Initialize an empty array for the custom table
    $customTable = [];

    // Fetch the results and populate the custom table
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $artworkId = $row['ArtworkID'];
        $genreName = $row['genreName'];

        if (!isset($artworkToGenres[$artworkId])) {
            $artworkToGenres[$artworkId] = $genreName;
        } else {
            $artworkToGenres[$artworkId] .= ', ' . $genreName;
        }

        if (!isset($genresToArtworks[$genreName])) {
            $genresToArtworks[$genreName] = $artworkId;
        } else {
            $genresToArtworks[$genreName] .= ', ' . $artworkId;
        }

        
        
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Capture the search type (artist or artwork) from user input
$searchType = $_GET['searchType'] ?? 'artist';
$searchQuery = '';

// Define valid columns for each search type
$validArtistColumns = ['FirstName', 'LastName', 'YearOfBirth', 'YearOfDeath', "Nationality"];
$validArtworkColumns = ['Title', 'YearOfWork', 'Genre'];

$artistColumns = [
    'FirstName' => 'First Name',
    'LastName' => 'Last Name',
    "LivedBetween" => "Lived Between",
    "Nationality" => "Nationality"
];

$artworkColumns = [
    'Title' => 'Title',
    'YearOfWork' => 'Year Of Work',
    'Genre' => 'Genre'
];

$validColumns = ($searchType === 'artist') ? $validArtistColumns : $validArtworkColumns;
$columnsHeader = ($searchType === 'artist') ? $artistColumns : $artworkColumns;

// Get the requested column if it's valid, otherwise default to the first column.
$column = in_array($_GET['column'] ?? '', $validColumns) ? $_GET['column'] : $validColumns[0];

// Determine the sort order.
$sort_order = strtolower($_GET['order'] ?? '') === 'desc' ? 'DESC' : 'ASC';

// Helper variables for UI elements.
$up_or_down = $sort_order === 'ASC' ? 'up' : 'down';
$asc_or_desc = $sort_order === 'ASC' ? 'desc' : 'asc';

// Adjust base query and search query based on the search type
if ($searchType === 'artist') {
    // Base query for artists
    $baseQuery = "SELECT * FROM artists";

    // Initialize variables for search criteria
    $name = $_GET['search'] ?? null;
    $nationality = $_GET['nationality'] ?? null;
    $date = $_GET['date'] ?? null;

    // Building the search query
    $searchConditions = [];
    if (!empty($name)) {
        $searchConditions[] = "(FirstName LIKE '%{$name}%' OR LastName LIKE '%{$name}%')";
    }
    if (!empty($date)) {
        $searchConditions[] = "(YearOfBirth <= '{$date}' AND YearOfDeath >= '{$date}')";
    }
    if (!empty($nationality)) {
        $searchConditions[] = "Nationality LIKE '%{$nationality}%'";
    }

    // Combine conditions with AND
    if (!empty($searchConditions)) {
        $searchQuery = " WHERE " . implode(' AND ', $searchConditions);
    } else {
        $searchQuery = '';
    }

} else {
        // Base query for artists
        $baseQuery = "SELECT * FROM artworks";

        // Initialize variables for search criteria
        $name = $_GET['search'] ?? null;
        $genre = $_GET['genre'] ?? null;
        $date = $_GET['date'] ?? null;
    
        // Building the search query
        $searchConditions = [];
        if (!empty($name)) {
            $searchConditions[] = "Title LIKE '%{$name}%'";
        }
        if (!empty($date)) {
            $searchConditions[] = "YearOfWork = '{$date}'";
        }
        if (!empty($genre)) {
            $artworkIds = explode(',', $genresToArtworks[$genre]);
            $artworkIds = implode(',', $artworkIds);
            
            $searchConditions[] = "ArtworkID IN ({$artworkIds})";
        }
    
        // Combine conditions with AND
        if (!empty($searchConditions)) {
            $searchQuery = " WHERE " . implode(' AND ', $searchConditions);
        } else {
            $searchQuery = '';
        }
}
// Final SQL query.
$sql = "{$baseQuery}{$searchQuery} ORDER BY {$column} {$sort_order}";
echo $sql;
$articles = $conn->query($sql);

?>

<div class="title bg-dark ">
    <div class="container">
        <h1 class="my-4 mt-5  text-white py-2">Search For </h1>
    </div>
</div>
<div class="container">
    <div class="row" style="background: #FBFCFC ;grid-column:2/5; color: black;">
        <form action="" method="get">
            <div class="row mb-3">
                <div class="col">
                    <select name="searchType" class="form-control" id="searchType">
                        <option value="artist">Artist</option>
                        <option value="artwork">Artwork</option>
                    </select>
                </div>
                <div class="col">
                    <input type="text" name="search" class="form-control" placeholder="Search...">
                </div>
                <div class="col">
                    <input type="number" name="date" class="form-control" placeholder="Select Date">
                </div>
                <div class="col" id="nationalityField">
                    <input type="text" name="nationality" class="form-control" placeholder="Nationality">
                </div>
                <div class="col" id="genreField">
                    <input type="text" name="genre" class="form-control" placeholder="Genre">
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
        </form>
        <?php if ($articles->rowCount() > 0) { ?>
            <div class="table-responsive mb-5" style="max-width: 100%;max-height: 400px;overflow: auto">
                <table class="table table-bordered fixTableHead table-responsive table-striped">
                    <thead class="bg-primary striped-top">
                        <tr>
                            <?php foreach ($columnsHeader as $columnKey => $columnDisplay): ?>
                                <th class="bg-primary">
                                    <a class="text-white"
                                        href="?search=<?= htmlspecialchars($_GET['search']); ?>&searchType=<?= $searchType; ?>&column=<?= $columnKey; ?>&order=<?= $asc_or_desc; ?>">
                                        <?= htmlspecialchars($columnDisplay); ?>
                                        <i class="fas fa-sort<?= $column == $columnKey ? '-' . $up_or_down : ''; ?>"></i>
                                    </a>
                                </th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        while ($row = $articles->fetch()) { ?>
                            <?php if ($searchType === "artist") { ?>
                                <tr>
                                    <td>
                                        <a href="?page=artist&Id=<?= $row['ArtistID'] ?>">
                                            <?= $row['FirstName'] ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="?page=artist&Id=<?= $row['ArtistID'] ?>">

                                            <?= $row['LastName'] ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="?page=artist&Id=<?= $row['ArtistID'] ?>">
                                            Lived between:
                                            <?= $row['YearOfBirth'] . '-' . $row['YearOfDeath'] ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="?page=artist&Id=<?= $row['ArtistID'] ?>">

                                            <?= $row['Nationality'] ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php } else { ?>
                                <tr>
                                    <td><a href="?page=work&Id=<?= $row['ArtWorkID'] ?>">
                                            <?= $row['Title'] ?>
                                        </a></td>
                                    <td>
                                        <?php echo $row['YearOfWork']; ?>
                                    </td>
                                    <td>
                                        <?php echo $artworkToGenres[$row['ArtWorkID']] ; ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php }

        } else { ?>
                        <div style="padding: 100px;">
                            <h2> No Results For <a style="color:#3366ff;">
                                    <?= $sql ?>
                                </a> Please try again</h2>
                        </div>
                    <?php } ?>
                </tbody>


            </table>
        </div>

    </div>
</div>

<script>
    document.getElementById('searchType').addEventListener('change', function () {
        var searchType = this.value;
        var nationalityField = document.getElementById('nationalityField');
        var genreField = document.getElementById('genreField');

        if (searchType === 'artist') {
            nationalityField.style.display = '';
            genreField.style.display = 'none';
        } else if (searchType === 'artwork') {
            nationalityField.style.display = 'none';
            genreField.style.display = '';
        }
    });

    // Trigger the event listener to set the initial state
    document.getElementById('searchType').dispatchEvent(new Event('change'));
</script>