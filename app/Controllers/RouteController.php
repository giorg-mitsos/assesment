<?php

namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\config\Database;
use PDO;

class RouteController
{
    private PDO $db;
    private LoginController $loginController;
    private EmployeeController $employeeController;
    private ManagerController $managerController;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->employeeController = new EmployeeController();
        $this->managerController = new ManagerController();
        $this->loginController = new LoginController($this->db);
    }

    public function route(string $route): void
    {
        $urlParts = parse_url($route);
        $path = $urlParts['path'];

        $segments = explode('/', trim($path, '/'));
        $root = $segments[0] ?? null;

        switch ($root) {
            case null:
            case 'login':
                $this->loginController->login();
                break;

            case 'manager':
                $this->handleRoleBasedRoute('manager', $segments);
                break;

            case 'employee':
                $this->handleRoleBasedRoute('employee', $segments);
                break;

            case 'logout':
                $this->loginController->logout();
                break;

            case '404':
                $this->notFound();
                break;

            case '403':
                $this->accessDenied();
                break;

            default:
                header('Location: /404');
                break;
        }
    }

    private function checkAccess(string $role): void
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== $role) {
            header('Location: /403');
            exit;
        }
    }

    private function handleRoleBasedRoute(string $role, array $segments): void
    {
        $this->checkAccess($role);

        $controller = $role === 'manager' ? $this->managerController : $this->employeeController;
        $action = $segments[1] ?? 'dashboard';

        if (method_exists($controller, $action)) {
            $controller->{$action}();
        } else {
            header('Location: /404');
        }
    }

    public function notFound(): void
    {
        http_response_code(404);
        $this->render('Error/404', ['homeUrl' => $this->home()]);
    }

    public function accessDenied(): void
    {
        http_response_code(403);
        $this->render('Error/403', ['homeUrl' => $this->home()]);
    }

    public function home(): string
    {
        if (!isset($_SESSION['user_id'])) {
            return '/login';
        }
        return $_SESSION['user_role'] === 'manager'
            ? '/manager/dashboard'
            : ($_SESSION['user_role'] === 'employee' ? '/employee/dashboard' : '/login');
    }

    public function render(string $view, array $data = []): void
    {
        extract($data);
        include __DIR__ . "/../Views/$view.php";
    }
}
