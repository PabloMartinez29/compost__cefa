# Imágenes en el hosting – por qué no cargan y cómo solucionarlo

Las imágenes de **registros** (residuos orgánicos, pilas de compostaje, maquinaria) se guardan **directamente en `public/storage/`** (carpetas `organics`, `compostings`, `machineries`) para que en el servidor no afecte el enlace simbólico. Se muestran con `asset('storage/...')`. Si en el hosting no se ven, suele ser por una de estas causas.

---

## 1. APP_URL en el servidor

En el **.env del hosting** debe estar la URL real del sitio, **sin barra final**:

```env
APP_URL=https://compostcefa.online
```

Si pones `http://` en vez de `https://`, o `localhost`, o una URL distinta, los enlaces de las imágenes saldrán mal.

---

## 2. Carpetas dentro de `public/storage/`

Las imágenes se guardan en:

- `public/storage/organics/`   → residuos orgánicos  
- `public/storage/compostings/` → pilas de compostaje  
- `public/storage/machineries/` → maquinaria  

**No hace falta** ejecutar `php artisan storage:link`: los archivos se guardan directamente en `public/storage/...`. La aplicación crea las carpetas al subir la primera imagen si no existen.

En el servidor, asegúrate de que la carpeta `public/storage` (y sus subcarpetas) tengan permisos de escritura para el usuario del servidor web (p. ej. `chmod 755` o que el proceso que ejecuta PHP pueda escribir).

---

## 3. Document root debe apuntar a `public/`

El dominio debe tener como **raíz del sitio** la carpeta `public` del proyecto, no la raíz del proyecto.

- Correcto: `DocumentRoot` → `.../compost__cefa/public`  
- Incorrecto: `DocumentRoot` → `.../compost__cefa`  

Si la raíz no es `public`, las rutas generadas por `asset('storage/...')` (como `/storage/organics/imagen.jpg`) no funcionarán porque el servidor buscará en la raíz del proyecto en lugar de en `public/storage`.

---

## 4. Imágenes subidas solo en local

Si las imágenes las subiste en tu PC y luego subes el proyecto por Git/FTP, **las carpetas `storage/app/public/organics`, `storage/app/public/compostings`, `storage/app/public/machineries` no suelen ir en el repositorio** (o van vacías). En el hosting esas carpetas estarán vacías y los registros que ya tenías en la base de datos seguirán apuntando a archivos que no existen en el servidor.

Opciones:

- Subir de nuevo las imágenes desde la aplicación en el hosting (editar cada registro y adjuntar la imagen), o  
- Copiar a mano las carpetas `public/storage/organics`, `public/storage/compostings`, `public/storage/machineries` desde tu PC al servidor (FTP, rsync, etc.) manteniendo la misma estructura.

---

## 5. Resumen de comprobaciones en el hosting

| Comprobación | Qué hacer |
|--------------|-----------|
| `.env` en el servidor | `APP_URL=https://compostcefa.online` (sin `/` al final) |
| Carpetas en el servidor | Existen `public/storage/organics`, `public/storage/compostings`, `public/storage/machineries` (se crean solas al subir la primera imagen) |
| Permisos | `chmod 755` en `public/storage` y subcarpetas para que la app pueda escribir |
| Document root | Apunta a la carpeta `public` del proyecto |
| Archivos | Las imágenes están en `public/storage/...` en el servidor |

Si todo esto está bien, las rutas que genera `asset('storage/organics/...')` (como `/storage/organics/imagen.jpg`) serán correctas y las imágenes de los registros deberían cargar en el hosting.

---

## 6. Si la app está en un subdirectorio

Si la URL del sitio es algo como `https://tudominio.com/compost` (subdirectorio), en el `.env` del servidor puedes forzar la base de los assets:

```env
APP_URL=https://tudominio.com/compost
ASSET_URL=https://tudominio.com/compost
```

Así `asset('storage/organics/x.jpg')` se convertirá en `https://tudominio.com/compost/storage/organics/x.jpg`.

---

## 7. Evitar copiar manualmente de `compost__cefa/public/storage` a `public_html`

Si en el hosting tienes **dos carpetas**: `compost__cefa` (donde está Laravel) y `public_html` (document root del dominio), las imágenes se guardan por defecto en `compost__cefa/public/storage/...` y no se ven porque el servidor sirve desde `public_html`. Para **evitar tener que copiar manualmente** el contenido de `storage` a `public_html`, puedes hacer que la aplicación guarde las imágenes directamente en `public_html`:

1. En el **.env del servidor** define la ruta absoluta a la carpeta `public_html` (sin barra final):

```env
UPLOAD_PUBLIC_PATH=/home/tu_usuario/public_html
```

Sustituye `/home/tu_usuario/public_html` por la ruta real que use tu hosting (p. ej. en cPanel suele ser algo como `/home/cpanel_user/public_html`).

2. Asegúrate de que esa carpeta tenga permisos de escritura para el usuario con el que corre PHP (p. ej. `chmod 755` en `public_html` y que existan o se creen `public_html/storage/organics`, `public_html/storage/compostings`, `public_html/storage/machineries`).

3. Las rutas en la base de datos siguen siendo relativas (`organics/nombre.jpg`, etc.) y en las vistas se sigue usando `asset('storage/...')`, así que las URLs no cambian: las imágenes se sirven desde el document root y no hace falta copiar nada a mano.
