<?php

namespace App\Controllers;


use App\Models\User;
use App\Models\Vacation;


class ManagerController
{
    private $User;
    private $Vacation;

    public function __construct()
    {
        $this->User = new User();
        $this->Vacation = new Vacation();
    }

    public function dashboard(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            $data['users'] = $this->User->all();

            $usersById = [];
            foreach ($data['users'] as $user) {
                $usersById[$user['id']] = $user['name'];
            }

            $vacations = $this->Vacation->all();
            $data['vacations_pending'] = $this->Vacation->whereStatus('pending');

            foreach ($data['users'] as &$user) {
                $user['pending_vacations'] = count(array_filter($vacations, function ($vacation) use ($user) {
                    return $vacation['user_id'] === $user['id'] && $vacation['status'] === 'pending';
                }));
            }
            unset($user);

            foreach ($vacations as &$vacation) {
                if (isset($usersById[$vacation['user_id']])) {
                    $vacation['user_name'] = $usersById[$vacation['user_id']];
                } else {
                    $vacation['user_name'] = 'Unknown';
                }
            }
            unset($vacation);

            $data['vacations'] = $vacations;

            include __DIR__ . '/../Views/Manager/Dashboard.php';
            exit();
        }
    }



    public function createUser(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $role = $_POST['role'];
            $employee_code = $_POST['employee_code'];
            $this->User->create($name, $email, $password, $role, $employee_code);

            header('Location: /manager/dashboard');
            exit();
        }
    }

    public function deleteUser(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $employee_code = $_POST['employee_code'];
            $user = $this->User->whereEmployeeCode($employee_code);

            if ($user['employee_code'] === $_SESSION['employee_code']) {
                $_SESSION['error'] = 'You can\'t delete your own account';
                header('Location: /manager/dashboard');
                exit();
            }

            if ($user) {
                $this->Vacation->deleteUserVacationRequests($user['id']);
                $this->User->delete($user['employee_code']);
            }

            header('Location: /manager/dashboard');
            exit();
        }
    }

    public function showUser(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            $user = $this->User->whereEmployeeCode($_GET['employee_code']);
            $vacations = $this->Vacation->whereUserId($user['id']);

            include __DIR__ . '/../Views/Manager/Show.php';
            exit();
        }
    }

    public function editUser(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $employee_code = $_POST['employee_code'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $updateData = [
                'name' => $name,
                'email' => $email
            ];

            if (!empty($password)) {
                $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            } else {
                $passwordHash = null;
            }

            if ($passwordHash) {
                $updateData['password'] = $passwordHash;
            }

            $this->User->update($employee_code, $updateData);

            header('Location: /manager/showUser?employee_code=' . $employee_code);
            exit();
        }
    }

    public function updateVacationStatus(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (isset($_POST['vacation_id']) && isset($_POST['status'])) {
                $vacationId = $_POST['vacation_id'];
                $status = $_POST['status'];

                $updated = $this->Vacation->update($vacationId, ['status' => $status]);

                if ($updated) {
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                    exit();
                } else {
                    echo "Error updating vacation status.";
                }
            }
        }
    }
}
