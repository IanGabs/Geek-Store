<?php
session_start();
require_once 'controllers/AdminController.php';

$controller = new AdminController();
$controller->index();
?>