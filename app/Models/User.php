<?php

namespace App\Models;

use PDO;
use App\Config\Database;

class User
{
    private  static ?PDO $conn = null;
    private static string $table = 'users';

    public function __construct()
    {
        if (self::$conn === null) {
            self::$conn = Database::getConnection();
        }
    }

    public function create(string $name, string $email, string $password, string $role, int $employee_code): bool
    {
        if (self::whereEmail($email)) {
            $_SESSION['error'] = 'Email already exists';
            return false;
        }
        if (self::whereEmployeeCode($employee_code)) {
            $_SESSION['error'] = 'Employee Code already exists';
            return false;
        }
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $query = "INSERT INTO " . self::$table . " (name, email, password, role, employee_code) VALUES (:name, :email, :password, :role, :employee_code)";
        $stmt = self::$conn->prepare($query);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':employee_code', $employee_code);

        return $stmt->execute();
    }


    public function whereEmail(string $email): ?array
    {
        $query = "SELECT * FROM " . self::$table . " WHERE email = :email";
        $stmt = self::$conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function whereEmployeeCode(int $code): ?array
    {
        $query = "SELECT * FROM " . self::$table . " WHERE employee_code = :employee_code LIMIT 1";
        $stmt = self::$conn->prepare($query);
        $stmt->bindParam(':employee_code', $code);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function all(): ?array
    {
        $query = "SELECT name, email, role, id, employee_code FROM " . self::$table;
        $stmt = self::$conn->prepare($query);
        if ($stmt->execute()) {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return !empty($results) ? $results : null;
        }
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function update(int $employee_code, array $fields): bool
    {
        if (empty($fields)) {
            $_SESSION['error'] = 'No required fields provided';
            return false;
        }

        $user = self::whereEmail($fields['email']);

        if ($user) {
            $_SESSION['error'] = 'Email already exists';
            return false;
        }

        $setClause = [];
        foreach ($fields as $column => $value) {
            $setClause[] = "$column = :$column";
        }
        $setClauseString = implode(', ', $setClause);

        $query = "UPDATE " . self::$table . " SET $setClauseString WHERE employee_code = :employee_code";

        $stmt = self::$conn->prepare($query);

        foreach ($fields as $column => &$value) {
            $stmt->bindParam(":$column", $value);
        }
        $stmt->bindParam(':employee_code', $employee_code);

        return $stmt->execute();
    }


    public function delete(int $employee_code): bool
    {
        if ($employee_code === 1000000) {
            $_SESSION['error'] = 'You can\'t delete root account';
            return false;
        }
        $query = "DELETE FROM " . self::$table . " WHERE employee_code = :employee_code";
        $stmt = self::$conn->prepare($query);
        $stmt->bindParam(':employee_code', $employee_code);
        return $stmt->execute();
    }
}
