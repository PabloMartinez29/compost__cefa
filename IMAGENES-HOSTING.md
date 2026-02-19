# Imágenes en el hosting – por qué no cargan y cómo solucionarlo

Las imágenes de **registros** (residuos orgánicos, pilas de compostaje, maquinaria) se guardan en **`storage/app/public/`** (sistema por defecto de Laravel) y se muestran mediante el enlace simbólico `public/storage` usando `Storage::url()`. Si en el hosting no se ven, suele ser por una de estas causas.

---

## 1. APP_URL en el servidor

En el **.env del hosting** debe estar la URL real del sitio, **sin barra final**:

```env
APP_URL=https://compostcefa.online
```

Si pones `http://` en vez de `https://`, o `localhost`, o una URL distinta, los enlaces de las imágenes saldrán mal.

---

## 2. Enlace simbólico `public/storage` no creado

Las imágenes se guardan en:

- `storage/app/public/organics/`   → residuos orgánicos  
- `storage/app/public/compostings/` → pilas de compostaje  
- `storage/app/public/machineries/` → maquinaria  

**IMPORTANTE:** Para que las imágenes se muestren, Laravel necesita un **enlace simbólico** desde `public/storage` hacia `storage/app/public`.

En el servidor, ejecuta:

```bash
cd /ruta/del/proyecto
php artisan storage:link
```

Esto crea el enlace `public/storage` → `storage/app/public`, permitiendo que las URLs generadas por `Storage::url()` funcionen correctamente.

Si el enlace no existe, las imágenes darán 404 aunque estén guardadas en `storage/app/public/`.

---

## 3. Document root debe apuntar a `public/`

El dominio debe tener como **raíz del sitio** la carpeta `public` del proyecto, no la raíz del proyecto.

- Correcto: `DocumentRoot` → `.../compost__cefa/public`  
- Incorrecto: `DocumentRoot` → `.../compost__cefa`  

Si la raíz no es `public`, las rutas generadas por `Storage::url()` (como `/storage/organics/imagen.jpg`) no funcionarán porque el servidor buscará en la raíz del proyecto en lugar de en `public/storage`.

---

## 4. Imágenes subidas solo en local

Si las imágenes las subiste en tu PC y luego subes el proyecto por Git/FTP, **las carpetas `storage/app/public/organics`, `storage/app/public/compostings`, `storage/app/public/machineries` no suelen ir en el repositorio** (o van vacías). En el hosting esas carpetas estarán vacías y los registros que ya tenías en la base de datos seguirán apuntando a archivos que no existen en el servidor.

Opciones:

- Subir de nuevo las imágenes desde la aplicación en el hosting (editar cada registro y adjuntar la imagen), o  
- Copiar a mano las carpetas `storage/app/public/organics`, `storage/app/public/compostings`, `storage/app/public/machineries` desde tu PC al servidor (FTP, rsync, etc.) manteniendo la misma estructura.

---

## 5. Resumen de comprobaciones en el hosting

| Comprobación | Qué hacer |
|--------------|-----------|
| `.env` en el servidor | `APP_URL=https://compostcefa.online` (sin `/` al final) |
| Enlace simbólico | Ejecutar `php artisan storage:link` en el servidor |
| Carpetas en el servidor | Existen `storage/app/public/organics`, `storage/app/public/compostings`, `storage/app/public/machineries` |
| Permisos | `chmod 755` en `storage/app/public` y sus subcarpetas |
| Document root | Apunta a la carpeta `public` del proyecto |
| Archivos | Las imágenes que quieres ver están dentro de `storage/app/public/...` en el servidor |

Si todo esto está bien, las rutas que genera `Storage::url('organics/...')` (como `/storage/organics/imagen.jpg`) serán correctas y las imágenes de los registros deberían cargar en el hosting.

---

## 6. Si la app está en un subdirectorio

Si la URL del sitio es algo como `https://tudominio.com/compost` (subdirectorio), en el `.env` del servidor puedes forzar la base de los assets:

```env
APP_URL=https://tudominio.com/compost
ASSET_URL=https://tudominio.com/compost
```

Así `Storage::url('organics/x.jpg')` se convertirá en `https://tudominio.com/compost/storage/organics/x.jpg`.
