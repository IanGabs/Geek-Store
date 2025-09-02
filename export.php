<?php
require_once 'controllers/AdminController.php';

$controller = new AdminController();
$format = $_GET['format'] ?? 'json';
$controller -> exportProducts($format);
?>