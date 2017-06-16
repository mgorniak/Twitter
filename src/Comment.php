<?php

class Comment
{
    private $id;
    private $userId;
    private $postId;
    private $creationDate;
    private $text;

    public function __construct($id, $userId, $postId, $creationDate, $text)
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

    
}