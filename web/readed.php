<?php

require_once '../src/lib.php';
require_once '../src/connection.php';

session_start();
$user = loggedUser($conn);

if ($user) {
    $_SESSION['box'] = "inbox";
    header ("location: mailBox.php");
} else {
    header ("location: index.php");
}