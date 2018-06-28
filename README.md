# BACKEND DE CONSORCIO LOCO
API para la aplicación de consorcios

## Index

- [Datos Mock](#datos-mock)
- [Instalacion](#instalacion)
  - [Dependencias](#dependencias)
  - [Migración](#migración)
  - [Seed](#seed)
- [Crear Modelo](#crear-modelo)
- [Crear Controller](#crear-controller)
- [Rutas](#rutas)


## Datos Mock

| Nombre | Contraseña | rol |
| ------------- | :-------------: | -------------: |
| maximiliano.depietro@gmail.com | changeme | Admin, Operator, User |
| marconidaniel@outlook.com | changeme | Admin, Operator, User |
| estebanmg_27@yahoo.com.ar | changeme | Admin, Operator, User |
| user@consorcio.com | changeme | user |
| operator@consorcio.com | changeme | operator, user |


## Instalación

La aplicación utiliza composer como gestor de paquetes:

```bash
    composer install
```

### Dependencias

Estos son los paquetes que se instalan con composer.

- Laravel
- Laravel/Passport
- Mockery
- Barryvdh/laravel-cors (Para poder levantar desde Angular)


### Migración

Para levantar los datos basta con ejecutar: 

```bash
    php artisan migrate
```

Esto levantará las tablas necesarias para que el programa corra

Si se quiere refrescar las bases de datos se puede aladir el comando :fresh


```bash
    php artisan migrate:fresh
```

Esto borrará las bases de datos y luego las volverá a crear

Si se quiere quitarlas solamente:

```bash
    php artisan migrate:rollback
```

Esto solo dejará una tabla con un log de las otras tablas dropedas.

### Seed

Para llenar la aplicación con datos de mock y de ejecución basta con ejecutar:

```bash
    php artisan db:seed
```

Esto llenará de datos el sitio para poder probar funcionalidades

[subir](#index)

## Crear Modelo

A) Comando en Artisan, añadir -m si se quiere hacer una tabla

```bash
    php artisan make:model NombreModelo -m
```

B) Si se persisten datos, definirlos en la propiedad $fillable

```php
<?php

protected $fillable = [
    'nombre', 'mail', 'propiedadCualquiera'
];

```

C) Definir el tipo de dato a almacenar en la tabla. Para ello hay que ir al migration generado anteriormente por make:model

El archivo se encontrará en database/migrations y se llamará create_nombremodeloenplural_table. En el caso de lo que hicimos sería: create_nombremodelos_table

Allí hay dos métodos uno up y otro down que levantarán o voltearán la tabla respectivamente.

El comando ya deja algunas cosas hechas, como el ID (que no es necesario definir en fillable) pero podemos completarlo con el resto de las cosas.

```php
   <?php
           Schema::create('proveedors', function (Blueprint $table) {
               $table->increments('id');
               $table->string('nombre');
               $table->string('email');
               $table->string('propiedadCualquiera');
               $table->timestamps();
           });
```

Hay diversos tipos de dato para usar para eso, ver el machete.

## Crear controller

Para hacer un controller, alcanza con ejecutar el comando:

```bash
    php artisan make:controller NombreModeloController
```

__Es importante llamar al controller igual que el modelo pero con el sufijo Controller para que Laravel lo reconozca legal y bonito__

Esto creará un archivo en app/Http/Controllers con el nombre definido. Aquí podremos definir los actions de nuestra app.


## Rutas

### Verbos

Las rutas nos permiten exponer los controllers al exterior. Allí definimos qué método usar para los actions y qué procesos previos podemos usar.

En nuestro caso, como de la app solo queremos sus  apis, hay que ir a routes/api.php

Allí veremos expuestos los métodos usando el siguiente formato: Route::get('/unidad', 'UnidadController@index'

Ese :: es un llamado a los métodos estáticos de Route y el get es reemplazado por los verbos HTTP (Post, Put, Get, Delete, etc)

Ejemplo 
```php
<?php

Route::post();
Route::get();

```

En este caso, al método estático le pasamos dos parámetros: El primero define la ruta a la que se accede y el segundo el controller y el método usado.

En el caso del ejemplo, cuando alguien vaya a sitio.com/api/unidad accederá al método index() de UnidadController

El /api/ es añadido por Laravel a todas las rutas que ponemos dentro de api, para difenrenciarlos de otros actions que redirigan a vistas o hagan otras cosas.


### Grupos

Como se verá en nuestro api.php tenemos también un llamado a group() y una definición de un middleware. Group es un atajo que nos provee Route para agrupar diversos actions, en este caso es para ejecutar antes 'api', 'cors'. 

El middleware se define en app/Http/Kernel.php dentro del array $routedMiddleware. Simplemente lo que hacemos es asociar 'cors' a la clase HandleCors que descargamos.

El group que hay definido señala que cualquier llamado a /api que hagamos en nuestra app, tiene que pasar por HandeCors que básicamente permite el acceso a nuestra apicación desde otros sitios webs.

No creo que sea necesario añadir más grupos, pero hay que tener en cuenta que tenemos que añadir nuestras rutas dentro del grupo principal.

## Roles y seguridad

Para definir los accesos a nuestro sitio, enganchamos un middleware a los accesos que querramos restringir. Un middleware es un software que se interpone entre dos elementos que se comunican entre sí. En nuestro caso, el middleware se interpone entre el request del cliente y el controller de la API.

Nuestro middleware recibe dos parámetros, el primero define que es de la api de autorización, y el segundo los scopes (roles) a los que permitimos el acceso a la información.

Ejemplo

```php
<?php

Route::get('/factura', 'FacturaController@index')->middleware('auth:api', 'scopes:operator,admin');
```

Aquí estamos diciendo que a FacturaController@index solo pueden acceder aquellos usuarios que tengan de scope operator y admin.


__NOTA:__ Dentro del seed de usuarios, hay uno para cada rol.

