<?php
require_once __DIR__ . '/models/database.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/views/Response.php';

class Router 
{
    public function handleRequest()
    {
        // Obter o método de requisição e o URI
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        // Processar a URI para obter o recurso e o ID
        $parts = explode('/', trim($uri, '/'));
        $resource = $parts[1] ?? null;  // Considerando que "v1" é o 2º segmento
        $id = $parts[2] ?? null;        // Considerando que o ID (se houver) é o 3º segmento

        // Roteamento com base no método HTTP e recurso
        switch ($method) {
            case 'GET':
                if ($resource === 'user' && $id) {
                    $this->getUser($id);
                } elseif ($resource === 'user') {
                    $this->getUsers();  
                } else {
                    //$this->sendError(400, 'Invalid endpoint');
                    echo Response::transformar_json(400, 'Invalid endpoint', null);
                }
                break;

            case 'POST':
                if ($resource === 'user') {
                    $this->createUser();
                    //$this->updateTabelaUser();
                } else {
                    echo Response::transformar_json(400, 'Invalid endpoint', null);
                }
                break;

            case 'PUT':
                if ($resource === 'user') {
                    $this->updateUser();
                    //$this->updateTabelaUser();
                } else {
                    echo Response::transformar_json(400, 'Invalid endpoint', null);
                }
                break;

            case 'DELETE':
                if ($resource === 'user' && $id) {
                    $this->deleteUser($id);
                    //$this->updateTabelaUser();
                } else {
                    echo Response::transformar_json(400, 'Invalid endpoint', null);
                }
                break;

            default:
                echo Response::transformar_json(400, 'Invalid endpoint', null);
                break;
        }
    }

    private function getUser($id)
    {
        require_once __DIR__ . '/../api_core/controllers/Controllers.php';
        Controllers::getContactById($id);
        
    }
    
    private function getUsers()
    {
        require_once __DIR__ . '/../api_core/controllers/Controllers.php';
        Controllers::getAllCotacts();
    } 

    private function createUser()
    {
        require_once __DIR__ . '/../api_core/controllers/Controllers.php';
        Controllers::createContact();
        
    }
    
    private function updateUser()
    {
        require_once __DIR__ . '/../api_core/controllers/Controllers.php';
        Controllers::updateContacts();
        
    }
    

    private function deleteUser($id)
    {
        require_once __DIR__ . '/../api_core/controllers/Controllers.php';
        Controllers::delContactById($id);
    }

    private function updateTabelaUser(){
        require_once __DIR__ . '/../api_core/controllers/Controllers.php';
        Controllers::updateTables();
    }


}

// Instanciar e chamar o roteador
//$router = new Router();
//$router->handleRequest();

