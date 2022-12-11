# Virtual Host
Apache facilita tener múltiples sitios web o aplicaciones web ejecutándose en el mismo servidor físico y acceder a cada uno usando un nombre de dominio personalizado. Esto se conoce como alojamiento virtual y cada host virtual se puede asignar a un subdirectorio diferente del servidor.

## 1. Configurar El archivo host de windows

Asignar nombres de host a direcciones ip.

Pasos:
1. Ejecutar el bloc de notas como **administrador**
2. Editamos el archivo **host** ubicado en: C:\Windows\System32\drivers\etc\hosts  
Si no aparece ningún archivo entonces cambiamos la opción del combobox Documentos de texto (`*.txt`) por Todos los archivos (`*.*`).
3. Al final del archivo agregamos nuestro host asi:
```
127.0.0.1       localhost
127.0.0.1       misitio.test
```
4. Guardamos el archivo.

## 2. Configurar Host Virtual en XAMPP

Editamos el archivo de configuración de apache.

Pasos:
1. En el panel de control de xampp.
2. Click en el botón EXPLORER.
3. Abrimos la ruta "C:\xampp\apache\conf\extra"
4. Editamos el archivo **"httpd-vhosts.conf"** con la siguiente información al final de todo el contenido:
```
<VirtualHost *:80>
  ServerName localhost
  DocumentRoot "C:/xampp/htdocs"
</VirtualHost>

<VirtualHost *:80>
  ServerName misitio.test
  DocumentRoot "C:/xampp/htdocs/misitio"
</VirtualHost>
```

## 3. Reiniciar el servidor Apache
Para que tengan efecto los cambios realizados.

Pasos:
1. En el panel de control de xampp.
2. Click en el botón STOP del módulo de apache.
3. Click en el botón START del módulo de apache.