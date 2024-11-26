<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Vacation;

class EmployeeController
{

    public function dashboard(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            $Vacation = new Vacation();

            $data['vacation_requests'] = $Vacation->whereUserId($_SESSION['user_id']);

            include __DIR__ . '/../Views/Employee/Dashboard.php';
            exit();
        }
    }

    public function updateVacationRequest(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $vacationId = $_POST['vacation_id'];
            $startDate = $_POST['start_date'];
            $endDate = $_POST['end_date'];
            $reason = $_POST['reason'];
            $userId = $_SESSION['user_id'];


            if (empty($startDate) || empty($endDate) || empty($reason)) {
                $_SESSION['error'] = "All fields are required.";
                header('Location: /employee/dashboard');
                exit;
            }


            if (strtotime($startDate) > strtotime($endDate)) {
                $_SESSION['error'] = "Start date cannot be after end date.";
                header('Location: /employee/dashboard');
                exit;
            }


            $Vacation = new Vacation();

            $existingVacations = $Vacation->whereUserId($userId);
            foreach ($existingVacations as $vacation) {
                if ($vacation['id'] == $vacationId) {
                    continue;
                }

                if (
                    (strtotime($startDate) <= strtotime($vacation['end_date']) &&
                        strtotime($endDate) >= strtotime($vacation['start_date']))
                ) {
                    $_SESSION['error'] = "Your vacation request overlaps with an existing one.";
                    header('Location: /employee/dashboard');
                    exit;
                }
            }

            $updated = $Vacation->update($vacationId, [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'reason' => $reason,
            ]);

            if ($updated) {
                header('Location: /employee/dashboard');
                exit();
            } else {
                $_SESSION['error'] = "Error updating vacation request.";
                header('Location: /employee/dashboard');
                exit();
            }
        }
    }


    public function createVacationRequest(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $startDate = $_POST['start_date'];
            $endDate = $_POST['end_date'];
            $reason = $_POST['reason'];
            $userId = $_SESSION['user_id'];

            if (empty($startDate) || empty($endDate) || empty($reason)) {
                $_SESSION['error'] = "All fields are required.";
                header('Location: /employee/dashboard');
                exit;
            }

            if (strtotime($startDate) > strtotime($endDate)) {
                $_SESSION['error'] = "Start date cannot be after end date.";
                header('Location: /employee/dashboard');
                exit;
            }

            $Vacation = new Vacation();
            $existingVacations = $Vacation->whereUserId($userId);

            foreach ($existingVacations as $vacation) {
                if (
                    (strtotime($startDate) <= strtotime($vacation['end_date']) &&
                        strtotime($endDate) >= strtotime($vacation['start_date']))
                ) {
                    $_SESSION['error'] = "Your vacation request overlaps with an existing one.";
                    header('Location: /employee/dashboard');
                    exit;
                }
            }

            $result = $Vacation->create($userId, $startDate, $endDate, $reason);
            if (!$result) {
                $_SESSION['error'] = "Failed to create vacation request.";
            }

            header('Location: /employee/dashboard');
            exit;
        }
    }

    public function deleteVacationRequest(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $vacationId = $_POST['vacation_id'];
            $Vacation = new Vacation();
            $Vacation->delete($vacationId);

            header('Location: /employee/dashboard');
            exit();
        }
    }
}
