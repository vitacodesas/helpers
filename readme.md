
# Vitacode Database Export/Import Package

Este paquete para Laravel permite exportar la estructura de una base de datos y los primeros 1000 registros de las tablas. Además, proporciona un comando de importación que restringe la operación a conexiones provenientes únicamente de `localhost` para evitar problemas de seguridad.

## Instalación

1. **Instalar el paquete usando Composer**:

   Para instalar el paquete, ejecuta el siguiente comando en la raíz de tu proyecto Laravel:

   ```bash
   composer require vitacode/database
   ```

<!-- 2. **Publicar el archivo de configuración** (si es necesario):

   Si deseas personalizar las configuraciones del paquete, puedes publicar el archivo de configuración con el siguiente comando:

   ```bash
   php artisan vendor:publish --provider="Vitacode\Database\DatabaseServiceProvider" --tag="config"
   ```

   Esto generará un archivo de configuración `config/databaseexport.php` donde puedes modificar los parámetros según tus necesidades. -->

## Uso

### Comando de Exportación

Este paquete permite exportar la estructura de la base de datos junto con los primeros 1000 registros de cada tabla. Para realizar la exportación, ejecuta el siguiente comando:

```bash
php artisan db:export
```

Este comando generará un archivo de exportación que contiene la estructura de la base de datos y los primeros 1000 registros de cada tabla. El archivo será guardado en la carpeta `storage/app/exports`.

#### Opciones del Comando de Exportación

- **--path**: Puedes especificar la ruta donde se almacenarán las exportaciones

  ```bash
  php artisan db:export
  ```

### Comando de Importación

El paquete también permite importar la estructura y los datos desde un archivo previamente exportado. Sin embargo, el comando de importación tiene una restricción de seguridad: **solo permite la conexión desde `localhost`**. Esto significa que no podrás importar datos desde una dirección IP externa.

Para realizar la importación, utiliza el siguiente comando:

```bash
php artisan db:import
```

Este comando leerá el archivo de exportación previamente generado desde `storage/app/exports` y restaurará la estructura y los primeros 1000 registros de las tablas.

#### Restricción de Conexión

El comando de importación solo permite conexiones desde `localhost` para evitar problemas de seguridad, ya que permitir conexiones desde direcciones IP externas puede representar un riesgo para la integridad de tu base de datos.

Si intentas ejecutar el comando desde una IP externa, recibirás un error similar a este:

```
Error: La importación solo está permitida desde localhost.
```

## Consideraciones Técnicas

- **Limitación de 1000 registros**: El paquete está diseñado para exportar un máximo de 1000 registros por tabla. Si deseas exportar más registros, deberás modificar el código del paquete o adaptar el proceso de exportación a tus necesidades.

- **Compatibilidad de Base de Datos**: Este paquete es compatible con bases de datos que Laravel soporta de forma predeterminada (MySQL, PostgreSQL, SQLite, etc.).

- **Exportación de Estructura**: La exportación incluye la estructura de las tablas, pero no las relaciones entre ellas. Si necesitas exportar relaciones complejas (por ejemplo, claves foráneas), deberás adaptar el código.

- **Formato del Archivo Exportado**: El archivo generado para la exportación está en formato `.sql`, que es fácilmente importable en otras bases de datos o entornos.

## Contribuciones

Si deseas contribuir al desarrollo de este paquete, por favor sigue estos pasos:

1. Haz un fork del repositorio.
2. Crea una nueva rama (`git checkout -b feature/nueva-funcionalidad`).
3. Realiza tus cambios y haz un commit (`git commit -am 'Añadir nueva funcionalidad'`).
4. Haz un push a la rama (`git push origin feature/nueva-funcionalidad`).
5. Crea un pull request en GitHub.

## Licencia

Este paquete es de código abierto y se distribuye bajo la licencia MIT.


## Versionamientos

Ejemplo de incremento de versión:
1. Si tu paquete está en la versión 1.0.0 y agregas nuevas funcionalidades sin romper las anteriores, la siguiente versión sería 1.1.0.
2. Si corriges errores pero no agregas nuevas funcionalidades, la siguiente versión sería 1.0.1.
3. Si haces un cambio importante que no es compatible con versiones anteriores, la siguiente versión sería 2.0.0.