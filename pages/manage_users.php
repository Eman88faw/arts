<?php

include $_SERVER['DOCUMENT_ROOT'].'/arts/repo/UserRepository.php';
include $_SERVER['DOCUMENT_ROOT'].'/arts/entities/User.php';
include $_SERVER['DOCUMENT_ROOT'].'/arts/testDatabase.php';


// Create a database connection (assuming you have a Database class)
$db = new testDatabase();

// Initialize UserRepository with the database connection
$userRepo = new UserRepository($db);

// // Creating a new user
$newUser = new User(1, 'john_doe', 'John', 'Doe', 1); // Assuming 1 is for normal user state
$userRepo->updateUser($newUser, 'password123'); // Assuming this creates a new user if not exists

?>