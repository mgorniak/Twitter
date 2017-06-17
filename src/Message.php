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
        $this->readed = "";
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function setSenderId($senderId)
    {
        $this->senderId = $senderId;
    }

    public function setReceiverId($receiverId)
    {
        $this->receiverId = $receiverId;
    }

    public function setReaded($readed)
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

    static public function loadAllMessagesBySenderId(mysqli $conn, $senderId)
    {
        $senderId = $conn->real_escape_string($senderId);

        $sql = "SELECT * FROM `message` WHERE `senderId` = '$senderId' ORDER BY `id` DESC";

        $result = $conn->query($sql);

        if (!$result) {
            die ("Query error" . $conn->error);
        }

        return $result;
    }

    static public function loadAllMessagesByReceiverId(mysqli $conn, $receiverId)
    {
        $receiverId = $conn->real_escape_string($receiverId);

        $sql = "SELECT * FROM `message` WHERE `receiverId` = '$receiverId' ORDER BY `id` DESC";

        $result = $conn->query($sql);

        if (!$result) {
            die ("Query error" . $conn->error);
        }

        return $result;
    }

    public function saveMessageToDb(mysqli $conn)
    {
        if ($this->id === -1) {
            $sql = sprintf("INSERT INTO `message` (`text`, `senderId`, `receiverId`) 
                                  VALUES ('%s', '%d', '%d')",
                $this->text,
                $this->senderId,
                $this->receiverId
            );

            $result = $conn->query($sql);

            if ($result) {
                $this->id = $conn->insert_id;
            } else {
                die ("Comment is not saved: " . $conn->error);
            }
        }
    }
}