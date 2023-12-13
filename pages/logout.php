<?php
// Include necessary files and initialize the session
include 'includes/header.php';
include 'includes/navigation.php';

// Start the session

// Check if the user is already logged in, redirect to the home page
if (isset($_SESSION['user_name'])) {
    session_unset();
    header("Location: index.php?page=home");
    exit();
}
?>

<?php include 'includes/scripts.php'; ?>
