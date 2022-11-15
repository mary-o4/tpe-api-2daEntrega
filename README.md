# API REST – RECURSO DE BOOKS
## Importar base de datos
Importar desde phpMyAdmin u otro database/db_tpe.sql

## Prueba con Postman o similar
El endpoint de la API es: http://localhost/practicoweb/tpeApiCopiaWeb/api/books

## Obtener todos los books
**Method:** GET
**URL:** api/books
**Response:** 200

## Obtener un book
**Method:** GET
**URL:** api/books/:id
**Response:** 200

## Insertar un nuevo book
**Method:** POST
**URL:** api/books
**Body:**
 `{
        "Titulo": "Crimen y Castigo",
        "Genero": "novela",
        "Fecha_de_Publicacion": "1866",
        "Editorial": "Alianza",
        "ISBN": 9789873952036,
        "Sinopsis": "Considerada por la crítica como la primera obra maestra de Dostoievski, Crimen y castigo es un profundo análisis psicológico de su protagonista, el joven estudiante Raskólnikov, cuya firme creencia en que los fines humanitarios justifican la maldad le conduce al asesinato de una usurera",
        "Imagen": "images/634cac4c25106.jpg",
        "ID_autor_FK": 1
    }`
**Response:** 201


## Eliminar un book
**Method:** DELETE
**Url:** api/books/:id
**Response:** 200

## Filtrar por titulo
Esta funcionalidad permite buscar un recurso por una parte de su titulo.
**Method:** GET
**Url:** api/books?search=gato
**Response:** 200

## Ordenar por cualquier campo
Esta funcionalidad permite obtener todos los recursos ordenados ascendentemente por cualquiera de los campos de la tabla del recurso.
**Method:** GET
**Url:** api/books?sort=Titulo
**Response:** 200

## Paginar todos los books
Esta funcionalidad permite mostrar cinco recursos por página.
**Method:** GET
**Url:** api/books?page=1
**Response:** 200

## Ordenar y paginar
Esta funcionalidad permite ordenar los recursos según cualquier campo de la tabla y mostrarlos paginados.
**Method:** GET
**Url:** api/books?sort=Titulo&page=1
**Response:** 200

## Ordenar y filtrar
Esta funcionalidad permite ordenar los recursos según cualquier campo de la tabla y filtrarlos por titulo.
**Method:** GET
**Url:** api/books?sort=Titulo&search=ana
**Response:** 200

## Paginar y filtrar
Esta funcionalidad permite paginar los recursos filtrados por título.
**Method:** GET
**Url:** api/books?page=1&search=cumbres
**Response:** 200

## Filtrar, ordenar y paginar
Esta funcionalidad permite ordenar los recursos según cualquier campo de la tabla, filtrarlos por título y paginarlos.
**Method:** GET
**Url:** api/books?search=ana&sort=ISBN&page=1
**Response:** 200

## ERRORES
- Key errónea
**Method:** GET
**Url:** api/books?sor=Titulo
**Response:** 400
**Mensaje de error:** Se ingresaron parametros incorrectos, ingrese sort, page, search o cualquier combinacion posible.
- Value vacio
**Method:** GET
**Url:** api/books?sort=&page=1
**Response:** 400
**Mensaje de error:** Alguno de los parametros es erroneo o esta vacio.
- Recurso erróneo
**Method:** GET
**Url:** api/boo
**Response:** 400
**Mensaje de error:** Page not found.

## CODIGOS DE RESPUESTA HTTP
### 200 OK
Se da cuando una solicitud realizada por el usuario tuvo éxito.

### 201 CREATED
Es la respuesta cuando se ha modificado o creado un recurso exitosamente.

### 400 BAD REQUEST
Indica que el servidor no puede o no procesara la petición debido a que algo es percibido como un error del cliente.

### 404 NOT FOUND
Indica que una página buscada no puede ser encontrada aunque la petición este correctamente hecha.

### 500 INTERNAL SERVER ERROR
Indica que el servidor encontró una condición inesperada que le impide completar la petición.