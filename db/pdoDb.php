<?php

include $_SERVER['DOCUMENT_ROOT'].'/arts/dbconfig.php';

class pdoDb {
    private $host = DBHOST;
    private $db   = DBNAME;
    private $user = DBUSER;
    private $pass = DBPASS;
    private $port = DBPORT;
    private $charset = 'utf8';

    private $pdo;
    private $error;

    public function __construct() {
        $this->estableConnection();
    }

    public function __destruct() {
        $this->pdo = null;
    }

    public function estableConnection() {
        // DSN (Data Source Name) configuration
        $dsn = "mysql:host=$this->host;dbname=$this->db;port=$this->port;charset=$this->charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo "Connection failed: " . $this->error;
        }
    }

    public function connect() {
        if ($this->pdo === null) {
            $this->estableConnection();
        }
        return $this->pdo;
    }
    

    public function prepareStatement($sql) {
        return $this->pdo->prepare($sql);
    }

    public function beginTransaction() {
        $this->pdo->beginTransaction();
    }

    public function commit() {
        $this->pdo->commit();
    }

    public function rollback() {
        $this->pdo->rollBack();
    }

    public function close() {
        $this->pdo = null;
    }
}
