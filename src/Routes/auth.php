<?php

use App\Controllers\UserController;

$method = strtolower($_SERVER['REQUEST_METHOD']); // Obtenemos el método y lo convertimos en minúsculas.
$routes = $_GET['route']; // Obtenemos la ruta
if (substr($routes, -1) != "/"){ $routes = $_GET['route'].'/'; } // Si la ruta no contiene "/" al final, se lo agregamos.
$params = explode('/', $routes);
$headers = getallheaders(); // Esta función devuelve un array asociativo de todas las cabeceras HTTP de la petición actual.
$json = file_get_contents('php://input');  // Toma los datos sin procesar de la solicitud.
$data = json_decode($json, true); // Lo convierte en un objeto PHP al tener la opción true en un array asociativo.

// Instaciamos el controlador de USUARIO
$app = new UserController($method, $routes, $params, $headers, $data);

echo json_encode($app->getLogin("auth/{$params[1]}/{$params[2]}/"));


/*
use App\Config\Security;
use App\DB\ConnectionDB;

ConnectionDB::getConnection();

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = Security::getSecretKey();
$data = ['Lionel Saporito', 'Administrador'];
$token = Security::createToken($data, $key);
$decoded = JWT::decode($token, new Key($key, 'HS256'));
echo json_encode($decoded);
*/