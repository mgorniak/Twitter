<?php

require_once '../src/lib.php';
require_once '../src/connection.php';
require_once '../src/Message.php';

session_start();
$user = loggedUser( $conn );

if ( $user ) {
	$id = $_POST[ 'tick' ];
	Message::tickMessageReadedById( $conn, $id );
	header( "location: mailBox.php" );
} else {
	header( "location: index.php" );
}