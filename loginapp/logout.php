<?php
    session_start();
    session_unset();
    session_destroy();
    header("Location: /loginapp/login.php?status=2");
    exit;
?>