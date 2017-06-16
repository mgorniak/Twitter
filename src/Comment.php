<?php

class Comment
{
    private $id;
    private $userId;
    private $postId;
    private $creationDate;
    private $text;

    public function __construct()
    {
        $this->id = -1;
        $this->userId = "";
        $this->postId = "";
        $this->creationDate = "";
        $this->text = "";
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function setPostId($postId)
    {
        $this->postId = $postId;
    }

    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getPostId()
    {
        return $this->postId;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function getText()
    {
        return $this->text;
    }

    static public function loadCommentById(mysqli $conn, $id)
    {
        $id = $conn->real_escape_string($id);

        $sql = "SELECT * FROM `comment` WHERE `id` = '$id'";

        $result = $conn->query($sql);

        if ($result) {
            die ("Query error" . $conn->error);
        }

        if ($result->num_rows === 1) {
            $commentArray = $result->fetch_assoc();

            $comment = new Comment();

//            $comment->setId($comment['id']);
            $comment->setText($commentArray['text']);
            $comment->setCreationDate($commentArray['creationDate']);
            $comment->setUserId($commentArray['userId']);
            $comment->setPostId($commentArray['postId']);

            return $comment;
        } else {
            return false;
        }
    }

    static public function loadAllCommentsByPostId(mysqli $conn, $postId)
    {
        $postId = $conn->real_escape_string($postId);

        $sql = "SELECT * FROM `comment` WHERE `postId` = '$postId' ORDER BY `creationDate` DESC";

        $result = $conn->query($sql);

        if (!$result) {
            die ("Query error" . $conn->error);
        }

        return $result;
    }

    public function saveToDb(mysqli $conn)
    {
        if ($this->id === -1) {
            $sql = sprintf("INSERT INTO `comment` (`userId`, `postId`, `creationDate`,  `text`) 
                                  VALUES ('%d', '%d', '%s', '%s')",
                $this->userId,
                $this->postId,
                $this->creationDate,
                $this->text
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