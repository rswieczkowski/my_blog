<?php

namespace libraries;

use Exception;
use PDO;
use PDOStatement;

class Database
{

    private string $host;
    private string $user;
    private string $password;
    private string $dbName;

    private PDO $dbh;
    private PDOStatement $stmt;


    public function __construct()
    {
        $this->host = DB_HOST;
        $this->user = DB_USER;
        $this->password = DB_PASSWORD;
        $this->dbName = DB_NAME;

        try {
            $this->dbh = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->dbName, $this->user, $this->password
            );
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            echo 'Connection error: ' . $e->getMessage();
        }
    }

    /**
     * @return PDOStatement
     */
    public function getStmt(): PDOStatement
    {
        return $this->stmt;
    }





    public function prepareQuery(string $query): bool|PDOStatement
    {
        $this->stmt = $this->dbh->prepare($query);

        return $this->stmt;
    }

    public function bind($param, $value, $type = null): bool
    {
        if (is_null($type)) {
            $type = match (true) {
                is_int($value) => PDO::PARAM_INT,
                is_string($value) => PDO::PARAM_STR,
                is_bool($value) => PDO::PARAM_BOOL,
                default => PDO::PARAM_NULL,
            };
        }

        return $this->stmt->bindValue($param, $value, $type);
    }


    public function executeStmt(): bool
    {
       return $this->stmt->execute();

    }


    public function getSingleRow(): bool|array
    {
        $this->executeStmt();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function getRowsInSet(): bool|array|object
    {
        $this->executeStmt();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}