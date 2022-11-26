<?php namespace App\Controllers;

use App\Config\ResponseHttp;
use App\Config\Security;
use App\Models\UserModel;

class UserController
{
  private static $validate_rol = '/^[1-3]{1,1}+$/';
  private static $validate_number = '/^[0-9]+$/';
  private static $validate_text = '/^[a-zA-Z\s]+$/';

  public function __construct(
    private string $method,
    private string $routes,
    private array $params,
    private $headers,
    private $data
  ){}

  final public function getLogin(string $endPoint)
  {
    $email = strtolower($this->params[1]);
    $pass = $this->params[2];
    if (empty($email) || empty($pass)){
      return ResponseHttp::status400('Todos los campos son requeridos');
    } elseif ($this->method == 'get' && $this->routes == $endPoint){
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        return ResponseHttp::status400('Mail con formato inválido');
      } else {
        UserModel::setEmail($email);
        UserModel::setPassword($pass);
        return UserModel::loginUser();
      }
    } else {
      return ResponseHttp::status400();
    }
  }

  final public function getAllUser(string $endPoint)
  {
    if ($this->method == 'get' && $this->routes == $endPoint){
      Security::checkToken(Security::getSecretKey()); //Validamos el JWT antes de realizar la operación.
      return UserModel::allUser();
    }
  }

  final public function getUser(string $endPoint)
  {
    if ($this->method == 'get' && $this->routes == $endPoint){
      Security::checkToken(Security::getSecretKey()); //Validamos el JWT antes de realizar la operación.
      $dni = $this->params[1];
      if (!isset($dni)){
        return ResponseHttp::status400('El DNI es requerido');
      } elseif (!preg_match(self::$validate_number, $dni)) {
        return ResponseHttp::status400('El DNI debe contener solo números');
      } else {
        UserModel::setDni($dni);
        return UserModel::thisUser();
      }
    }
  }

  final public function postNewUser(string $endPoint)
  {
    if ($this->method == 'post' && $this->routes == $endPoint){
      Security::checkToken(Security::getSecretKey()); //Validamos el JWT antes de realizar la operación.
      if (empty($this->data['name']) || empty($this->data['dni']) || empty($this->data['email']) || 
          empty($this->data['rol']) || empty($this->data['password']) || empty($this->data['confirmPassword'])){
        return ResponseHttp::status400('Todos los campos son requeridos');
      } elseif (!preg_match(self::$validate_text, $this->data['name'])) {
        return ResponseHttp::status400('El campo NOMBRE admite solo texto');
      } elseif (!preg_match(self::$validate_number, $this->data['dni'])) {
        return ResponseHttp::status400('El campo DNI admite solo números');
      } elseif (!filter_var($this->data['email'], FILTER_VALIDATE_EMAIL)) {
        return ResponseHttp::status400('Mail con formato inválido');
      } elseif (!preg_match(self::$validate_rol, $this->data['rol'])) {
        return ResponseHttp::status400('Rol inválido');
      } elseif (strlen($this->data['password']) < 8 || strlen($this->data['confirmPassword']) < 8) {
        return ResponseHttp::status400('La contraseña debe tener al menos 8 caracteres');
      } elseif ($this->data['password'] != $this->data['confirmPassword']) {
        return ResponseHttp::status400('Las contraseñas no coinciden');
      } else {
        return UserModel::saveUser();
      }
    } else {
      return ResponseHttp::status400();
    }
  }

  final public function patchNewPassword(string $endPoint)
  {
    if ($this->method == 'patch' && $this->routes == $endPoint){
      Security::checkToken(Security::getSecretKey()); //Validamos el JWT antes de realizar la operación.
      
      $jwtUserData = Security::getDataJwt(); //Datos del JWT decodificados

      if (empty($this->data['oldPassword']) || empty($this->data['newPassword']) || empty($this->data['confirmNewPassword'])) {
        return ResponseHttp::status400('Todos los campos son requeridos');
      } else if (!UserModel::validateUserPassword($jwtUserData['IDToken'], $this->data['oldPassword'])) {
        return ResponseHttp::status400('La contraseña antigua no coincide');
      } else if (strlen($this->data['newPassword']) < 8 || strlen($this->data['confirmNewPassword']) < 8 ) {
        return ResponseHttp::status400('La contraseña debe tener un minimo de 8 caracteres');
      }else if ($this->data['newPassword'] !== $this->data['confirmNewPassword']){
        return ResponseHttp::status400('Las contraseñas no coinciden');
      } else {
        UserModel::setIDToken($jwtUserData['IDToken']);
        UserModel::setPassword($this->data['newPassword']); 
        return UserModel::patchPassword();
      } 
    }
  }

  //Eliminar usuario
  final public function deleteUser(string $endPoint)
  {
    if ($this->method == 'delete' && $this->routes == $endPoint){
      Security::checkToken(Security::getsecretKey());

      if (empty($this->data['IDToken'])) {
          return ResponseHttp::status400('Todos los campos son requeridos');
      } else {
          UserModel::setIDToken($this->data['IDToken']);
          return UserModel::deleteUser();
      }
    }
  }

}