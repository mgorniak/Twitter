<?php

require_once '../src/lib.php';
require_once '../src/connection.php';

session_start();
$user = loggedUser($conn);

if ( ($user) && (isset($_POST['allOrUserTweets']) && ($_SERVER['REQUEST_METHOD'] === 'POST') ) ) {
    $_SESSION['view'] = $_POST['allOrUserTweets'];
}

header('location: index.php');