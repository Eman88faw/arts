<?php
include $_SERVER['DOCUMENT_ROOT'].'/arts/repo/UserRepository.php';
include $_SERVER['DOCUMENT_ROOT'].'/arts/entities/User.php';
include $_SERVER['DOCUMENT_ROOT'].'/arts/db/pdoDb.php';

$successMessage = "";
$errorMessage = "";

// Create a database connection (assuming you have a Database class)
$db = new pdoDb();

// Initialize UserRepository with the database connection
$userRepo = new UserRepository($db);

$users = $userRepo->getUsers();

// Define default sort column and order
$sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'Title';
$sortOrder = isset($_GET['order']) ? $_GET['order'] : 'asc';

// Define available sort options
$sortOptions = [
    'Title' => 'Title',
    'Artist' => 'artists.LastName',
    'YearOfWork' => 'YearOfWork',
];

// Validate and set the sort order
if (!array_key_exists($sortColumn, $sortOptions)) {
    $sortColumn = 'Title'; // Default to sorting by title
}
if ($sortOrder !== 'asc' && $sortOrder !== 'desc') {
    $sortOrder = 'asc'; // Default to ascending order
}

// Determine the actual SQL column to sort by
$orderBy = $sortOptions[$sortColumn];

// Query to retrieve artworks with specified sort order
// $query = "SELECT artworks.*, artists.FirstName, artists.LastName FROM artworks
//           LEFT JOIN artists ON artworks.ArtistID = artists.ArtistID
//           ORDER BY $orderBy $sortOrder";
// $statement = $pdo->prepare($query);
// $statement->execute();
// $artworks = $statement->fetchAll(PDO::FETCH_ASSOC);

// Query to retrieve users with specified sort order
// $query = "SELECT * FROM customers ORDER BY CustomerID ASC";
// $statement = $pdo->prepare($query);
// $statement->execute();
// $users = $statement->fetchAll(PDO::FETCH_ASSOC);

?>
<div class="container pt-5">
        <h1 class="my-4">Manage Users</h1>

        <!-- Sort order selection -->
        <div class="mb-3">
            <strong>Sort by:</strong>
            <?php foreach ($sortOptions as $column => $label) : ?>
                <a href="?sort=<?php echo $column; ?>&order=<?php echo ($sortColumn === $column && $sortOrder === 'asc') ? 'desc' : 'asc'; ?>" class="btn btn-link btn-sm">
                    <?php echo $label; ?>
                </a>
                <?php if ($sortColumn === $column) : ?>
                    <span class="badge badge-secondary"><?php echo strtoupper($sortOrder); ?></span>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <!-- Users Table -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>User</th>
                    <th>Username</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><?php echo $user->getId(); ?></td>
                        <td><?php echo $user->getFirstName() . ' ' . $user->getLastName(); ?></td>
                        <td><?php echo $user->getUserName(); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
</div>
