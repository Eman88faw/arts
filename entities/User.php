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
    private $address;
    private $city;
    private $region;
    private $country;
    private $postal;
    private $phone;
    private $email;
    private $state;

    public function __construct($id, $username, $firstName, $lastName, $address, $city, $region, $country, $postal, $phone, $email, $state)
    {
        $this->id = $id;
        $this->username = $username;
        $this->lastName = $lastName;
        $this->firstName = $firstName;
        $this->address = $address;
        $this->city = $city;
        $this->region = $region;
        $this->country = $country;
        $this->postal = $postal;
        $this->phone = $phone;
        $this->email = $email;
        $this->state = $this->evaluateState($state);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getState(): int
    {
        return $this->state;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getCity(): string
    {
        return $this->city;
    }
    public function getRegion(): string
    {
        return $this->region;
    }
    public function getCountry(): string
    {
        return $this->country;
    }
    public function getPostal(): string
    {
        return $this->postal;
    }
    public function getPhone(): string
    {
        return $this->phone;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function isUser(): bool
    {
        return $this->state == self::STATE_USER;
    }

    public function isAdmin(): bool
    {
        return $this->state == self::STATE_ADMIN;
    }

    public function isDeleted(): bool
    {
        return $this->state == self::STATE_DELETED;
    }

    public function isDefaultUser(): bool
    {
        return $this->id == -1;
    }

    private function evaluateState($state): int
    {
        switch ($state) {
            // normal user
            case self::STATE_USER:
                return self::STATE_USER;
            // administrator
            case self::STATE_ADMIN:
                return self::STATE_ADMIN;
            // deleted user
            case self::STATE_DELETED:
                return self::STATE_DELETED;
            default:
                return self::STATE_DELETED;
        }
    }

    public static function fromState(array $user): User
    {
        $id = $user["id"];
        $username = $user["username"] ?? "";
        $firstName = $user["firstName"] ?? "";
        $lastName = $user["lastName"] ?? "";
        $address = $user["address"] ?? "";
        $city = $user["city"] ?? "";
        $region = $user["region"] ?? "";
        $country = $user["country"] ?? "";
        $postal = $user["postal"] ?? "";
        $phone = $user["phone"] ?? "";
        $email = $user["email"] ?? "";
        $state = $user["state"];

        return new self($id, $username, $firstName, $lastName, $address, $city, $region, $country, $postal, $phone, $email, $state);
    }

    public static function getDefaultUser(): User
    {
        return new self(-1, "", "", "", "", "", "", "", "", "", "", 0);
    }
}