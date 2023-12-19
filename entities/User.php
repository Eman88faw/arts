<?php

class User
{
    const STATE_USER = 1;
    const STATE_ADMIN = 666;
    const STATE_DELETED = 0;

    private $id;
    private $username;
    private $firstName;
    private $lastName;
    private $state;

    public function __construct($id, $username, $firstName, $lastName, $state)
    {
        $this->id = $id;
        $this->username = $username;
        $this->lastName = $lastName;
        $this->firstName = $firstName;
        $this->state = $this->evaluateState($state);
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getFirstName() : String
    {
        return $this->firstName;
    }

    public function getLastName() : String
    {
        return $this->lastName;
    }

    public function getUsername() : String
    {
        return $this->username;
    }

    public function getState() : int
    {
        return $this->state;
    }

    public function isUser() : bool
    {
        return $this->state == self::STATE_USER;
    }

    public function isAdmin() : bool
    {
        return $this->state == self::STATE_ADMIN;
    }

    public function isDeleted() : bool
    {
        return $this->state == self::STATE_DELETED;
    }

    public function isDefaultUser() : bool
    {
        return $this->id == -1;
    }

    private function evaluateState($state) : int
    {
        switch($state)
        {
            // normal user
            case self::STATE_USER: return self::STATE_USER;
            // administrator
            case self::STATE_ADMIN: return self::STATE_ADMIN;
            // deleted user
            case self::STATE_DELETED: return self::STATE_DELETED;
            default: return self::STATE_DELETED;
        }
    }

    public static function fromState(array $user) : User
    {
        $id = $user["id"];
        $username = $user["username"];
        $firstName = $user["firstName"];
        $lastName = $user["lastName"];
        $state = $user["state"];

        return new self($id, $username, $firstName, $lastName, $state);
    }

    public static function getDefaultUser() : User
    {
        return new self(-1, "", "", "", 0);
    }
}