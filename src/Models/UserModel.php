<?php namespace App\Models;

use App\Config\ResponseHttp;
use App\Config\Security;
use App\DB\ConnectionDB;
use App\DB\Sql;

class UserModel extends ConnectionDB {

  //Propiedades de la base de datos
  private static string $nombre;
  private static string $dni;
  private static string $correo;
  private static int    $rol;    
  private static string $password;
  private static string $IDToken;

  public function __construct(array $data)
  {
    self::$nombre   = $data['name'];
    self::$dni      = $data['dni'];
    self::$correo   = $data['email'];
    self::$rol      = $data['rol'];        
    self::$password = $data['password']; 
  }

  // Metodos Getter
  final public static function getName(){return self::$nombre;}
  final public static function getDni(){return self::$dni;}
  final public static function getEmail(){return self::$correo;}
  final public static function getRol(){return self::$rol;}
  final public static function getPassword(){return self::$password;}
  final public static function getIDToken(){return self::$IDToken;}
  
  // Metodos Setter
  final public static function setName(string $nombre){self::$nombre = $nombre;}
  final public static function setDni(string $dni){self::$dni = $dni;}
  final public static function setEmail(string $correo){self::$correo = $correo;}
  final public static function setRol(string $rol){self::$rol = $rol;}
  final public static function setPassword(string $password){self::$password = $password;}
  final public static function setIDToken(string $IDToken){self::$IDToken = $IDToken;}

  // Login de usuario
  final public static function loginUser()
  {
    try{
      $con = self::getConnection();
      $query = $con->prepare("SELECT * FROM usuario WHERE correo = :correo");
      $query->execute([':correo'=>self::getEmail()]);
      if ($query->rowCount() == 0){
        return ResponseHttp::status400("Usuario o Contraseña invalidos");
      } else {
        foreach($query as $res){
          if (Security::checkPass(self::getPassword(), $res['password'])){
            $payload = ['IDToken'=>$res['IDToken']];
            $token = Security::createToken($payload, Security::getSecretKey());
            $data = [
              'name'  =>$res['nombre'],
              'rol'   =>$res['rol'],
              'token' =>$token
            ];
            return ResponseHttp::status200($data);
          } else {
            return ResponseHttp::status400("Usuario o Contraseña invalidos");
          }
        }
      }
    } catch (\PDOException $e) {
      error_log('UserModel::login -> ' . $e);
      die(json_encode(ResponseHttp::status400()));
    }
  }

  // Obtener todos los usuario
  final public static function allUser()
  {
    try {
      $con = self::getConnection();
      $query = $con->prepare("SELECT * FROM usuario");
      $query->execute([]);
      if ($query->rowCount() > 0) {
        $data = $query->fetchAll(\PDO::FETCH_ASSOC);
        return ResponseHttp::status200($data);
      } else {
        return ResponseHttp::status400('Sin usuarios');
      }
    } catch (\PDOException $e) {
        error_log('UserModel::allUser -> ' . $e);
        die(json_encode(ResponseHttp::status400()));
    }
  }

  // Obtener un usuario en particular
  final public static function thisUser()
  {
    try {
      $con = self::getConnection();
      $query = $con->prepare("SELECT * FROM usuario WHERE dni = :dni");
      $query->execute([':dni' => self::getDni()]);
      if ($query->rowCount() == 0) {
        return ResponseHttp::status400('Usuario inexistente o no está registrado');
      } else {
        $data['data'] = $query->fetchAll(\PDO::FETCH_ASSOC);
        return ResponseHttp::status200($data);
      }
    } catch (\PDOException $e) {
        error_log('UserModel::thisUser -> ' . $e);
        die(json_encode(ResponseHttp::status400()));
    }
  }

  // Registrar un nuevo usuario
  final public static function saveUser()
  {
    if (Sql::isExists("SELECT dni FROM usuario WHERE dni = :dni",":dni",self::getDni())) {  
      return ResponseHttp::status400('El DNI ya esta registrado');
    } else if (Sql::isExists("SELECT correo FROM usuario WHERE correo = :correo",":correo",self::getEmail())) {
      return ResponseHttp::status400('El Correo ya esta registrado');
    } else {
      self::setIDToken(hash('sha512',self::getDni().self::getEmail()));            
      try {
        $con = self::getConnection();
        $query1 = "INSERT INTO usuario (nombre,dni,correo,rol,password,IDToken) VALUES";
        $query2 = "(:nombre,:dni,:correo,:rol,:password,:IDToken)";
        $query = $con->prepare($query1 . $query2);
        $query->execute([
            ':nombre'  => self::getName(),
            ':dni'     => self::getDni(),
            ':correo'  => self::getEmail(),
            ':rol'     => self::getRol(),                    
            ':password'=> Security::encryptPass(self::getPassword()),
            ':IDToken' => self::getIDToken()            
        ]);
        if ($query->rowCount() > 0) {
            return ResponseHttp::status200('Usuario registrado exitosamente');
        } else {
            return ResponseHttp::status400('No se puedo registrar el usuario');
        }
      } catch (\PDOException $e) {
          error_log('UserModel::saveUser -> ' . $e);
          die(json_encode(ResponseHttp::status400()));
      }
    }
  }

  // Validar si la contaseña es correcta
  final public static function validateUserPassword(string $IDToken,string $oldPassword)
  {
      try {
          $con = self::getConnection();
          $query = $con->prepare("SELECT password FROM usuario WHERE IDToken = :IDToken");
          $query->execute([
              ':IDToken' => $IDToken
          ]);

          if ($query->rowCount() == 0) {
              die(json_encode(ResponseHttp::status500()));
          } else {
              $res = $query->fetch(\PDO::FETCH_ASSOC);

              if (Security::checkPass($oldPassword, $res['password'])){
                  return true;
              } else {
                  return false;
              }               
          }                     
      } catch (\PDOException $e) {
          error_log('UserModel::validateUserPassword -> ' . $e);            
          die(json_encode(ResponseHttp::status400()));
      }
  }

  //Actualizar la contraseña de usuario
  final public static function patchPassword()
  {
      try {
          $con = self::getConnection();
          $query = $con->prepare("UPDATE usuario SET password = :password WHERE IDToken = :IDToken");           
          $query->execute([
              ':password' => Security::encryptPass(self::getPassword()),
              ':IDToken'  => self::getIDToken()
          ]);
          if ($query->rowCount() > 0) {
          return ResponseHttp::status200('Contraseña actualizado exitosamente');
          } else {
          return ResponseHttp::status400('Error al actualizar la contraseña del usuario');
          }
      } catch (\PDOException $e) {
          error_log("UserModel::patchPassword -> " . $e);
          die(json_encode(ResponseHttp::status400()));
      }
  }

  //Eliminar un usuario
  final public static function deleteUser()
  {
      try {
          $con   = self::getConnection();
          $query = $con->prepare("DELETE FROM usuario WHERE IDToken = :IDToken");
          $query->execute([
              ':IDToken' => self::getIDToken()
          ]);

          if ($query->rowCount() > 0) {
              return ResponseHttp::status200('Usuario eliminado exitosamente');
          } else {
              return ResponseHttp::status500('No se puede eliminar el usuario');
          }
      } catch (\PDOException $e) {
          error_log("UserModel::deleteUser -> " . $e);
          die(json_encode(ResponseHttp::status500('No se puede eliminar el usuario')));
      }
  }
}