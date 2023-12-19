<?php
include $_SERVER['DOCUMENT_ROOT'].'/arts/db.php';
include $_SERVER['DOCUMENT_ROOT'].'/arts/utils/functions.php';

class AuthManager
{
    private $db;

    public function __construct()
    {
        $this->db = new db();
    }

    public function getUserFromDatabase($email, $password)
    {
        echo $email;
        echo $password;
        $account = $this->db->query('SELECT * FROM customerlogon WHERE UserName = ? AND Pass = ?', $email, $password)->fetchArray();
        
        $this->closeDatabase();

        return $account;
    }

    public function addUserToDatabase($email, $password, $firstName, $lastName, $address, $city, $region, $country, $postal, $phone)
    {

        $existingUser = $this->db->query('SELECT * FROM customerlogon WHERE UserName = ?', $email)->fetchArray();

        if (sizeof($existingUser) > 0) {
            return false;
        }

        $this->db->query('INSERT INTO `customerlogon` (`CustomerID`, `UserName`, `Pass`, `Salt`, `State`, `Type`, `DateJoined`, `DateLastModified`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', [NULL, $email, $password, '', 1, 0, getCurrentDate(), getCurrentDate()]);
        $lastInsertedId = $this->db->lastInsertID();
        $this->db->query('INSERT INTO customers (CustomerID, FirstName, LastName, Address, City, Region, Country, Postal, Phone, Email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', $lastInsertedId, $firstName, $lastName, $address, $city, $region, $country, $postal, $phone, $email);
        $currentUser = $this->db->query('SELECT * FROM customerlogon WHERE UserName = ?', $email)->fetchArray();

        $this->closeDatabase();
        return $currentUser;
    }

    public function editUserInDatabase($email, $password, $firstName, $lastName, $address, $city, $region, $country, $postal, $phone)
    {
        $existingUser = $this->db->query('SELECT * FROM customerlogon WHERE UserName = ?', $email)->fetchArray();

        if (sizeof($existingUser) == 0) {
            return false;
        }

        $this->db->query('UPDATE `customerlogon` SET `Pass` = ?, `DateLastModified` = ? WHERE `customerlogon`.`UserName` = ?', $password, getCurrentDate(), $email);
        $this->db->query('UPDATE `customers` SET `FirstName` = ?, `LastName` = ?, `Address` = ?, `City` = ?, `Region` = ?, `Country` = ?, `Postal` = ?, `Phone` = ?, `Email` = ? WHERE `customers`.`CustomerID` = ?', $firstName, $lastName, $address, $city, $region, $country, $postal, $phone, $email, $existingUser['CustomerID']);
        $rowsAffected = $this->db->affectedRows();

        $this->closeDatabase();

        return $rowsAffected > 0;
    }

    public function closeDatabase()
    {
        $this->db->close();
    }
}
?>