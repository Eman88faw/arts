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

    $result = $authManager->addUserToDatabase($email, $password, $firstName, $lastName, $address, $city, $region, $country, $postal, $phone);

    if ($result) {
        $_SESSION['user_name'] = $result["UserName"];
        $_SESSION['user_id'] = $result["CustomerID"];
        $_SESSION['state'] = $result["State"];
        header("Location: index.php?page=home");
        exit();
    } else {
        $errorMessage = 'User already exists.';
    }
}
?>
<style>
    .header {
        display: none;
    }

    .register {
        max-width: 400px;
        margin: 50px auto;
        background-color: white;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .register form {
        display: flex;
        flex-direction: column;
    }

    .register label {
        margin-bottom: 8px;
    }

    .register input {
        padding: 10px;
        margin-bottom: 16px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .register button {
        background-color: #333;
        color: white;
        padding: 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .register button:hover {
        background-color: #555;
    }

    input {
        width: 100%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        margin-top: 6px;
        margin-bottom: 16px;
    }

    input[type=submit] {
        background-color: #04AA6D;
        color: white;
    }

    #message {
        display: none;
        background: #f1f1f1;
        color: #000;
        position: relative;
        padding: 20px;
        margin-top: 10px;
    }

    #message p {
        padding: 10px 35px;
        font-size: 18px;
    }

    .valid {
        color: green;
    }

    .valid:before {
        position: relative;
        left: -35px;
        content: "&#10004;";
    }

    .invalid {
        color: red;
    }

    .invalid:before {
        position: relative;
        left: -35px;
        content: "&#10006;";
    }
</style>
<div class="register">
    <h1>Register</h1>

    <?php if (isset($errorMessage)): ?>
        <p style="color: red;">
            <?php echo $errorMessage; ?>
        </p>
    <?php endif; ?>

    <form method="post" action="?page=register">
        <div class="form-group">
            <label for="exampleInputEmail1">Email address</label>
            <input type="email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" class="form-control"
                placeholder="Enter email" required>
            <small class="form-text text-muted">We'll never share your email with anyone else.</small>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <div id="password-message" style="color: red;"></div>
            <input type="password" id="password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
                required>
        </div>
        <div id="message">
            <h3>Password must contain the following:</h3>
            <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
            <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
            <p id="number" class="invalid">A <b>number</b></p>
            <p id="length" class="invalid">Minimum <b>8 characters</b></p>
        </div>
        <div class="form-group">
            <label for="firstName">First Name</label>
            <input type="text" name="firstName" class="form-control" placeholder="Enter your first name" required>
        </div>
        <div class="form-group">
            <label for="lastName">Last Name</label>
            <input type="text" name="lastName" class="form-control" placeholder="Enter your last name" required>
        </div>
        <div class="form-group">
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
            <input type="number" name="postal" pattern="[0-9]{5}" class="form-control"
                placeholder="Enter your postal code" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="number" name="phone" pattern="^\+\d+$" class="form-control"
                placeholder="Enter your phone number" required>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input">
            <label class="form-check-label" for="checkbox">Check me out</label>
        </div>
        <button type="submit" class="btn btn-primary">Regestrieren</button>
    </form>
</div>

<script>
    var myInput = document.getElementById("password");
    var letter = document.getElementById("letter");
    var capital = document.getElementById("capital");
    var number = document.getElementById("number");
    var length = document.getElementById("length");

    // When the user clicks on the password field, show the message box
    myInput.onfocus = function () {
        document.getElementById("message").style.display = "block";
    }

    // When the user clicks outside of the password field, hide the message box
    myInput.onblur = function () {
        document.getElementById("message").style.display = "none";
    }

    // When the user starts to type something inside the password field
    myInput.onkeyup = function () {
        // Validate lowercase letters
        var lowerCaseLetters = /[a-z]/g;
        if (myInput.value.match(lowerCaseLetters)) {
            letter.classList.remove("invalid");
            letter.classList.add("valid");
        } else {
            letter.classList.remove("valid");
            letter.classList.add("invalid");
        }

        // Validate capital letters
        var upperCaseLetters = /[A-Z]/g;
        if (myInput.value.match(upperCaseLetters)) {
            capital.classList.remove("invalid");
            capital.classList.add("valid");
        } else {
            capital.classList.remove("valid");
            capital.classList.add("invalid");
        }

        // Validate numbers
        var numbers = /[0-9]/g;
        if (myInput.value.match(numbers)) {
            number.classList.remove("invalid");
            number.classList.add("valid");
        } else {
            number.classList.remove("valid");
            number.classList.add("invalid");
        }

        // Validate length
        if (myInput.value.length >= 8) {
            length.classList.remove("invalid");
            length.classList.add("valid");
        } else {
            length.classList.remove("valid");
            length.classList.add("invalid");
        }
    }
</script>