<?php
require_once '../src/connection.php';

class Tweet
{
	private $id;
	private $text;
	private $userId;
	private $creationDate;

	public function __construct()
	{
		$this->id = -1;
		$this->userId = "";
		$this->text = "";
		$this->creationDate = "";
	}

	public function getId()
	{
		return $this->id;
	}

	public function getUserId()
	{
		return $this->userId;
	}

	public function setUserId( $userId )
	{
		$this->userId = $userId;
	}

	public function getText()
	{
		return $this->text;
	}

	public function setText( $text )
	{
		$this->text = $text;
	}

	public function getCreationDate()
	{
		return $this->creationDate;
	}

	public function setCreationDate( $creationDate )
	{
		$this->creationDate = $creationDate;
	}

	public static function loadTweetById( mysqli $conn, $id )
	{
		$id = $conn->real_escape_string( $id );

		$sql = "SELECT * FROM `tweet` WHERE `id` = '$id''";

		$result = $conn->query( $sql );

		if ( !$result ) {
			die( "Query error" . $conn->error );
		}

		if ( $result->num_rows === 1 ) {
			$tweetArray = $result->fetch_assoc();

			$tweet = new Tweet();
			$tweet->setText( $tweetArray[ 'text' ] );
			$tweet->setCreationDate( $tweetArray[ 'creationDate' ] );
			$tweet->setUserId( $tweetArray[ 'userId' ] );

			return $tweet;
		} else {
			return false;
		}
	}

	public static function loadAllTweetsByUserId( mysqli $conn, $userId )
	{
		$userId = $conn->real_escape_string( $userId );

		$sql = "SELECT * FROM `tweet` WHERE `userId` = '$userId' ORDER BY `creationDate` DESC";

		$result = $conn->query( $sql );

		if ( !$result ) {
			die( 'Query error' . $conn->error );
		}

		return $result;
	}

	public static function loadAllTweets( mysqli $conn )
	{
		$sql = "SELECT * FROM `tweet` ORDER BY `creationDate` DESC";

		$result = $conn->query( $sql );

		if ( !$result ) {
			die( 'Query error' . $conn->error );
		}

		return $result;
	}

	public function saveToDb( mysqli $conn )
	{
		if ( $this->id === -1 ) {
			$sql = sprintf( "INSERT INTO `tweet` (`text`, `userId`, `creationDate`) VALUES ('%s', '%d', '%s')",
				$this->text,
				$this->userId,
				$this->creationDate
			);

			$result = $conn->query( $sql );

			if ( $result ) {
				$this->id = $conn->insert_id;
			} else {
				die ( "Tweet is not saved: " . $conn->error );
			}
		}
	}
}
