## GIT
Es un sistema de control de versiones, o VCS, rastrea el historial de cambios a medida que las personas y los equipos colaboran en proyectos juntos. A medida que los desarrolladores realizan cambios en el proyecto, cualquier versión anterior del proyecto se puede recuperar en cualquier momento.

### Configuración de su email y nombre de usuario de Git
GitHub requiere su dirección de correo electrónico para asociar las confirmaciones (comits) con su cuenta en GitHub.

Para todos los repositorios:
  $ git config --global user.name "Mona Lisa"
  $ git config --global user.name
  > Mona Lisa

  $ git config --global user.email "monalisa@ejemplo.com"
  $ git config --global user.email
  > monalisa@ejemplo.com

Para un solo repositorio:
  $ git config user.name "Mona Lisa"
  $ git config user.name
  > Mona Lisa

  $ git config user.email "monalisa@ejemplo.com"
  $ git config user.email
  > monalisa@ejemplo.com

### Comandos básicos de Git
git init --> inicializa un nuevo repositorio de Git y comienza a rastrear un directorio existente. Agrega una subcarpeta oculta dentro del directorio existente que alberga la estructura interna de datos necesaria para el control de versiones.

git add --> agrega un cambio. Le dice a Git que desea incluir actualizaciones de un archivo en particular en la próxima confirmación. Sin embargo, git add en realidad no afecta al repositorio de manera significativa. Los cambios no se registran hasta que ejecuta git commit.

git commit --> guarda la instantánea en el historial del proyecto y completa el proceso de seguimiento de cambios. En resumen, una confirmación funciona como tomar una foto. Todo lo que se haya preparado git addse convertirá en parte de la instantánea con git commit.

git reset --> se usa para deshacer un comit o una instantánea preparada.

git status --> muestra el estado de los cambios como sin seguimiento, modificado o preparado.

git branch --> muestra las ramas que se están trabajando localmente.

git merge --> fusiona líneas de desarrollo juntas. Este comando generalmente se usa para combinar cambios realizados en dos ramas distintas. Por ejemplo, un desarrollador se fusionaría cuando quisiera combinar los cambios de una rama de características en la rama principal para la implementación.

git clone --> crea una copia local de un proyecto existente de forma remota. El clon incluye todos los archivos, el historial y las ramas del proyecto.

git-push --> (local al remoto) actualiza las referencias remotas junto con los objetos asociados. se usa para transferir o enviar el comit, que se realiza en una rama local a un repositorio remoto como GitHub.

git pull --> (remoto al local) actualiza la línea de desarrollo local con actualizaciones de su contraparte remota.

### Cómo funciona
Los comandos git add y git commit componen el flujo de trabajo fundamental de Git.
Cuando todo esté listo para guardar una copia del estado actual del proyecto, realice los cambios con git add. Una vez que esté satisfecho con la instantánea preparada, la consigna en el historial del proyecto con git commit.
Un tercer comando git push es esencial para un flujo de trabajo Git colaborativo, se utiliza para enviar los cambios comprometidos a repositorios remotos. Esto permite que otros miembros del equipo accedan a un conjunto de cambios guardados.

### Procedimiento básico
1- Una vez Git está instalado y configurado, desde la terminal y en la carpeta del proyecto, creamos el repositorio  local con el comando: git init.
2 - Cuando nuestros archivos esten listos para ser actualizados y llevar el control de los cambios, con el comando git add, agregamos el archivo o todos los archivos al "staging area". Por ej.: git add --all (todos) o git add *.php (todos con la extensión php) o git add index.php (solo ese).
3 - Para confirmar los cambios y crear nuestra instantánea en el historial del proyecto ejecutamos git commit -m 'mi primer commit'.
4 - Conexión con el repositorio remoto (GitHub): git remote add origin https://github.com/aqui-tu-repo.git
5 - Enviar contenido local a GitHub: git push -u origin master

### Este caso en particular

Crea un nuevo repositorio desde la línea de comando
git init
git add --all
git commit -m "first commit"
git branch -M main
git remote add origin https://github.com/liosap/api.git
git push -u origin main

Para subir un repositorio existente desde la línea de comando
git remote add origin https://github.com/liosap/api.git
git branch -M main
git push -u origin main

Para descarga desde un repositorio remoto y actualizar el repo local
git pull https://github.com/liosap/api.git

https://www.datacamp.com/tutorial/git-push-pull
https://www.atlassian.com/git/tutorials/learn-git-with-bitbucket-cloud


## GITHUB
Es una plataforma en la nube que utiliza Git como su tecnología central. GitHub actúa como el "repositorio remoto"

https://learn.microsoft.com/en-us/training/browse/?products=github&levels=beginner

Issues (Problemas): ocurre la mayor parte de la comunicación entre los consumidores de un proyecto y el equipo de desarrollo para discutir un amplio conjunto de temas, incluidos informes de errores, solicitudes de funciones, aclaraciones de documentación y más.

Notifications (Notificaciones): para prácticamente todos los eventos que tienen lugar dentro de un flujo de trabajo.

Branches (Ramas): son la forma preferida de crear cambios en el flujo de trabajo en GitHub. Permite trabajar simultaneamente en nueva función o para corregir un error, sin importar cuán grande o pequeño sea, luego esta rama se puede fusionar a la rama principal (main) mediante un pull request.

Commits (Confirmaciones): es un cambio en uno o más archivos en una rama. Cada vez que se crea una conmit, se le asigna una ID única y se realiza un seguimiento, junto con la hora y el colaborador. Esto proporciona un seguimiento de auditoría claro para cualquier persona que revise el historial de un archivo o elemento vinculado, como un problema o una solicitud de pull request (extracción).

Pull Request (Extracción): es el mecanismo utilizado para señalar que las confirmaciones de una rama están listas para fusionarse en otra rama. Una vez que se han aprobado los cambios (si se requiere aprobación), la rama de origen de la solicitud de extracción (la rama de comparación) puede fusionarse con la rama base.

Labels (Etiquetas): Las etiquetas proporcionan una forma de categorizar y organizar problemas (inssues) y solicitudes de extracción (pull request) en un repositorio.



https://learn.microsoft.com/en-us/training/modules/use-git-from-vs-code/
https://www.youtube.com/watch?v=n-wzos1Uc9E
https://learn.microsoft.com/en-us/training/modules/introduction-to-github-visual-studio-code/

Conectarse con VS a GitHub
Instalar la extensión GitHub Pull Requests and Issues.
Después de instalar la extensión, puede seleccionar el ícono Cuenta en la parte inferior de la barra de actividad, seleccionar Iniciar sesión para usar GitHub Pull Requests and Issues. Se abre el navegador y se le solicita que conceda permiso para que Visual Studio Code acceda a GitHub. Se vuelve a abrir la ventana de Visual Studio Code, ha iniciado sesión.
Puede volver a verificar seleccionando el ícono de la cuenta nuevamente y viendo su nombre de usuario de GitHub.

Crear y clonar un repositorio

Agregar contenido
Ver la linea de tienpo en el repositorio de GitHub
Ramas y pull resquets (solicitudes de extraccción)