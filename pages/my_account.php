<?php

include $_SERVER['DOCUMENT_ROOT'] . '/arts/repo/UserRepository.php';
include $_SERVER['DOCUMENT_ROOT'] . '/arts/entities/User.php';
include $_SERVER['DOCUMENT_ROOT'] . '/arts/db/pdoDb.php';

$successMessage = "";
$errorMessage = "";

// Create a database connection (assuming you have a Database class)
$db = new pdoDb();

// Initialize UserRepository with the database connection
$userRepo = new UserRepository($db);

// Retrieving a user by ID
$currentUserId = $_SESSION['user_id'];
$user = $userRepo->getUser($currentUserId);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST["username"];
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $address = $_POST["address"];
    $city = $_POST["city"];
    $region = $_POST["region"];
    $country = $_POST["country"];
    $postal = $_POST["postal"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Updating a user
    $user = new User($currentUserId, $username, $firstName, $lastName, $address, $city, $region, $country, $postal, $phone, $email, $user->getState());
    $result = $userRepo->updateUser($user, $password);

    echo $result;

    if ($result) {
        $successMessage = 'Your data has been changed.';
    } else {
        $errorMessage = 'Something went wrong.';
    }
}
?>

<div class="container py-5">
    <div class="register">
        <h1>Change personal data</h1>

        <?php if (isset($errorMessage)): ?>
            <p style="color: red;">
                <?php echo $errorMessage; ?>
            </p>
        <?php endif; ?>

        <?php if (isset($successMessage)): ?>
            <p style="color: green;">
                <?php echo $successMessage; ?>
            </p>
        <?php endif; ?>

        <form method="post" action="?page=my_account">
            <form action="" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input value="<?php echo htmlspecialchars($user->getUsername()); ?>" type="text" name="username"
                        class="form-control" placeholder="Enter your first name" required>
                </div>
                <div class="form-group">
                    <label for="firstName">First Name</label>
                    <input value="<?php echo htmlspecialchars($user->getFirstName()); ?>" type="text" name="firstName"
                        class="form-control" placeholder="Enter your first name" required>
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name</label>
                    <input value="<?php echo htmlspecialchars($user->getLastName()); ?>" type="text" name="lastName"
                        class="form-control" placeholder="Enter your last name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input value="<?php echo htmlspecialchars($user->getEmail()); ?>" type="email" name="email"
                        class="form-control" placeholder="Enter your email address"
                        pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" required>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input value="<?php echo htmlspecialchars($user->getAddress()); ?>" type="text" name="address"
                        class="form-control" placeholder="Enter your last name" required>
                </div>
                <div class="form-group">
                    <label for="city">City</label>
                    <input value="<?php echo htmlspecialchars($user->getCity()); ?>" type="text" name="city"
                        class="form-control" placeholder="Enter your city" required>
                </div>
                <div class="form-group">
                    <label for="region">Region</label>
                    <input value="<?php echo htmlspecialchars($user->getRegion()); ?>" type="text" name="region"
                        class="form-control" placeholder="Enter your region" required>
                </div>
                <div class="form-group">
                    <label for="country">Country</label>
                    <input value="<?php echo htmlspecialchars($user->getCountry()); ?>" type="text" name="country"
                        class="form-control" placeholder="Enter your country" required>
                </div>
                <div class="form-group">
                    <label for="postal">Postal Code</label>
                    <input value="<?php echo htmlspecialchars($user->getPostal()); ?>" type="number" name="postal"
                        pattern="[0-9]{5}" class="form-control" placeholder="Enter your postal code" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input value="<?php echo htmlspecialchars($user->getPhone()); ?>" type="text" name="phone"
                        class="form-control" placeholder="Enter your phone number" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter your password"
                        required>
                </div>
                <button type="submit" class="btn btn-primary">Change</button>
            </form>
    </div>
</div>