<?php
require_once '../controllers/CartController.php';

$controller = new CartController();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['action']) && $data['action'] === 'finalizar') {
            $controller->finalizar();
        } else {
            $controller->add();
        }
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