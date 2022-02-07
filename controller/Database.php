<?php

declare(strict_types=1);

namespace Controller;

use PDO;

class Database
{    
    private static PDO $connexion;

    public function setConnection(PDO $conn): void
    {
        $this->connexion = $conn;
    }

    public static function getConnection(): PDO
    {
        try {
            if (!self::$connexion instanceof PDO) {
                self::$connexion = new PDO(
                    'mysql:host=localhost:3306; dbname=test',
                    'JDonald',
                    "93isjodoiIJOISmm***22"
                );
                self::$connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$connexion->exec('SET CHARACTER SET UTF8');
            }
        } catch (\Throwable $th) {
            die("An error has happened -> {$th->getMessage()}; Near in line -> {$th->getLine()}");
        } finally {
            return self::$connexion;
        }
    }

    public static function execute(string $query, array $params)
    {
        $connexion = Database::getConnection();
        $connexion->beginTransaction();
        try {
            $result = $connexion->prepare($query);
            $result->execute($params);
            $connexion->commit();
        } catch (\Throwable $th) {
            $connexion->rollBack();
            die("An error has ocurred in
                 database connection to resource:
                 {$th->getMessage()}");
        } finally {
            $connexion = null;
            return $result;
        }
    }

    public static function fetch(string $query, array $params = [])
    {
        $connexion = Database::getConnection();
        $connexion->beginTransaction();
        try {
            $result = $connexion->prepare($query);
            (empty($params)) ? $result->execute() : $result->execute($params);
            $connexion->commit();
        } catch (\Throwable $th) {
            $connexion->rollBack();
            die("An error has ocurred in
                 database connection to resource:
                 {$th->getMessage()}");
        } finally {
            $connexion = null;
            return $result->fetchAll();
        }
    }
}
