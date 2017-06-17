<?php

class User {
    private $id;
    private $username;
    private $email;
    private $password;

    function __construct() {
        $this->id = -1;
        $this->username = '';
        $this->email = '';
        $this->password = '';
    }

    function setUsername($username) {
        $this->username = $username;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function setHash($hash)
    {
        $this->password = $hash;
    }

    function getId() {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    function getPassword() {
        return $this->password;
    }

    static public function loadUserByUsername(mysqli $conn, $username)
    {
        $username = $conn->real_escape_string($username);

        $sql = "SELECT * FROM `user` WHERE `username` = '$username' ";

        $result = $conn->query($sql);

        if(!$result){
            die('Query error' . $conn->error);
        }

        if($result->num_rows === 1){
            $userArray = $result->fetch_assoc();

            $user = new User();

            $user->setId($userArray['id']);
            $user->setUsername($userArray['username']);
            $user->setEmail($userArray['email']);
            $user->setHash($userArray['password']);

            return $user;
        }else {
            return false;
        }
    }

    static public function loadUserById(mysqli $conn, $id)
    {
        $id = $conn->real_escape_string($id);

        $sql = "SELECT * FROM `user` WHERE `id` = '$id'";

        $result = $conn->query($sql);

        if(!$result){
            die('Query error' . $conn->error);
        }

        if($result->num_rows === 1){
            $userArray = $result->fetch_assoc();

            $user = new User();

            $user->setId($userArray['id']);
            $user->setUsername($userArray['username']);
            $user->setEmail($userArray['email']);
            $user->setHash($userArray['password']);

            return $user;
        } else {
            return false;
        }
    }

    public function save(mysqli $conn)
    {
        if (-1 === $this->id){
            $sql = sprintf("INSERT INTO `user` (`email`, `username`, `password`) VALUES ('%s', '%s', '%s')",
                $this->email,
                $this->username,
                $this->password
            );

            $result = $conn->query($sql);

            if ($result){
                $this->id = $conn->insert_id;
            }else{
                die ("User not saved: " . $conn->error);
            }
        }
    }
}