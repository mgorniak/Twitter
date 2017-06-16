<?php

session_start();

if (isset($_SESSION['user'])) {
    unset($_SESSION['user']);
    unset($_SESSION['view']);
    $_SESSION['logout'] = "Logout correct.";
}

header('Location: index.php');
