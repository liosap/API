<?php namespace App\Config;

use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Security {

  private static $jwt_data; //Propiedad para guardar los datos decodificados del JWT

  //Obtener la 'SECRET_KEY' del archivo .env
  final public static function getSecretKey()
  {
    $dotenv = Dotenv::createImmutable(dirname(__DIR__,2));
    $dotenv->load();
    return $_ENV['SECRET_KEY'];    
  }

  //Encriptar la contraseña del usuario
  final public static function encryptPass(string $pwd)
  {
    $encryptPass = password_hash($pwd, PASSWORD_DEFAULT);
    return $encryptPass;
  }

  //Verificar que la contraseña sea correcta
  final public static function checkPass(string $pwd, string $pwdHash)
  {
    if (password_verify($pwd, $pwdHash)){
      return true;
    } else {
      return false;
      error_log('Clave incorrecta');
    }
  }

  //Crear JWT con el algoritmo 'HS256'
  final public static function createToken(array $data, string $key)
  {
    $payload = array (
      "iat" => time(),                  //Tiempo de emición en segundos
      "exp" => time() + (60*60*6),      //Tiempo de expiración en segundos. En este caso (60*60*6) son seis horas
      "data" => $data                   //Datos a encriptar
    );
    $jwt = JWT::encode($payload, $key, 'HS256');
    return $jwt;
  }

  //Validar que el JWT sea correcto
  final public static function checkToken(string $key)
  {
    if (!isset(getallheaders()['Authorization'])) {
      die(json_encode(ResponseHttp::status400('El token de acceso en requerido')));
    }
    try {
        $jwt = explode(" ", getallheaders()['Authorization']);
        //$data = JWT::decode($jwt[1], $key, array('HS256'));
        $data = JWT::decode($jwt[1], new Key($key, 'HS256'));
        self::$jwt_data = $data;
        return $data;
    } catch (\Exception $e) {
        error_log('Token invalido o expirado '. $e);
        die(json_encode(ResponseHttp::status401('Token invalido o expirado')));
    }
  }

  //Devolver los datos del JWT decodificados
  final public static function getDataJwt()
  {
      $jwt_decoded_array = json_decode(json_encode(self::$jwt_data), true);
      return $jwt_decoded_array['data'];
  }
}