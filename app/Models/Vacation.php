<?php

namespace App\Models;

use PDO;
use App\Config\Database;

class Vacation {
    private static ?PDO $conn = null;
    private static string $table = 'vacation_requests';

    public function __construct() {
        if (self::$conn === null) {
            self::$conn = Database::getConnection();
        }
    }

    public static function create(int $user_id, string $start_date, string $end_date, string $reason, string $status = 'pending',): bool {
        $query = "INSERT INTO " . self::$table . " (user_id, start_date, end_date, reason, status) VALUES (:user_id, :start_date, :end_date, :reason, :status)";
        $stmt = self::$conn->prepare($query);

        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
        $stmt->bindParam(':reason', $reason);
        $stmt->bindParam(':status', $status);

        return $stmt->execute();
    }

    public static function find($vacation_id) {
        $query = "SELECT * FROM " . self::$table . " WHERE id = :vacation_id";
        $stmt = self::$conn->prepare($query);
        $stmt->bindParam(':vacation_id', $vacation_id);
        $stmt->execute();
        return $stmt->fetch();
    }
    public static function all(): ?array {
        $query = "SELECT * FROM " . self::$table . " ORDER BY 
              CASE WHEN status = 'pending' THEN 0 ELSE 1 END, id";
        $stmt = self::$conn->query($query);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return !empty($results) ? $results : null;
    }

    public static function update(int $id, array $fields): bool {
        if (empty($fields)) {
            return false;
        }

        $setClause = [];
        foreach ($fields as $column => $value) {
            $setClause[] = "$column = :$column";
        }
        $setClauseString = implode(', ', $setClause);

        $query = "UPDATE " . self::$table . " SET $setClauseString WHERE id = :id";

        $stmt = self::$conn->prepare($query);

        foreach ($fields as $column => &$value) {
            $stmt->bindParam(":$column", $value);
        }
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public static function whereUserId(int $user_id): ?array {
        $query = "SELECT * FROM " . self::$table . " WHERE user_id = :user_id
        ORDER BY CASE WHEN status = 'pending' THEN 0 ELSE 1 END, id";
        $stmt = self::$conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return !empty($results) ? $results : null;
    }

    public static function whereStatus(string $status = 'pending'): ?array {
        $query = "SELECT * FROM " . self::$table . " WHERE status = :status";
        $stmt = self::$conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return !empty($results) ? $results : null;
    }

    public static function delete(int $id): void {
        $query = "DELETE FROM " . self::$table . " WHERE id = :id";
        $stmt = self::$conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public static function deleteUserVacationRequests(int $user_id): void {
        $query = "DELETE FROM " . self::$table . " WHERE user_id = :user_id";
        $stmt = self::$conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
