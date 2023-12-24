<?php
include $_SERVER['DOCUMENT_ROOT'] . '/arts/repo/UserRepository.php';
include $_SERVER['DOCUMENT_ROOT'] . '/arts/entities/User.php';
include $_SERVER['DOCUMENT_ROOT'] . '/arts/db/pdoDb.php';

$successMessage = "";
$errorMessage = "";

// Create a database connection
$db = new pdoDb();

// Initialize UserRepository with the database connection
$userRepo = new UserRepository($db);

// Define default sort column and order
$sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'first_name';
$sortOrder = isset($_GET['order']) ? $_GET['order'] : 'asc';

// Define available sort options
$sortOptions = [
    'first_name' => UserSortFields::FIRST_NAME,
    'last_name' => UserSortFields::LAST_NAME,
    'username' => UserSortFields::USERNAME,
    'address' => UserSortFields::ADDRESS,
    'city' => UserSortFields::CITY,
    'region' => UserSortFields::REGION,
    'country' => UserSortFields::COUNTRY,
    'postal' => UserSortFields::POSTAL,
    'phone' => UserSortFields::PHONE,
    'email' => UserSortFields::EMAIL,
    'state' => UserSortFields::STATE
];

$sortOptionsNames = [
    'first_name' => 'First Name',
    'last_name' => 'Last Name',
    'username' => 'Username',
    'address' => 'Address',
    'city' => 'City',
    'region' => 'Region',
    'country' => 'Country',
    'postal' => 'Postal',
    'phone' => 'Phone',
    'email' => 'Email',
    'state' => 'State',
];

// Validate and set the sort order
if (!array_key_exists($sortColumn, $sortOptions)) {
    $sortColumn = UserSortFields::FIRST_NAME; // Default to sort by title
}
if ($sortOrder !== 'asc' && $sortOrder !== 'desc') {
    $sortOrder = SortOrder::ASCENDING; // Default to ascending
}

// Determine the actual SQL column to sort by
$orderBy = $sortOptions[$sortColumn];
$sortOrder = $sortOrder === 'asc' ? SortOrder::ASCENDING : SortOrder::DESCENDING;

$users = $userRepo->getUsersSorted($orderBy, $sortOrder);

?>
<div class="container pt-5">
    <h1 class="my-4">Manage Users</h1>

    <!-- Sort order selection -->
    <div class="mb-3">
        <strong>Sort by:</strong>
        <?php foreach ($sortOptions as $column => $label): ?>
            <a href="?page=manage_users&sort=<?php echo $column; ?>&order=<?php echo ($sortColumn === $column && $sortOrder === SortOrder::ASCENDING) ? 'desc' : 'asc'; ?>"
                class="btn btn-link btn-sm">
                <?php echo $sortOptionsNames[$column]; ?>
            </a>
            <?php if ($sortColumn === $column): ?>
                <?php if ($sortOrder === SortOrder::ASCENDING): ?>
                    <img src="<?= ASSETS ?>images/sort-a-to-z.svg" width="40" alt="Ascending">
                <?php else: ?>
                    <img src="<?= ASSETS ?>images/sort-z-to-a.svg" width="40" alt="Descending">
                <?php endif; ?>
            <?php endif; ?>

        <?php endforeach; ?>
    </div>
    <!-- Users Table -->
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Actions</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>Region</th>
                    <th>Country</th>
                    <th>Postal</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>State</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <?php echo $user->getId(); ?>
                        </td>
                        <td><a href="index.php?page=edit_user&id=<?php echo $user->getId(); ?>">Edit</a></td>
                        <td>
                            <?php echo $user->getFirstName() ?>
                        </td>
                        <td>
                            <?php echo $user->getLastName(); ?>
                        </td>
                        <td>
                            <?php echo $user->getUserName(); ?>
                        </td>
                        <td>
                            <?php echo $user->getAddress(); ?>
                        </td>
                        <td>
                            <?php echo $user->getCity(); ?>
                        </td>
                        <td>
                            <?php echo $user->getRegion(); ?>
                        </td>
                        <td>
                            <?php echo $user->getCountry(); ?>
                        </td>
                        <td>
                            <?php echo $user->getPostal(); ?>
                        </td>
                        <td>
                            <?php echo $user->getPhone(); ?>
                        </td>
                        <td>
                            <?php echo $user->getEmail(); ?>
                        </td>
                        <td>
                            <?php echo $user->getState(); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>