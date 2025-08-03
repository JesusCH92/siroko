# APP
Stack tecnológico: PHP8.3(Symfony7), Swagger, Nginx, MySQL y Docker.

## Estructura de carpetas
El proyecto sigue una **arquitectura de cortes verticales**, donde cada módulo o contexto del dominio se organiza de forma independiente. Esto facilita la escalabilidad y el mantenimiento del código, asegurando que cada módulo sea autónomo y encapsule su propia lógica.

Cada módulo está dividido en tres capas principales:

- **Infrastructure**: Maneja las dependencias externas, como bases de datos, bibliotecas externas o del propio framework, APIs externas, punto de entrada y salida(Comando, APIs, ControladorWeb), o cualquier detalle técnico.
- **ApplicationService**: Contiene los casos de uso que coordinan la lógica de aplicación y se encargan de interactuar con el dominio.
- **Domain**: Encierra las reglas de negocio y las entidades que representan el núcleo del sistema.

```plaintext
  src/Cart
  ├── ApplicationService
  ├── Domain
  └── Infrastructure
  src/Order
  ├── ApplicationService
  ├── Domain
  └── Infrastructure
  src/Product
  ├── ApplicationService
  ├── Domain
  └── Infrastructure
  src/User
  ├── ApplicationService
  ├── Domain
  └── Infrastructure
  src/Common
  ├── Domain
  └── Infrastructure
```

## Bibliotecas
-  `nelmio/api-doc-bundle` para documentar las API's.
-  `lexik/jwt-authentication-bundle` para la autentificación con JWT
-  `doctrine/doctrine-bundle` para modelar la base de datos con nuestro dominio.
-  `phpunit/phpunit` para testear.

## Requisitos previos
> [!IMPORTANT]
> **Debes tener instalado Docker y Docker Compose en tu equipo.**

> [!WARNING]
> **Estamos usando el puerto 8080 para el servidor local, y tiene que estar disponible para levantar nuestro servicio de nginx.**
>
> **Estamos usando el puerto 3306 de mysql y debe estar disponible, si tiene un servidor en mysql encendido apaguelo para levantar la app.**

## Desplegar en: MAC, Linux, WSL2

- [ ] Debes ejecutar:

```shell
  make deploy
```

## Desplegar en: Windows

- [ ] Instalar la network de los contenedores en caso de no tenerla instalada antes:

```shell
  docker network create app-network
```

- [ ] Levantar los contenedores:

```shell
  docker-compose -p app up -d
```

- [ ] Modo interactivo: acceso al contenedor de PHP(php-fpm):

```shell
  docker exec -it php-fpm bash 
```

- [ ] Después de entrar al contenedor de PHP(php-fpm), ver el paso anterior, dentro del contenedor ejecutar:

```shell
  composer install
  php bin/console doctrine:migrations:migrate --no-interaction
  php bin/console lexik:jwt:generate-keypair
```

- [ ] Opcional, desde el contenedor de PHP(php-fpm), se puede ejecutar los test:

```shell
  php bin/phpunit --testdox
```

## Acceso al sistema

- [ ] Credenciales:
```JSON
{
    "email": "admin",
    "password": "admin"
}
```
📄 [Ver fichero de ejemplo `Login.http`](./src/User/Infrastructure/Http/Login.http)

- [ ] Documentación de los API's usadas en el proyecto:

[**`documentación de las API's`**](http://localhost:8080/api/doc)

- [ ] Documentación de los API's usadas en formato json:

[**`documentación de las API's JSON`**](http://localhost:8080/api/doc.json)

