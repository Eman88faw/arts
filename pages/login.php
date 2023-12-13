<?php
// Include necessary files and initialize the session
include_once "connDB.php";
//include 'includes/navigation.php';
// Check if the user is already logged in, redirect to the home page
if (isset($_SESSION['user_name'])) {
    header("Location: index.php?page=home");
    exit();
}
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // In a real-world scenario, you would validate the user input more thoroughly
    $username = $_POST['username'];
    $password = $_POST['password'];

//    $sql = "select * from `customerlogon` where `UserName`=$username and Pass=$password";
    $sql = "SELECT * FROM `customerlogon` WHERE `UserName` = '$username' AND `Pass` = '$password' Limit 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Validate the username and password (basic example, should be improved for production)
    if ($result) {
        // Authentication successful, set session variables
        $_SESSION['user_name'] = $result[0]["UserName"]; // Set a user ID or other relevant information
        $_SESSION['user_id'] = $result[0]["CustomerID"]; // Set a user ID or other relevant information
        header("Location: index.php?page=home");
        exit();
    } else {
        $error_message = 'Invalid username or password.';
    }
}
?>
<style>
    .header{
        display: none;
    }
    .login {
        max-width: 400px;
        margin: 50px auto;
        background-color: white;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .login form {
        display: flex;
        flex-direction: column;
    }

    .login label {
        margin-bottom: 8px;
    }

    .login input {
        padding: 10px;
        margin-bottom: 16px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .login button {
        background-color: #333;
        color: white;
        padding: 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .login button:hover {
        background-color: #555;
    }

</style>


<div class="fixed-top bg-white">
    <div class="login">
        <h1>Login</h1>

        <?php if (isset($error_message)) : ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form method="post" action="?page=login">
            <label for="username">Username:</label>
            <input type="text" name="username" autocomplete="luisg@embraer.com.br" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>
    </div>
</div>

<?php //include 'includes/scripts.php'; ?>
