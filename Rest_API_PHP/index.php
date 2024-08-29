<?php

require_once __DIR__ . '/api_core/Router.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');// Permite todas as origens
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');// Permite métodos HTTP
header('Access-Control-Allow-Headers: Content-Type');// Permite cabeçalhos

// Instancia a classe Router
$router = new Router();

// Chama o método para processar a rota
$router->handleRequest();