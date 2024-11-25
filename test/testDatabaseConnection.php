<?php
require_once '../app/config/Database.php';

use App\config\Database;

$db = new Database();
$conn = $db->getConnection();

if ($conn) {
    echo "Connected successfully!\n";
} else {
    echo "Connection failed.\n";
}
