<?php

namespace App\Controllers;

use App\Models\User;


class LoginController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function login()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            include __DIR__ . '/../Views/Login.php';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $User = new User();
            $user = $User->whereEmail($email);

            if ($user && password_verify($password, $user['password'])) {

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['employee_code'] = $user['employee_code'];

                unset($_SESSION['error']);

                if ($user['role'] === 'manager') {
                    header('Location: /manager/dashboard');
                } else {
                    header('Location: /employee/dashboard');
                }
            } else {
                $_SESSION['error'] = 'Wrong email or password';
                include __DIR__ . '/../Views/Login.php';
            }
            exit;
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: /login');
        exit;
    }
}
