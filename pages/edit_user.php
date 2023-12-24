<?php

include $_SERVER['DOCUMENT_ROOT'].'/arts/repo/UserRepository.php';
include $_SERVER['DOCUMENT_ROOT'].'/arts/entities/User.php';
include $_SERVER['DOCUMENT_ROOT'].'/arts/db/pdoDb.php';

// Start the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$successMessage = "";
$errorMessage = "";

// Create a database connection (assuming you have a Database class)
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

$firstName = $user->getFirstName();
$lastName  = $user->getLastName();
$username  = $user->getUsername();
$isAdmin   = $user->isAdmin(); 

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(isset($_POST['update']) && $_POST['update']){
        //Login
        echo "Login";
    }elseif($_POST['delete']){
        echo "Delete";
    }
    // $firstName = $_POST["firstName"];
    // $lastName = $_POST["lastName"];
    // $username = $_POST["username"];
    // $password = "abcd1234";

    // // Updating a user
    // $user = new User($currentUserId, $username, $firstName, $lastName, 1);
    // $result = $userRepo->updateUser($user, $password);

    // echo $result;

    // if ($result) {
    //     $successMessage = 'Your data has been changed.';
    // } else {
    //     $errorMessage = 'Something went wrong.';
    // }
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

        <form method="post" id="form1" action="?page=edit_user&id=<?php echo $currentUserId; ?>">
            <!-- <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" class="form-control"
                    placeholder="Enter email" required>
                <small class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div> -->
            <div class="form-group">
                <label for="username">Username</label>
                <input value="<?php echo htmlspecialchars($username); ?>" type="text" name="username" class="form-control" placeholder="Enter your first name" required>
            </div>
            <div class="form-group">
                <label for="firstName">First Name</label>
                <input value="<?php echo htmlspecialchars($firstName); ?>" type="text" name="firstName" class="form-control" placeholder="Enter your first name" required>
            </div>
            <div class="form-group">
                <label for="lastName">Last Name</label>
                <input value="<?php echo htmlspecialchars($lastName); ?>" type="text" name="lastName" class="form-control" placeholder="Enter your last name" required>
            </div>
            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 666 && $_SESSION['customer_id'] != $currentUserId): ?>
                <div class="from-group">
                    <label for="isAdmin">Is admin</label>
                    <input type="checkbox" name="isAdmin" <?php if ($isAdmin) echo "checked"; ?> >
                </div>
            <?php endif; ?>
            <!-- <div class="form-group">
                <label for="address">Address</label>
                <input type="text" name="address" class="form-control" placeholder="Enter your last name" required>
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" name="city" class="form-control" placeholder="Enter your city" required>
            </div>
            <div class="form-group">
                <label for="region">Region</label>
                <input type="text" name="region" class="form-control" placeholder="Enter your region" required>
            </div>
            <div class="form-group">
                <label for="country">Country</label>
                <input type="text" name="country" class="form-control" placeholder="Enter your country" required>
            </div>
            <div class="form-group">
                <label for="postal">Postal Code</label>
                <input type="text" name="postal" pattern="[0-9]{5}" class="form-control"
                    placeholder="Enter your postal code" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" name="phone" pattern="^\+\d+$" class="form-control" placeholder="Enter your phone number"
                    required>
            </div> -->
            <button type="submit" name="update" value="form2" class="btn btn-primary">Change</button>
        </form>
        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 666 && $_SESSION['customer_id'] != $currentUserId): ?>
            </form method="post" id="form2" action="?page=edit_user&id=<?php echo $currentUserId; ?>">
                <button type="submit" name="delete" value="form2" class="btn btn-primary">Delete</button>
            </form>
        <?php endif; ?>
    </div>
</div>  

