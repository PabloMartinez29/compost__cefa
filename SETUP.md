# Configuración del proyecto COMPOST CEFA (después de descomprimir)

Si recibiste el proyecto en una carpeta comprimida y al abrirlo no ves el logo, las imágenes de los registros o los registros no aparecen en las tablas, sigue estos pasos.

## 1. Variables de entorno

- Copia el archivo `.env.example` a `.env` si no tienes `.env`.
- Ejecuta: `php artisan key:generate`

## 2. Base de datos

- Crea la base de datos (MySQL/MariaDB) y configura `DB_*` en `.env`.
- Ejecuta las migraciones: `php artisan migrate`
- Si el proyecto incluye seeders (datos iniciales): `php artisan db:seed`

**Importante:** Si no hay residuos orgánicos ni datos de bodega, no podrás crear pilas de compostaje (el formulario requiere ingredientes disponibles). En ese caso ejecuta los seeders o crea primero residuos y movimientos de bodega.

## 3. Logo en la barra lateral

El logo **no viene** en la carpeta comprimida (solo la ruta). Tienes dos opciones:

- **Opción A:** Pide a tu compañero el archivo `logo-compost-cefa.webp` (o `.png`) y colócalo en:  
  `public/img/logo-compost-cefa.webp`
- **Opción B:** No hacer nada: si el archivo no existe, se mostrará el texto **"COMPOST CEFA"** con un icono como respaldo.

Más detalles: `public/img/README.md`

## 4. Imágenes de los registros

Las imágenes de pilas, residuos, maquinaria, etc. se guardan en **storage**. En un proyecto recién descomprimido suele pasar que:

- La carpeta `storage/app/public` está vacía (los archivos no se incluyen en el zip).
- No existe el enlace simbólico para servir archivos desde `storage`.

Haz lo siguiente:

```bash
php artisan storage:link
```

Esto crea el enlace `public/storage` → `storage/app/public`. Las **nuevas** subidas se verán bien. Los registros que ya tengan ruta de imagen pero cuyos archivos no estén en tu carpeta (porque no venían en el zip) mostrarán un icono por defecto hasta que vuelvas a subir una imagen.

## 5. Registros que no aparecen en las tablas (Pilas, Proveedores)

Si **haces un registro nuevo** y no aparece en la lista:

1. **Revisa mensajes de error:** Después de guardar, si hay error de validación o de servidor, debería mostrarse en la parte superior de la página (mensaje en rojo o verde). Si ves uno, léelo para saber qué falta (por ejemplo: “La cantidad excede la disponible en bodega”, “Debe seleccionar una maquinaria”, etc.).
2. **Base de datos:** Comprueba que estás usando la misma base de datos en `.env` y que las migraciones se ejecutaron correctamente.
3. **Pilas:** Para crear una pila necesitas tener **residuos orgánicos** y **cantidad disponible en bodega**. Si no hay datos de bodega o residuos, primero créalos desde los módulos correspondientes.
4. **Proveedores:** Para “Nuevo Registro” de proveedor debes elegir una **maquinaria** que aún no tenga proveedor. Si no hay maquinarias creadas, crea primero al menos una en Maquinaria.

## 6. Errores en el módulo de Seguimientos

Si al abrir una pila en Seguimientos o al hacer alguna acción te sale error en pantalla:

- Los errores del servidor ahora se capturan y se muestra un mensaje en el modal en lugar de una pantalla en blanco o 500.
- Revisa el archivo `storage/logs/laravel.log` para ver el detalle del error (útil para depuración o para reportar al que mantiene el proyecto).

## Resumen de comandos

```bash
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link
```

Luego coloca el logo en `public/img/logo-compost-cefa.webp` si lo tienes.
