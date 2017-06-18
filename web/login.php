<?php

require_once '../src/connection.php';
require_once '../src/User.php';

session_start();

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
	if ( isset( $_POST[ 'username' ] ) && isset( $_POST[ 'password' ] ) ) {
		$username = $_POST[ 'username' ];
		$password = $_POST[ 'password' ];

		$user = User::loadUserByUsername( $conn, $username );

		if ( ( !$user ) || ( !password_verify( $password, $user->getPassword() ) ) ) {
			echo '<p>Incorrect username or password</p>';
			echo "<p><a href='loginForm.php'>Try to login again</a></p>";
			exit;
		} else {
			$_SESSION[ 'user' ] = $user->getId();
		}
	}
}

header( 'location: index.php' );