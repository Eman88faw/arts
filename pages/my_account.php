<?php

echo $_SESSION['user_id']

?>



<div class="container py-5">
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
            <input type="password" minlength="8" name="password" class="form-control" placeholder="Enter your password"
                required>
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
            <input type="text" name="postal" pattern="[0-9]{5}" class="form-control"
                placeholder="Enter your postal code" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="text" name="phone" pattern="^\+\d+$" class="form-control" placeholder="Enter your phone number"
                required>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input">
            <label class="form-check-label" for="checkbox">Check me out</label>
        </div>
        <button type="submit" class="btn btn-primary">Regestrieren</button>
    </form>
</div>
</div>  

