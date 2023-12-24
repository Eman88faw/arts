<?php
    if (isset($_SESSION['user_name'])) {
        session_unset();
        header("Location: index.php?page=home");
        exit();
    }
?>
