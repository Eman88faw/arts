<?php

require_once("./utils/Logging.php");

class UserRepository
{
    private $database;

    function __construct($db)
    {
        $this->database = $db;
    }

    public function getUsersSorted(int $sort, int $order)
    {
        $sort_field = "username";
        $sort_order = "ASC";

        switch($sort)
        {
            case UserSortFields::FIRST_NAME: $sort_field = "firstName"; break;
            case UserSortFields::LAST_NAME: $sort_field = "lastName"; break;
            case UserSortFields::USERNAME: $sort_field = "username"; break;
            case UserSortFields::ADDRESS: $sort_field = "address"; break;
            case UserSortFields::CITY: $sort_field = "city"; break;
            case UserSortFields::REGION: $sort_field = "region"; break;
            case UserSortFields::COUNTRY: $sort_field = "country"; break;
            case UserSortFields::POSTAL: $sort_field = "postal"; break;
            case UserSortFields::PHONE: $sort_field = "phone"; break;
            case UserSortFields::EMAIL: $sort_field = "email"; break;
            case UserSortFields::STATE: $sort_field = "state"; break;
            default: $sort_field = "username"; break;
        }

        switch($order)
        {
            case SortOrder::ASCENDING: $sort_order = "ASC"; break;
            case SortOrder::DESCENDING: $sort_order = "DESC"; break;
            default: $sort_order = "ASC"; break;
        }

        $sql = "SELECT l.CustomerID as id, c.FirstName as firstName, c.LastName as lastName, l.UserName as username, c.Address as address, c.City as city, c.Region as region, c.Country as country, c.Postal as postal, c.Phone as phone, c.Email as email, l.State as state  
                FROM customerlogon l, customers c WHERE l.CustomerID = c.CustomerID
                ORDER BY $sort_field $sort_order";

        $users = array();

        try {
            $this->database->connect();
            $statement = $this->database->prepareStatement($sql);
            $statement->execute();

            while ($row = $statement->fetch()) {
                $user = User::fromState($row);
                $users[] = $user;
            }

        } catch (Exception $ex) {
            exit('Could not retrieve users: ' . $ex->getMessage());
        } finally {
            $this->database->close();
        }

        Logging::Log("All users retrieved from database.");

        return $users;
    }

    public function getUsers()
    {
        return $this->getUsersSorted(UserSortFields::USERNAME, SortOrder::ASCENDING);
    }

    public function getUser(int $id) : User
    {
        $user = User::getDefaultUser();

        $sql = "SELECT l.CustomerID as id, c.FirstName as firstName, c.LastName as lastName, l.UserName as username, c.Address as address, c.City as city, c.Region as region, c.Country as country, c.Postal as postal, c.Phone as phone, c.Email as email, l.State as state  
        FROM customerlogon l, customers c WHERE l.CustomerID = c.CustomerID AND l.CustomerID = :id";


        try {
            $this->database->connect();
            $statement = $this->database->prepareStatement($sql);
            $statement->bindValue(':id', $id);
            $statement->execute();

            while ($row = $statement->fetch()) {
                $user = User::fromState($row);
                break;
            }

        } catch (Exception $ex) {
            exit("'Could not retrieve user with ID '$id' : " . $ex->getMessage());
        } finally {
            $this->database->close();
        }

        Logging::Log("User with ID '$id' retrieved from database.");

        return $user;
    }
    public function updateUserByAdmin(User $user): bool
    {
        $id = $user->getId();

        // check for invalid users
        if($id < 1) {
            return false;
        }

        $result = false;

        try {
            $this->database->connect();

            $this->database->beginTransaction();

            // 1st step: update table "customerlogon"
            $sql = "UPDATE customerlogon SET UserName = :username, State = :state, DateLastModified = now() WHERE CustomerID = :id";
            $statement = $this->database->prepareStatement($sql);
            $statement->bindValue(':id', $id);
            $statement->bindValue(':username', $user->getUsername());
            $statement->bindValue(':state', $user->getState());
            $result = $statement->execute();

            if($result) {
                // 2nd step: update table "customers"
                $sql = "UPDATE customers SET firstname = :firstname, lastname = :lastname, Address = :address, City = :city, Region = :region, Country = :country, Postal = :postal, Phone = :phone, Email = :email WHERE CustomerID = :id";
                $statement = $this->database->prepareStatement($sql);
                $statement->bindValue(':id', $id);
                $statement->bindValue(':firstname', $user->getFirstName());
                $statement->bindValue(':lastname', $user->getLastName());
                $statement->bindValue(':address', $user->getAddress());
                $statement->bindValue(':city', $user->getCity());
                $statement->bindValue(':region', $user->getRegion());
                $statement->bindValue(':country', $user->getCountry());
                $statement->bindValue(':postal', $user->getPostal());
                $statement->bindValue(':phone', $user->getPhone());
                $statement->bindValue(':email', $user->getEmail());
                $result = $statement->execute();

                $this->database->commit();
            }
        } catch (Exception $ex) {
            $this->database->rollback();
            exit("'Could not retrieve user with ID '$id' : " . $ex->getMessage());
        } finally {
            $this->database->close();
        }

        Logging::Log("User with ID '$id' updated.");

        return $result;
    }

    public function deleteUser(int $id) : bool
    {
        $result = false;
        // check for invalid users
        if($id < 1) {
            return false;
        }

        try {
            $this->database->connect();

            $this->database->beginTransaction();
            
            $sql = "UPDATE customerlogon SET State = :state, DateLastModified = now() WHERE CustomerID = :id";
            $statement = $this->database->prepareStatement($sql);
            $statement->bindValue(':id', $id);
            $statement->bindValue(':state', 0);
            $result = $statement->execute();

            if ($result) {
                $this->database->commit();
                Logging::Log("User with ID '$id' deleted.");
            } else {
                $this->database->rollback();
            }
        } catch (Exception $ex) {
            $this->database->rollback();
            exit("'Could not delete user with ID '$id' : " . $ex->getMessage());
        } finally {
            $this->database->close();
        }

        Logging::Log("User with ID '$id' deleted.");

        return $result;
    }

    public function updateUser(User $user, String $password) : bool
    {
        $id = $user->getId();

        // check for invalid users
        if($id < 1) {
            return false;
        }

        $result = false;
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $this->database->connect();

            $this->database->beginTransaction();

            // 1st step: update table "customerlogon"
            $sql = "UPDATE customerlogon SET UserName = :username, Pass = :pass, State = :state, DateLastModified = now() WHERE CustomerID = :id";
            $statement = $this->database->prepareStatement($sql);
            $statement->bindValue(':id', $id);
            $statement->bindValue(':username', $user->getUsername());
            $statement->bindValue(':pass', $password_hash);
            $statement->bindValue(':state', $user->getState());
            $result = $statement->execute();

            if($result) {
                // 2nd step: update table "customers"
                $sql = "UPDATE customers SET firstname = :firstname, lastname = :lastname WHERE CustomerID = :id";
                $statement = $this->database->prepareStatement($sql);
                $statement->bindValue(':id', $id);
                $statement->bindValue(':firstname', $user->getFirstName());
                $statement->bindValue(':lastname', $user->getLastName());
                $result = $statement->execute();

                $this->database->commit();
            }
        } catch (Exception $ex) {
            $this->database->rollback();
            exit("'Could not retrieve user with ID '$id' : " . $ex->getMessage());
        } finally {
            $this->database->close();
        }

        Logging::Log("User with ID '$id' updated.");

        return $result;
    }
}

class UserSortFields
{
    const FIRST_NAME    = 86786;
    const LAST_NAME    = 86787;
    const USERNAME    = 86788;
    const ADDRESS   = 86789;
    const CITY   = 86790;
    const REGION  = 86791;
    const COUNTRY  = 86792;
    const POSTAL  = 86793;
    const PHONE  = 86794;
    const EMAIL  = 86795;
    const STATE  = 86796;
}

class SortOrder
{
    const ASCENDING    = 86789;
    const DESCENDING    = 86790;
}

