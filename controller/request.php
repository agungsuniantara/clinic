<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../controller/controller.php';

class RequestHandler
{
    private $controller;

    public function __construct(Controller $controller)
    {
        $this->controller = $controller;
    }

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = isset($_POST['action']) ? $_POST['action'] : '';

            switch ($action) {
                case 'login':
                    $email = $_POST['Email'];
                    $password = $_POST['Password'];

                    $account = $this->controller->login($email, $password);

                    if ($account) {
                        $_SESSION['user'] = $account;
                        echo json_encode($account);
                        exit();
                    } else {
                        echo json_encode(["message" => "username atau password salah"]);
                        exit();
                    }
                    break;
                case 'read':
                    $roles = $_POST['role'];
                    $this->controller->read($roles);
                    exit();
                    break;
                case 'create':
                    $akun = $_POST['role'];
                    $this->controller->create($akun);
                    exit();
                    break;
                case 'update':
                    $id = $_POST['id'];
                    $this->controller->update($id);
                    exit();
                    break;
                case 'updateStatus':
                    $id = $_POST['id'];
                    $this->controller->updateStatus($id);
                    exit();
                    break;
                case 'delete':
                    $id = $_POST['id'];
                    $this->controller->delete($id);
                    exit();
                    break;
                case 'fetch':
                    $id = $_POST['id'];
                    $role = $_POST['role'];
                    $this->controller->fetch($id, $role);
                    exit();
                    break;
                case 'jadwal':
                    $this->controller->jadwal();
                    exit();
                    break;
                case 'fetchName':
                    $this->controller->getName();
                    exit();
                    break;
                default:
                    echo json_encode(["message" => "invalid_action"]);
                    break;
            }
        }
    }
}
