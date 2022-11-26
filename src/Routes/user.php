<?php

use App\Config\ResponseHttp;
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

// Rutas
try {
  switch ($method) {
    case 'get':
      if ($params[1]){
        $res = $app->getUser("user/${params[1]}/"); //Mostrar un usuario por su dni con el metodo GET
      } else {
        $res = $app->getAllUser('user/'); //Mostrar todos los usuarios con el metodo GET
      }
      echo ($res != null) ? json_encode($res) : json_encode(ResponseHttp::status400());
      break;
    case 'post':
      $res = $app->postNewUser('user/'); //Crear un usuario con el metodo POST
      echo ($res != null) ? json_encode($res) : json_encode(ResponseHttp::status400());
      break;
    case 'patch':
      $res = $app->patchNewPassword('user/password/');
      echo ($res != null) ? json_encode($res) : json_encode(ResponseHttp::status400());
      break;
      case 'delete':
        $res = $app->deleteUser('user/');
        echo ($res != null) ? json_encode($res) : json_encode(ResponseHttp::status400());
        break;
      default:
      echo json_encode(ResponseHttp::status400());
  }
} catch (\Throwable $e) {
  error_log('UserModel -> ' . $e);
  die(json_encode(ResponseHttp::status400()));
}


/*
php://input: Este es un flujo de sólo lectura que nos permite leer los datos en bruto del cuerpo de la solicitud.
  Devuelve todos los datos en bruto después de las cabeceras HTTP de la petición, independientemente del tipo de
  contenido.
Función file_get_contents(): En PHP se utiliza para leer un archivo en una cadena.
Función json_decode(): Toma una cadena JSON y la convierte en una variable PHP que puede ser un array o un objeto.

Para recibir la cadena JSON podemos usar "php://input" junto con la función file_get_contents() que nos ayuda
a recibir los datos JSON como un archivo y leerlo en una cadena.
Posteriormente, podemos utilizar la función json_decode() para decodificar la cadena JSON.

$data = json_decode(file_get_contents('php//:input'), true);

*/