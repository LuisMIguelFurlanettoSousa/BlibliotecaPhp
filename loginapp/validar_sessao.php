<?php
    session_start();
    if (!isset($_SESSION['loggedin']) && $_SESSION['loggedin'] !== true) {
        header("Location: /loginapp/login.php?status=3");
        exit;
    }
?>