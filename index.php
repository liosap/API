<?php

use App\Config\ErrorLog;
use App\Config\ResponseHttp;

require_once './vendor/autoload.php';

//$_SERVER['HTTP_ORIGIN'] existe cuando realizamos la petición desde un dominio válido
if (isset($_SERVER['HTTP_REFERER'])){
  ResponseHttp::headerHttpPro($_SERVER['REQUEST_METHOD'], $_SERVER['HTTP_ORIGIN']);//CORS Producción
} else {
  ResponseHttp::headerHttpDev($_SERVER['REQUEST_METHOD']);//CORS Desarrollo
}

//Activa el registro de errores
ErrorLog::activateErrorLog();

if (isset($_GET['route']) && $_GET['route'] != "index.php") {

  $name = explode('/', $_GET['route']);
  $file = './src/Routes/'.$name[0].'.php';
  $list = ['auth','user'];

  if (is_readable($file) && in_array($name[0],$list)){
    require_once $file;
  } else {
    $msg = "La ruta '".$name[0]."' no existe.";
    echo json_encode(ResponseHttp::status400($msg));
    error_log($msg);//Escribe el archivo php-error.log
  }

} else {
  echo json_encode(ResponseHttp::status404());
}