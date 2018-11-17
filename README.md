# PlaceToPay

Este es una prueba de desarrollo elaborada exclusivamente para PlaceToPay.

Este proyecto es un cliente SOAP que prueba los servicios web del PlacetoPay PSE.

El Cliente Soap establece una conexión que permite un proceso de pago básico. Al final del proceso el usuario puede ver el
Resultado de la transacción y las demás transacciones anteriores almacenadas.

## Comienzo

Estas instrucciones le permitirán obtener una copia del proyecto en funcionamiento en su máquina local para fines de desarrollo y prueba. Consulte la implementación para ver las notas sobre cómo implementar el proyecto en un sistema en vivo.

### Prerequisitos

Qué cosas necesita instalar antes y cómo instalarlo

* Composer
* Servidor Apache (opcional)
* PHP 7+
* Servidor de base de datos Mysql


### Instalación

Esta es una serie paso a paso con ejemplos que le indican cómo instalar y levantar la aplicación.

Deberá clonar el proyecto en el folder deseado

```
git clone https://github.com/softjuanm/placetopay.git
```

Ubicarse en el folder base del repositorio y cargar las dependencias via composer

```
cd placetopay
composer install
```

Cree el archivo `.env` (use` .env.example` como guía).

```
cp .env.example .env
```

Debera editar el archivo __.env__ y agregar las siguientes configuraciones de entorno

```
PLACETOPAY_KEY=
PLACETOPAY_ID=
PLACETOPAY_WSDL=https://test.placetopay.com/soap/pse/?wsdl
PLACETOPAY_ENDPOINT=https://test.placetopay.com/soap/pse/

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=
```

Genere una llave unica para su proyecto

```
php artisan key:generate
```

Realice la migracion de base de datos

```
php artisan migrate
```

Considere el uso de Laravel Homestead para lavantar la aplicacion. Homestead es una máquina virtual simple diseñada para Laravel como alternativa a Apache.

```
php artisan serve
```

Para acceder a la aplicacion deberá seguir la siguiente URL en su navegador de preferencia <http://127.0.0.1:8000>, en caso que haya utilizado Homestead.

Si utilizo un puerto diferente dbera remplazarlo en la url.


## Construido con

* [Laravel 5.7](https://laravel.com) - Framwework de desarrollo
* [Boostrap 4](https://getbootstrap.com/) - Bootstrap


## Autor

* **Juan Manuel Pinzón** - *Ingeniero Desarrollador*

## Licencia

Este proyecto está licenciado bajo la Licencia MIT