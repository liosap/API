<?php namespace App\DB;

use App\Config\ResponseHttp;

class Sql extends ConnectionDB
{
  public static function isExists(string $resquet, string $condition, $param)
  {
    try {
      $con = self::getConnection();
      $query = $con->prepare($resquet);
      $query->execute([$condition=>$param]);
      $response = ($query->rowCount() == 0) ? false : true;
      return $response;
    } catch (\PDOException $e) {
      error_log('Sql::isExists -> '.$e);
      die(json_encode(ResponseHttp::status400()));
    }
  }
}