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

// Check if the 'id' query parameter is set and is a valid number
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $currentUserId = $_GET['id'];
    $user = $userRepo->getUser($currentUserId);
} else {
    header('Location: index.php?page=home ');
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update'])) {
        // Sanitize inputs
        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
        $firstName = filter_input(INPUT_POST, "firstName", FILTER_SANITIZE_STRING);
        $lastName = filter_input(INPUT_POST, "lastName", FILTER_SANITIZE_STRING);
        $address = filter_input(INPUT_POST, "address", FILTER_SANITIZE_STRING);
        $city = filter_input(INPUT_POST, "city", FILTER_SANITIZE_STRING);
        $region = filter_input(INPUT_POST, "region", FILTER_SANITIZE_STRING);
        $country = filter_input(INPUT_POST, "country", FILTER_SANITIZE_STRING);
        $postal = filter_input(INPUT_POST, "postal", FILTER_VALIDATE_INT);
        $phone = filter_input(INPUT_POST, "phone", FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);

            $isAdmin = isset($_POST["isAdmin"]) ? 666 : 1;

            $user = new User($currentUserId, $username, $firstName, $lastName, $address, $city, $region, $country, $postal, $phone, $email, $isAdmin);
            $result = $userRepo->updateUserByAdmin($user);
    
            if ($result) {
                header("Location: index.php?page=manage_users");
            } else {
                $errorMessage = 'Something went wrong.';
            }
        } elseif (isset($_POST['delete'])) {
    
            $result = $userRepo->deleteUser($currentUserId);
    
            if ($result) {
                header("Location: index.php?page=manage_users");
            } else {
                $errorMessage = 'Something went wrong.';
            }
        }
    
    }
?>

<div class="container py-5">
    <div>
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
            <?php if (isset($_SESSION['state']) && $_SESSION['state'] == 666 && $_SESSION['user_id'] != $currentUserId): ?>
                <div class="from-group">
                    <label for="isAdmin">Is admin</label>
                    <input type="checkbox" name="isAdmin" <?php if ($user->isAdmin())
                        echo "checked"; ?>>
                </div>
            <?php endif; ?>
            <input type="submit" name="update" value="Update">
        </form>
        <?php if (isset($_SESSION['state']) && $_SESSION['state'] == 666 && $_SESSION['user_id'] != $currentUserId): ?>
            <form action="" method="post">
                <input type="submit" name="delete" value="Delete">
            </form>
        <?php endif; ?>
    </div>
</div>