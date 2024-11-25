<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

require_once '../vendor/autoload.php';

use App\Controllers\RouteController;

$routeController = new RouteController();

$routeController->route($_SERVER['REQUEST_URI']);
