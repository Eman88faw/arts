<?php

include $_SERVER['DOCUMENT_ROOT'].'/arts/utils/authManager.php';

$authManager = new AuthManager();
$errorMessage = "";

// Check if the user is already logged in, redirect to the home page
if (isset($_SESSION['user_name'])) {
    header("Location: index.php?page=home");
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize email
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = $_POST["password"];

    $result = $authManager->getUserFromDatabase($email, $password);

    if ($result) {
        $_SESSION['user_name'] = htmlspecialchars($result["UserName"]);
        $_SESSION['user_id'] = htmlspecialchars($result["CustomerID"]);
        $_SESSION['state'] = htmlspecialchars($result["State"]);
        header("Location: index.php?page=home");
        exit();
    } else {
        $errorMessage = 'Invalid username or password.';
    }
}
?>
<style>
    .header {
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

        <?php if (isset($errorMessage)): ?>
            <p style="color: red;">
                <?php echo $errorMessage; ?>
            </p>
        <?php endif; ?>

        <form method="post" action="?page=login">
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" class="form-control"
                placeholder="Enter email" required>
            <small class="form-text text-muted">We'll never share your email with anyone else.</small>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" minlength="8" name="password" class="form-control" placeholder="Enter your password"
                required>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input">
            <label class="form-check-label" for="exampleCheck1">Check me out</label>
        </div>
        <?php
        ?>
        <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</div>