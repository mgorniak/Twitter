<?php

class Message
{
	private $id;
	private $text;
	private $senderId;
	private $receiverId;
	private $readed;

	public function __construct()
	{
		$this->id = -1;
		$this->text = "";
		$this->senderId = "";
		$this->receiverId = "";
		$this->readed = 0;
	}

	public function setText( $text )
	{
		$this->text = $text;
	}

	public function setSenderId( $senderId )
	{
		$this->senderId = $senderId;
	}

	public function setReceiverId( $receiverId )
	{
		$this->receiverId = $receiverId;
	}

	public function setReaded( $readed )
	{
		$this->readed = $readed;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getText()
	{
		return $this->text;
	}

	public function getSenderId()
	{
		return $this->senderId;
	}

	public function getReceiverId()
	{
		return $this->receiverId;
	}

	public function getReaded()
	{
		return $this->readed;
	}

	public static function loadAllMessagesBySenderId( mysqli $conn, $senderId )
	{
		$senderId = $conn->real_escape_string( $senderId );

		$sql = "SELECT * FROM `message` WHERE `senderId` = '$senderId' ORDER BY `id` DESC";

		$result = $conn->query( $sql );

		if ( !$result ) {
			die ( "Query error" . $conn->error );
		}

		return $result;
	}

	public static function loadAllMessagesByReceiverId( mysqli $conn, $receiverId )
	{
		$receiverId = $conn->real_escape_string( $receiverId );

		$sql = "SELECT * FROM `message` WHERE `receiverId` = '$receiverId' ORDER BY `id` DESC";

		$result = $conn->query( $sql );

		if ( !$result ) {
			die ( "Query error" . $conn->error );
		}

		return $result;
	}

	public static function tickMessageReadedById( mysqli $conn, $id )
	{
		$id = $conn->real_escape_string( $id );

		$sql = "SELECT * FROM `message` WHERE `id` = $id";

		$result = $conn->query( $sql );
		$result = $result->fetch_assoc();

		if ( $result[ 'readed' ] == 0 ) {
			$sql = "UPDATE `message` SET `readed`='1' WHERE `id` = $id";
		}
//        elseif ($result['readed'] == 1){
//            $sql = "UPDATE `message` SET `readed`='0' WHERE `id` = $id";
//        }

		$result = $conn->query( $sql );

		if ( !$result ) {
			die ( "Query error" . $conn->error );
		}
	}

	public function saveMessageToDb( mysqli $conn )
	{
		if ( $this->id === -1 ) {
			$sql = sprintf( "INSERT INTO `message` (`text`, `senderId`, `receiverId`, `readed`) 
                                  VALUES ('%s', '%d', '%d', '%d')",
				$this->text,
				$this->senderId,
				$this->receiverId,
				$this->readed
			);

			$result = $conn->query( $sql );

			if ( $result ) {
				$this->id = $conn->insert_id;
			} else {
				die ( "Comment is not saved: " . $conn->error );
			}
		}
	}


}