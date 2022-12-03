# API RESTful

https://github.com/hnelson9402/API_REST_PHP_8
https://www.youtube.com/watch?v=OFBOx8WWXJI&list=PLwJmX01ylPH23xuqRmYtgVCWDd2rr_V5P
https://www.youtube.com/watch?v=DjAS77VK_uo&list=PLwJmX01ylPH23xuqRmYtgVCWDd2rr_V5P&index=12

## Estructura

 public
  |--.htaccess
  |--index.php
 src
  |--Config
      |--ErrorLog.php
      |--ResponseHttp.php
      |--Security.php
  |--Controllers
      |--UserController.php
  |--DB
      |--ConectionDB.php
      |--DataDB.php
      |--Sql.php
  |--Logs
      |--php-error.log
  |--Models
      |--UserModel.php
  |--Routes
      |--auth.php
      |--user.php

public: contiene los archivos públicos
src: contiene la lógica de la aplicación

## Control de acceso HTTP (CORS)
El Intercambio de Recursos de Origen Cruzado (CORS) es un mecanismo que utiliza cabeceras HTTP adicionales para permitir que un user agent (en-US) obtenga permiso para acceder a recursos seleccionados desde un servidor, en un origen distinto (dominio) al que pertenece. Un agente crea una petición HTTP de origen cruzado cuando solicita un recurso desde un dominio distinto, un protocolo o un puerto diferente al del documento que lo generó.
API Fetch siguen la política de mismo-origen, esto significa que una aplicación que consume la API sólo puede hacer solicitudes HTTP desde su propio dominio, a menos que se utilicen cabeceras CORS.

En CORS, se envía una solicitud de verificación previa con el método OPTIONS para que el servidor pueda responder si es aceptable enviar la solicitud.

## Librerias

- Dotenv (vlucas/phpdotenv)
  https://github.com/vlucas/phpdotenv
  comando: composer require vlucas/phpdotenv

- Firebase (firebase/php-jwt)
  https://github.com/firebase/php-jwt
  comando: composer require firebase/php-jwt

## API

Estructura de tabla para la tabla `usuario`

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nombre` varchar(20) NOT NULL,
  `dni` varchar(50) NOT NULL UNIQUE KEY,
  `correo` varchar(30) NOT NULL UNIQUE KEY,
  `rol` int(11) NOT NULL,
  `password` varchar(500) NOT NULL,
  `IDToken` varchar(500) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

## USO DE LA API

### Login de usuario (GET)

método: GET
endpoint: api/public/auth/
parametros: email/password/
body: no requerido.
autorización: no requerido.
controles: se verifica formato de mail y que ambos parametros existan.
repuestas: un string con la representación JSON.

Ejemplo de uso: localhost/api/public/auth/algo@ejemplo.com/12345678/

Loguin exitoso

{
  "status": "ok",
  "message": {
    "name": "Nombre Apellido",
    "rol": 2,
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2Njk0NjYyODksImV4cCI6MTY2OTQ4Nzg4OSwiZGF0YSI6eyJJRFRva2VuIjoiZTliNDU1NzIxODJmZDRlN2U2YzliZDA2YTQ3NDMxNTA5MzdlYmFkY2ZlNDIwMjI0NGFhMjUzOGYyNjI3Y2MxYjk0MDBlN2E4NzFiYjZhOGYzYmNmNTU3OTdiMGE0NmU1OWQ5ZTI2NWFmNWI0ZjU3NTQ3MGJkMGI4YzU5YWIyNzIifX0.CWciLUQ_mIqU-29URVQcSCOmRa_BoR0sfo2gBqWV1eg"
  }
}

En caso de no coincidencia

{
  "status":"error",
  "message":"Usuario o Contraseña invalidos"
}

### Obtener todos los usuarios (GET)

método: GET
endpoint: api/public/user/
parametros: no requeridos.
body: no requerido.
autorización: requerido (Bearer token).
controles: se verifica el token de login.
repuestas: un string con la representación JSON donde "message":[{}] es un array asociativo con todos los usuarios.

Ejemplo de uso: localhost/api/public/user/

### Obtener un usuario por su dni(GET)

método: GET
endpoint: api/public/user/
parametros: dni/ (sin puntos)
body: no requerido.
autorización: requerido (Bearer token).
controles: se verifica el token de login.
repuestas: un string con la representación JSON donde "message":[{}] es un array asociativo con el usuario.

Ejemplo de uso: localhost/api/public/user/XXXXXXXX

### Crear un usuario (POST)

método: POST
endpoint: api/public/user/
parametros: no requiere.
body: Json conteniendo: name, dni, email, rol, password y confirmPassword.
autorización: requerido (Bearer token).
controles: se verifica el token de login y los campos del body.
repuestas: un string con la representación JSON donde "message":[{}] es un array asociativo con el nuevo usuario.

Ejemplo de uso: localhost/api/public/user/

### Cambiar password (PATCH)

método: PATCH
endpoint: api/public/user/password/
parametros: no requiere.
body: Json conteniendo: oldPassword, nePassword y confirmNewPassword.
autorización: requerido (Bearer token).
controles: se verifica el token de login y los campos del body.
repuestas: un string con la representación JSON.

Ejemplo de uso: localhost/api/public/user/password/

### Eliminar usuario  (DELETE)

método: DELETE
endpoint: api/public/user/
parametros: no requiere.
body: Json conteniendo: IDToken.
autorización: requerido (Bearer token).
controles: se verifica el token de login y los campos del body.
repuestas: un string con la representación JSON.

Ejemplo de uso: localhost/api/public/user/
