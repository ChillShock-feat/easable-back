<?php
class Database
{
    public $pdo;

    public function __construct($db_dns, $db_user, $db_pass)
    {
        $this->pdo = new PDO($db_dns, $db_user, $db_pass);
        if ($this->pdo === null) {
            die("Connection Error:");
        }
        return $this->pdo;
    }
}

class user_database_func extends Database
{

    public function selectProcess($sql, $input_parameters = NULL)
    {
        $stmt = $this->pdo->prepare($sql);
        $flag = $stmt->execute($input_parameters);
        if (!$flag) {
            return [];
        }
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function updateProcess($sql, $input_parameters = NULL)
    {
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute($input_parameters);
        return $result;
    }

    public function insertProcess($sql, $input_parameters = NULL)
    {
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute($input_parameters);
        return $result;
    }

    public function deleteProcess($sql, $input_parameters = NULL)
    {
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute($input_parameters);
        return $result;
    }
}

