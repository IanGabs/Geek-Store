<?php
require_once '../controllers/CartController.php';

$controller = new CartController();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        $controller->add();
        break;
    case 'PUT':
        $controller->update();
        break;
    case 'DELETE':
        $controller->remove();
        break;
    case 'GET':
        $controller->get();
        break;
    default:
        http_response_code(405);
        echo json_encode(['status' => 'error', 'message' => 'Método não permitido']);
        break;
}
?>