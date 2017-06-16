<?php

require_once 'User.php';

function loggedUser($conn)
{
    if (isset($_SESSION['user'])) {
        return User::loadUserById($conn, $_SESSION['user']);
    }

    return false;
}
