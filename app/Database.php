<?php

declare(strict_types=1);

namespace App;

use PDO;

class Database
{
    use Singleton;

    // private static PDO $connexion;

    private static string $connexion;

    private static Database $conn01;

    private static Database $conn02;

    private function __construct()
    {
        self::$connexion = "Soy la variable conexion";
        // try {
        //     self::$connexion = new PDO(
        //         'mysql:host=localhost:3306; dbname=test',
        //         'JDonald',
        //         "93isjodoiIJOISmm***22"
        //     );

        //     self::$connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //     self::$connexion->exec('SET CHARACTER SET UTF8');

        // } catch (\Throwable $th) {
        //     die("An error has happened -> {$th->getMessage()}; Near in line -> {$th->getLine()}");
        // }

        echo "<pre style='margin-bottom: 10px;' >1. Estoy ejecutando el Constructor de la clase Database:</pre>";
    }

    public static function execute(
        // string $query,
        // array $params
    ): \PDOStatement | false
    {
        self::$conn01 = self::getInstance();
        // $connexion = Database::getInstance();
        // $connexion->beginTransaction();

        // try {
        //     $result = $connexion->prepare($query);
        //     $result->execute($params);
        //     $connexion->commit();

        // } catch (\Throwable $th) {
        //     $connexion->rollBack();
        //     die("An error has ocurred in database connection to resource: {$th->getMessage()}");

        // } finally {
        //     $connexion = null;
        //     return $result;
        // }

        echo "<pre style='margin-bottom: 10px;' >2. Estoy ejecutando la funcion execute de la clase Database</pre>";
        echo "<pre style='margin-bottom: 10px;' >   2.1 Instanciación 1 de la clase Database: " . get_class(self::$conn01) . "</pre>";
        echo "<pre style='margin-bottom: 10px;' >   2.2 Conexion 1 de la clase Database: " . self::$conn01->getConnection() . "</pre>";
        return false;
    }

    public static function fetch(
        // string $query, 
        // array $params = []
    ): array
    {
        self::$conn02 = self::getInstance();
        // $connexion = Database::getInstance();
        // $connexion->beginTransaction();

        // try {
        //     $result = $connexion->prepare($query);
        //     (empty($params)) ? $result->execute() : $result->execute($params);
        //     $connexion->commit();

        // } catch (\Throwable $th) {
        //     $connexion->rollBack();
        //     die("An error has ocurred in database connection to resource: {$th->getMessage()}");

        // } finally {
        //     $connexion = null;
        //     return $result->fetchAll();
        // }

        echo "<pre style='margin-bottom: 10px;' >3. Estoy ejecutando la funcion fetch de la clase Database</pre>";
        echo "<pre style='margin-bottom: 10px;' >   3.1 Instanciación 2 de la clase Database: " . get_class(self::$conn02) . "</pre>";
        echo "<pre style='margin-bottom: 10px;' >   3.2 Conexion 2 de la clase Database: " . self::$conn02->getConnection() . "</pre>";
        return [];
    }

    public static function getConnection()
    {
        return self::$connexion;
    }

    public static function test()
    {
        if (self::$conn01 === self::$conn02) {
            echo "<pre style='margin-bottom: 10px;' >Son las mismas instancias</pre>";
        } else {
            echo "<pre style='margin-bottom: 10px;' >No son las mismas instancias</pre>";
        }
    }
}
