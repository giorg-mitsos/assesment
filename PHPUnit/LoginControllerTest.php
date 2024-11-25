<?php

use PHPUnit\Framework\TestCase;

use App\Controllers\LoginController;

use App\config\Database;

use App\Models\User;

class LoginControllerTest extends TestCase
{
    protected function setUp(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        $this->db = (new Database())->getConnection();

        $this->loginController = new LoginController($this->db);
    }


    public function testLoginPageGETRequest()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $_SESSION = [];

        $this->loginController->login();

        $this->assertEmpty($_SESSION['error']);
    }

    public function testManagerDashboard()
    {
        $_SESSION['user_role'] = 'manager';
        $_SESSION['user_name'] = 'Alice Manager';
        $_SESSION['user_id'] = 1;

        ob_start();
        $this->loginController->login();
        $output = ob_get_clean();

        $this->assertStringContainsString('Welcome, Manager Alice Manager (ID: hello)', $output);
    }

}
