<?php
declare(strict_types=1);
require_once __DIR__ . '/../models/Customer.php';
require_once __DIR__ . '/../models/Item.php';
require_once __DIR__ . '/../models/Category.php';

final class Tools
{
    public static function connect(
        string $host = 'localhost',
        string $user = 'root',
        string $pass = '123',
        string $dbname = 'shop',
        int $port = 3306,
    ): PDO {
        $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";

        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE   => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC  
        ]);
    }

    public static function register(string $name, string $pass, string $imagepath): bool {
        $name = trim($name);
        $pass = trim($pass);

        if ($name === '' || $pass === '') {
            echo "<h3 style='color:red;'>Fill All Required Fields!</h3>";
            return false;
        }
        if (strlen($name) < 3 || strlen($name) > 30 ||
            strlen($pass) < 3 || strlen($pass) > 30) {
            echo "<h3 style='color:red;'>Values Length Must Be Between 3 And 30!</h3>";
            return false;
        }

        $err = new Customer($name, $pass, $imagepath)->intoDb(); // PHP 8.4 chaining on new

        if ($err !== true) {
            echo $err === 1062
                ? "<h3>This Login Is Already Taken!</h3>"
                : "<h3 style='color:red'>Error: " .
                htmlspecialchars((string)$err) . "</h3>";
            return false;
        }
        return true;
    }
}