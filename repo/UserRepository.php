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
            default: $sort_field = "username"; break;
        }

        switch($order)
        {
            case SortOrder::ASCENDING: $sort_order = "ASC"; break;
            case SortOrder::DESCENDING: $sort_order = "DESC"; break;
            default: $sort_order = "ASC"; break;
        }

        $sql = "SELECT l.CustomerID as id, c.FirstName as firstName, c.LastName as lastName, l.UserName as username, l.State as state  
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

        $sql = "SELECT l.CustomerID as id, c.FirstName as firstName, c.LastName as lastName, l.UserName as username, l.State as state 
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
}

class SortOrder
{
    const ASCENDING    = 86789;
    const DESCENDING    = 86790;
}

