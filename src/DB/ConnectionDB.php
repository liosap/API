<?php namespace App\DB;

use App\Config\ResponseHttp;

require __DIR__ . '/dataDB.php';

class ConnectionDB {

  private static $host = '';
  private static $user = '';
  private static $password = '';

  final public static function from($host, $user, $password)
  {
    self::$host     = $host;
    self::$user     = $user;
    self::$password = $password;
  }

  final public static function getConnection()
  {
    try {

      $opt = [\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC];      //Opciones de la conexión (array asociativo)
      $dsn = new \PDO(self::$host, self::$user, self::$password, $opt); //La conexión
      $dsn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);  //Atributos de la conexión
      return $dsn;

    } catch (\PDOException $e) {

      error_log('Error de conexion :' . $e);
      die(json_encode(ResponseHttp::status500()));

    }
  }

}