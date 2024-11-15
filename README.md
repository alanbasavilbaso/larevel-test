## Rutas de API

### Autenticación
1. `/api/v1/login`: Inicia sesión y devuelve un token de autenticación.

### Endpoints de GIFs
**Nota:** Requiere token de autenticación.
- `GET /api/v1/gifs/search`: Busca GIFs.
- `GET /api/v1/gifs/{id}`: Muestra detalles de un GIF por ID.
- `POST /api/v1/gifs/favorite`: Guarda un GIF como favorito.

### Errores de Acceso
- Si intentas acceder sin autenticación, recibirás un error `401 Unauthorized`.
- Accesos no válidos desde el navegador pueden resultar en un error `403 Forbidden`.

## Deploy - steps

Este proyecto usa Docker para el entorno de desarrollo de Laravel. Sigue estos pasos para configurarlo en tu máquina local.

## Requisitos

- Docker y Docker Compose instalados.


1. Clona este repositorio:

    ```bash
    git clone git@github.com:alanbasavilbaso/larevel-test.git
    ```

2. Crea el archivo `.env` en la raíz del proyecto, copiando el contenido del archivo `.env.example`.

3. Construye y ejecuta los contenedores de Docker en segundo plano:

    ```bash
    docker-compose up -d
    ```

4. Usar collección de postman (incluido debajo)

5. Correr Test
    ```bash
    docker exec -it laravel-app bash

    php artisan test
    ```


## Apagar los contenedores

Para detener los contenedores, ejecuta:

```bash
docker-compose down
```

## usuario de test
```
php artisan db:seed --class=UserSeeder
php artisan passport:client --personal
```

## Diagrama de Casos de Uso

![Buscar Gif por palabra](https://github.com/alanbasavilbaso/larevel-test/blob/main/cu.jpg)
```
1. Login de Usuario
    Actor: Usuario
    Caso de Uso: El usuario proporciona sus credenciales para autenticarse en el sistema y obtener un token de acceso.
    Precondición: 
        - El usuario debe tener un email válido.
        - La contraseña debe ser válida (al menos 8 caracteres).
        - El usuario debe tener el acceso habilitado en el sistema.
    Secuencia normal:
        Flujo:
            1) El usuario envía su email y contraseña.
            2) El sistema valida las credenciales.
            3) Si las credenciales son válidas, el sistema devuelve un token de autenticación.
            4) El usuario recibe el token junto con el tiempo de expiración (30 minutos).
    Excepciones
        Flujo:
            2) Si el nombre de email/contraseña no son correctos, el sistema devuelve "Invalid credentials" con un código de error 422.
            2) Si los parámetros email/contraseña no son válidos (por ejemplo, el email tiene un formato incorrecto o la contraseña es demasiado corta), el sistema devuelve "Validation failed" con código de error 422.
            3) En caso de error interno, el sistema devuelve "Unexpected error occurred" con código de error 500.

2. Buscar GIFs
    Actor: Usuario
    Caso de Uso: El usuario busca GIFs en el sistema utilizando un término de búsqueda, y el sistema devuelve los GIFs correspondientes.

    Precondición:
        - El usuario debe haber iniciado sesión y debe disponer de un token de autenticación válido.
        - El parámetro de búsqueda debe ser un término de búsqueda válido (por ejemplo, una cadena de texto).

    Secuencia normal:
        Flujo:
            1) El usuario envía una solicitud con el parámetro de búsqueda (por ejemplo, "funny").
            2) El sistema valida que el término de búsqueda no esté vacío y que sea un texto válido.
            3) El sistema consulta la API de GIFs (Giphy) para obtener los resultados de la búsqueda.
            4) El sistema recibe los resultados de la búsqueda y los presenta al usuario.
            5) El usuario recibe la lista de GIFs.
    Excepciones:
        Flujo:
            1) Si el término de búsqueda es inválido (vacío o no alfanumérico), el sistema devuelve "Validation failed" con código 422.
            2) Si ocurre un error al consultar la API de GIFs (por ejemplo, si la API no responde), el sistema devuelve "Service error" con código 500.

3. Buscar GIF por ID
    Actor: Usuario
    Caso de Uso: El usuario busca un GIF específico mediante su ID, y el sistema devuelve los detalles del GIF correspondiente.

    Precondición:
        - El usuario debe haber iniciado sesión y debe disponer de un token de autenticación válido.
        - El ID del GIF debe ser un identificador válido y existir en la API.

    Secuencia normal:
        Flujo:
            1) El usuario envía una solicitud con el ID del GIF.
            2) El sistema valida que el ID sea un identificador válido.
            3) El sistema consulta la API de GIFs para obtener los detalles del GIF con el ID proporcionado.
            4) El sistema recibe los detalles del GIF y los presenta al usuario.
            5) El usuario recibe la información del GIF solicitado.
    Excepciones:
        Flujo:
            1) Si el ID del GIF es inválido o no se encuentra en la base de datos/ API, el sistema devuelve "GIF not found" con código 404.
            2) Si ocurre un error al consultar la API de GIFs, el sistema devuelve "Service error" con código 500.

4. Guardar GIF como Favorito
    Actor: Usuario
    Caso de Uso: El usuario guarda un GIF como favorito en el sistema.

    Precondición:
        - El usuario debe haber iniciado sesión y debe disponer de un token de autenticación válido.
        - El GIF debe existir en el sistema (verificación del ID del GIF).
    Secuencia normal:
        Flujo:
            1) El usuario envía una solicitud para guardar un GIF como favorito, proporcionando el ID del GIF y un alias opcional.
            2) El sistema valida que el ID del GIF sea válido y que el usuario esté autenticado.
            3) El sistema guarda el GIF como favorito para el usuario, asociándolo con el ID del GIF y el alias proporcionado (si aplica).
            4) El sistema confirma que el GIF ha sido guardado correctamente y devuelve un mensaje de éxito.
            5) El usuario recibe una confirmación de que el GIF fue guardado correctamente.
    Excepciones:
        Flujo:
            1) Si el ID del GIF es inválido o el GIF no se encuentra, el sistema devuelve error = "Error saving favorite GIF", y details = "GIF not found or API error" con código 500.
            2) Si hay un problema al guardar el GIF (por ejemplo, problema con la base de datos), el sistema devuelve "Error saving favorite GIF" con código 500.
            3) Si la validación de los parámetros falla (por ejemplo, si el alias/gif_id no es válido), el sistema devuelve "Validation failed" con código 422.
```

## Diagrama de Secuencia que refleje todos los casos de uso.

- Login : 
![Buscar Gif por palabra](https://github.com/alanbasavilbaso/larevel-test/blob/main/Diagrama%20de%20secuencia-login.jpg)

- Buscar Gif por palabra :
![Buscar Gif por palabra](https://github.com/alanbasavilbaso/larevel-test/raw/main/secuencia-buscar-gif-palabra.jpg)

- Buscar Gif por id :
![Buscar Gif por id](https://github.com/alanbasavilbaso/larevel-test/blob/main/secuencia%20-%20buscar-gif-id.jpg)

- Marcar gif como favorito :
![Buscar Gif por id](https://github.com/alanbasavilbaso/larevel-test/blob/main/secuencia-gif-favorito.jpg)


## DER 

![DER](https://github.com/alanbasavilbaso/larevel-test/blob/main/DER.jpg)
                                                     

## Postman

https://github.com/alanbasavilbaso/larevel-test/blob/main/Prex.postman_collection.json


## Dockerfile

```
FROM php:8.2-apache

RUN apt-get update && apt-get install -y nano \
    && docker-php-ext-install pdo pdo_mysql

RUN sed -i 's|<VirtualHost \*:80>|<VirtualHost *:8123>|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|#ServerName www.example.com|ServerName localhost|g' /etc/apache2/sites-available/000-default.conf

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 775 /var/www/html/storage

RUN a2enmod rewrite

RUN echo "Listen 8123" >> /etc/apache2/ports.conf

EXPOSE 8123
```