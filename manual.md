MANUAL DE USUARIO DEL ADMINISTRADOR
Sistema de Gestión de Compostaje – COMPOST CEFA
Versión: 1.0
Fecha de elaboración: 12/02/2025
Elaborado por: Equipo de Desarrollo COMPOST CEFA
Entidad responsable: Centro de Formación Agroindustrial (CEFA)

---

## TABLA DE CONTENIDO

1. Objetivo
2. Alcance
3. Términos y Definiciones
4. Introducción al Sistema
5. Acceso al Sistema (Inicio de Sesión)
6. Estructura de Navegación
7. Módulos del Sistema
   7.1. Dashboard
   7.2. Gestión de Usuarios
   7.3. Monitoreo
   7.4. Residuos Orgánicos
   7.5. Bodega de Clasificación
   7.6. Creación de Pilas
       7.6.1. Pila
       7.6.2. Seguimiento
   7.7. Maquinaria
       7.7.1. Identificación y Especificaciones
       7.7.2. Datos del Proveedor
       7.7.3. Control de Actividades (Mantenimiento)
       7.7.4. Control de Uso del Equipo
   7.8. Abono Terminado
8. Notificaciones
9. Generación de Reportes PDF y Excel
10. Preguntas Frecuentes
11. Solución de Problemas
12. Datos de Contacto

---

## 1. Objetivo

El presente manual tiene como objetivo proporcionar una guía completa y detallada sobre el funcionamiento del **Sistema de Gestión de Compostaje – COMPOST CEFA**, dirigido específicamente al usuario con rol de **Administrador**. Se describen todos los módulos, funcionalidades, acciones disponibles y procedimientos para la gestión integral del proceso de compostaje.

---

## 2. Alcance

Este manual cubre todas las funcionalidades disponibles para el perfil de **Administrador** del sistema, incluyendo:

- Gestión completa de usuarios (creación, edición, activación/desactivación).
- Registro y administración de residuos orgánicos.
- Gestión de la bodega de clasificación (inventario y movimientos).
- Creación y seguimiento de pilas de compostaje.
- Gestión de maquinaria (especificaciones, proveedores, mantenimiento y uso).
- Registro y control de abono terminado.
- Monitoreo general con gráficas y exportación de reportes.
- Gestión de notificaciones (solicitudes de eliminación y recordatorios de mantenimiento).

---

## 3. Términos y Definiciones

| Término | Definición |
|---|---|
| **Pila de Compostaje** | Acumulación organizada de residuos orgánicos para su descomposición controlada. |
| **Seguimiento (Tracking)** | Registro periódico del estado de una pila de compostaje (temperatura, humedad, pH, etc.). |
| **Residuo Orgánico** | Material de origen biológico que se descarta y se utiliza como insumo para el compostaje. |
| **Bodega** | Espacio de almacenamiento donde se clasifican los residuos orgánicos por tipo. |
| **Abono Terminado** | Producto final del proceso de compostaje, listo para ser entregado o utilizado. |
| **Maquinaria** | Equipos y herramientas utilizados en el proceso de compostaje. |
| **Administrador** | Usuario con permisos completos para gestionar todos los módulos del sistema. |
| **Aprendiz** | Usuario con permisos limitados, orientado al registro de información bajo supervisión. |
| **DataTables** | Componente de tablas interactivas que permite buscar, ordenar y paginar registros. |
| **SweetAlert** | Componente de alertas interactivas para confirmaciones y mensajes del sistema. |
| **PDF** | Formato de documento portable para la generación de reportes descargables. |

---

## 4. Introducción al Sistema

**COMPOST CEFA** es una plataforma web desarrollada para automatizar y gestionar el proceso integral de compostaje del Centro de Formación Agroindustrial. El sistema permite llevar un control detallado desde la recepción de residuos orgánicos hasta la entrega del abono terminado, pasando por la clasificación en bodega, la creación y seguimiento de pilas, y la gestión de maquinaria.

### Características Principales:
- **Control integral del proceso de compostaje**: Desde la recepción de residuos hasta la entrega del producto final.
- **Gestión de usuarios con roles diferenciados**: Administrador y Aprendiz.
- **Monitoreo en tiempo real**: Gráficas y estadísticas actualizadas de todos los módulos.
- **Generación de reportes**: Exportación a PDF y Excel para documentación y análisis.
- **Sistema de notificaciones**: Alertas de mantenimiento de maquinaria y solicitudes de eliminación.
- **Interfaz responsiva**: Diseño adaptable a diferentes dispositivos.

### Requisitos del Sistema:
- Navegador web moderno (Google Chrome, Mozilla Firefox, Microsoft Edge).
- Conexión a Internet estable.
- Resolución de pantalla mínima recomendada: 1280 x 720 píxeles.

---

## 5. Acceso al Sistema (Inicio de Sesión)

Para acceder al sistema como Administrador:

1. Abra su navegador web e ingrese la URL del sistema.

`[📸 CAPTURA 1: Pantalla de inicio de sesión – Formulario con campos de correo electrónico y contraseña]`

2. En la página de inicio de sesión, ingrese su **correo electrónico** y **contraseña**.
3. Haga clic en el botón **"Iniciar Sesión"**.
4. Si las credenciales son correctas, será redirigido al **Dashboard** del panel de administración.

`[📸 CAPTURA 2: Redirección exitosa al Dashboard después de iniciar sesión]`

> **Nota:** Si olvidó su contraseña, contacte al administrador del sistema para restablecerla. Si su cuenta está desactivada, no podrá acceder al sistema.

---

## 6. Estructura de Navegación

Al ingresar al sistema, el administrador encontrará una interfaz dividida en tres secciones principales:

`[📸 CAPTURA 3: Vista general de la interfaz completa – Señalar con flechas o recuadros las 3 secciones: Sidebar (izquierda), Barra Superior (arriba) y Área de Contenido (centro)]`

### 6.1. Barra Lateral (Sidebar)
Ubicada en el lado izquierdo, contiene el menú de navegación principal con los siguientes elementos:

| Icono | Módulo | Descripción |
|---|---|---|
| 🌐 | **Dashboard** | Panel principal con resumen general del sistema. |
| 👥 | **Gestión de Usuarios** | Administración de cuentas de usuarios. |
| 📊 | **Monitoreo** | Supervisión general de todos los módulos con gráficas. |
| ♻️ | **Residuos** | Gestión de residuos orgánicos (Ver Registros / Registrar Nuevo). |
| 🏭 | **Bodega** | Inventario de la bodega de clasificación. |
| ⛰️ | **Creación de Pilas** | Gestión de pilas de compostaje y su seguimiento. |
| ⚙️ | **Maquinaria** | Gestión de equipos, proveedores, mantenimiento y uso. |
| 🌱 | **Abono** | Registro y control de abono terminado. |

`[📸 CAPTURA 4: Barra lateral (Sidebar) completa – Todos los módulos visibles]`

Algunos módulos tienen **submenús desplegables** que se abren al hacer clic en el nombre del módulo. Estos submenús muestran las opciones adicionales disponibles.

`[📸 CAPTURA 5: Sidebar con submenús desplegados – Mostrar los menús expandidos de Residuos, Creación de Pilas, Maquinaria y Abono]`

### 6.2. Barra Superior (Header)
Ubicada en la parte superior, muestra:
- El título **"Panel de Administración"**.
- Un **ícono de campana** 🔔 para acceder a las notificaciones (con un badge rojo que indica la cantidad de notificaciones pendientes).
- Un **menú de usuario** con las opciones de perfil y cerrar sesión.

`[📸 CAPTURA 6: Barra superior – Señalar el título, ícono de campana con badge rojo y menú de usuario]`

`[📸 CAPTURA 7: Menú desplegable de notificaciones – Clic en la campana mostrando las notificaciones pendientes]`

`[📸 CAPTURA 8: Menú desplegable de usuario – Opciones de perfil y cerrar sesión]`

### 6.3. Área de Contenido
Espacio central donde se muestra el contenido del módulo seleccionado.

---

## 7. Módulos del Sistema

---

### 7.1. Dashboard

**Ruta de acceso:** Sidebar → Dashboard

El Dashboard es la pantalla principal del sistema y muestra un resumen general con las estadísticas más relevantes de todos los módulos. Incluye:

- **Tarjetas de estadísticas** con datos clave de cada módulo (total de registros, pilas activas, peso de residuos, etc.).
- Se actualiza cada vez que se accede a él, reflejando los datos más recientes.
- Muestra la fecha actual (zona horaria: América/Bogotá).

`[📸 CAPTURA 9: Vista completa del Dashboard – Todas las tarjetas de estadísticas visibles]`

---

### 7.2. Gestión de Usuarios

**Ruta de acceso:** Sidebar → Gestión de Usuarios

Este módulo permite al administrador gestionar todas las cuentas de usuario del sistema.

#### Tarjetas de Estadísticas:
| Tarjeta | Descripción |
|---|---|
| **Total Usuarios** | Cantidad total de usuarios registrados en el sistema. |
| **Administradores** | Cantidad de usuarios con rol de Administrador. |
| **Aprendices** | Cantidad de usuarios con rol de Aprendiz. |

`[📸 CAPTURA 10: Vista principal de Gestión de Usuarios – Tarjetas de estadísticas (Total Usuarios, Administradores, Aprendices)]`

#### Tabla de Usuarios:
La tabla interactiva (DataTables) muestra la siguiente información por cada usuario:

| Columna | Descripción |
|---|---|
| **Tipo** | Tipo de documento de identidad (CC, TI, CE, PEP, Pasaporte). |
| **Identificación** | Número de documento de identidad. |
| **Usuario** | Nombre completo del usuario (con indicador "Tú" si es el usuario actual). |
| **Email** | Correo electrónico del usuario. |
| **Rol** | Rol asignado: Administrador (verde) o Aprendiz (azul). |
| **Fecha Registro** | Fecha en que se creó la cuenta. |
| **Estado** | Activo (verde) o Inactivo (gris). |
| **Acciones** | Botones de acción disponibles. |

`[📸 CAPTURA 11: Tabla de usuarios con DataTables – Mostrar registros con badges de rol (verde/azul) y estado (Activo/Inactivo)]`

#### Acciones disponibles:
| Botón | Acción | Descripción |
|---|---|---|
| 👁️ **Ver** | Ver detalles | Abre un modal con la información completa del usuario. |
| ✏️ **Editar** | Editar usuario | Abre un modal para modificar nombre, email, documento, rol y contraseña. |
| 🚫 **Desactivar** | Desactivar usuario | Desactiva la cuenta (el usuario no podrá iniciar sesión). Solo disponible para otros usuarios. |
| ✅ **Activar** | Activar usuario | Reactiva una cuenta desactivada. Solo disponible para usuarios inactivos. |
| 📄 **PDF** | Descargar PDF | Genera un reporte PDF con la información del usuario. |

#### Crear Nuevo Usuario:
1. Haga clic en el botón verde **"+ Nuevo Usuario"** en la esquina superior derecha.

`[📸 CAPTURA 12: Botón "+ Nuevo Usuario" resaltado en la esquina superior derecha de la tabla]`

2. Complete el formulario con los datos del usuario:
   - **Nombre Completo** (obligatorio)
   - **Correo Electrónico** (obligatorio, debe ser único)
   - **Tipo de Documento** (CC, TI, CE, PEP, Pasaporte)
   - **Número de Identificación** (obligatorio)
   - **Rol** (Administrador o Aprendiz)
   - **Contraseña** (obligatoria)

`[📸 CAPTURA 13: Formulario de creación de nuevo usuario – Todos los campos visibles y llenados con datos de ejemplo]`

3. Haga clic en **"Guardar"**.

`[📸 CAPTURA 14: Mensaje de éxito (SweetAlert) – "¡Éxito! Usuario creado correctamente"]`

#### Ver Detalles de Usuario:
1. Haga clic en el ícono de ver (👁️) en la fila del usuario.

`[📸 CAPTURA 15: Modal de ver detalles del usuario – Avatar, nombre, email, identificación, tipo de documento, rol, estado, fechas]`

#### Editar Usuario:
1. Haga clic en el ícono de editar (✏️) en la fila correspondiente.

`[📸 CAPTURA 16: Modal de edición de usuario – Campos pre-llenados con datos actuales (nombre, email, tipo documento, identificación, rol, contraseña)]`

2. Modifique los campos necesarios en el modal.
3. El campo **"Nueva Contraseña"** es opcional; si se deja vacío, se mantiene la contraseña actual.
4. Haga clic en **"Actualizar Usuario"**.

`[📸 CAPTURA 17: Mensaje de éxito (SweetAlert) – "¡Éxito! Usuario actualizado correctamente"]`

#### Desactivar Usuario:
1. Haga clic en el ícono de desactivar (🚫) en la fila del usuario.

`[📸 CAPTURA 18: Alerta SweetAlert de confirmación de desactivación – "¿Desactivar Usuario?" con nombre del usuario y botones Sí/Cancelar]`

`[📸 CAPTURA 19: Usuario mostrado como "Inactivo" en la tabla después de la desactivación]`

#### Activar Usuario:
1. Haga clic en el ícono de activar (✅) en la fila del usuario inactivo.

`[📸 CAPTURA 20: Alerta SweetAlert de confirmación de activación – "¿Activar Usuario?" con nombre del usuario y botones Sí/Cancelar]`

#### Descargar PDF de Todos los Usuarios:
- Haga clic en el botón rojo **PDF** (📄) ubicado junto al botón de "Nuevo Usuario" para descargar un reporte con todos los usuarios.

`[📸 CAPTURA 21: PDF generado de un usuario individual – Vista previa del documento descargado]`

`[📸 CAPTURA 22: PDF general de todos los usuarios – Vista previa del documento descargado]`

---

### 7.3. Monitoreo

**Ruta de acceso:** Sidebar → Monitoreo

El módulo de Monitoreo proporciona una visión global de la actividad del sistema a través de gráficas interactivas y datos tabulados.

`[📸 CAPTURA 23: Vista principal de Monitoreo – Filtros de período y tarjetas de módulos (Residuos, Pilas, Abono, Maquinaria)]`

#### Filtros de Período:
En la parte superior se encuentran los filtros para personalizar el rango de datos visualizados:

| Filtro | Opciones |
|---|---|
| **Período** | Diario, Semanal, Quincenal, Mensual, Anual. |
| **Fecha Inicio** | Fecha desde la cual se desean ver los datos. |
| **Fecha Fin** | Fecha hasta la cual se desean ver los datos. |

Después de configurar los filtros, haga clic en **"Filtrar"** para actualizar los datos.

`[📸 CAPTURA 24: Filtros seleccionados – Ejemplo con período "Mensual" y rango de fechas configurado, botón "Filtrar" resaltado]`

#### Tarjetas de Módulos:
Se muestran 4 tarjetas clicables que representan los módulos principales:

| Tarjeta | Datos mostrados |
|---|---|
| **Residuos** | Total de registros y peso total en Kg. |
| **Pilas** | Total de pilas y cantidad de pilas activas. |
| **Abono** | Total de registros y cantidad total en Kg/L. |
| **Maquinaria** | Total de equipos registrados. |

#### Interacción:
1. **Haga clic en una tarjeta** para expandir la sección de ese módulo.
2. Se mostrará:
   - Una **gráfica interactiva** (barras o líneas según el módulo).
   - Un **historial de registros** en tabla con DataTables (5 registros por página).

`[📸 CAPTURA 25: Módulo Residuos expandido – Gráfica de barras (peso por tipo de residuo) + historial en tabla DataTables]`

`[📸 CAPTURA 26: Módulo Pilas expandido – Gráfica de barras (pilas activas vs. completadas) + historial en tabla]`

`[📸 CAPTURA 27: Módulo Abono expandido – Gráfica de línea (tendencia de registros por fecha) + historial en tabla]`

`[📸 CAPTURA 28: Módulo Maquinaria expandido – Gráfica de barras (estado de equipos) + historial en tabla]`

3. Para exportar los datos del módulo seleccionado:
   - Haga clic en **"Excel"** (verde) para descargar en formato Excel.
   - Haga clic en **"PDF"** (rojo) para descargar en formato PDF.

`[📸 CAPTURA 29: Botones de exportación resaltados – Botón Excel (verde) y botón PDF (rojo)]`

#### Tipos de Gráficas por Módulo:
| Módulo | Tipo de Gráfica | Contenido |
|---|---|---|
| **Residuos** | Barras | Peso total por tipo de residuo (Cocina, Camas, Hojas, etc.). |
| **Pilas** | Barras | Cantidad de pilas en proceso vs. completadas. |
| **Abono** | Línea | Tendencia de registros por fecha. |
| **Maquinaria** | Barras | Estado de equipos (Operativa vs. Registrada). |

---

### 7.4. Residuos Orgánicos

**Ruta de acceso:** Sidebar → Residuos → Ver Registros / Registrar Nuevo

Este módulo gestiona el registro de todos los residuos orgánicos que ingresan al sistema.

#### Tarjetas de Estadísticas:
| Tarjeta | Descripción |
|---|---|
| **Peso Total** | Suma total del peso de todos los residuos registrados (en Kg). |
| **Total Registros** | Cantidad total de registros de residuos. |
| **Registros Hoy** | Cantidad de registros realizados en el día actual. |
| **Peso Hoy** | Peso total de los residuos registrados hoy (en Kg). |

`[📸 CAPTURA 30: Vista principal de Residuos Orgánicos – Tarjetas de estadísticas (Peso Total, Total Registros, Registros Hoy, Peso Hoy)]`

#### Tabla de Residuos:
| Columna | Descripción |
|---|---|
| **ID** | Identificador único del registro (formato: #001). |
| **Fecha** | Fecha del registro. |
| **Imagen** | Miniatura de la imagen del residuo (clic para ampliar). |
| **Tipo** | Tipo de residuo orgánico. |
| **Peso (Kg)** | Peso del residuo en kilogramos. |
| **Entregado Por** | Nombre de la persona que entrega el residuo. |
| **Recibido Por** | Nombre de la persona que recibe el residuo. |
| **Creado por** | Usuario que creó el registro (Administrador o Aprendiz). |
| **Acciones** | Botones de acción. |

`[📸 CAPTURA 31: Tabla de residuos orgánicos con DataTables – Registros visibles con miniaturas de imagen y badges de tipo]`

#### Tipos de Residuos:
| Tipo (interno) | Nombre en Español |
|---|---|
| Kitchen | 🍽️ Cocina |
| Beds | 🛏️ Camas |
| Leaves | 🍃 Hojas |
| CowDung | 🐄 Estiércol de Vaca |
| ChickenManure | 🐔 Gallinaza |
| PigManure | 🐷 Estiércol de Cerdo |
| Other | 📦 Otro |

#### Acciones disponibles:
| Botón | Descripción |
|---|---|
| 👁️ **Ver** | Abre un modal con los detalles completos del residuo, incluyendo imagen, notas e información del creador. |
| ✏️ **Editar** | Abre un modal para modificar los datos del registro. Permite cambiar la imagen (la nueva reemplaza la anterior). |
| 🗑️ **Eliminar** | Elimina el registro previa confirmación con SweetAlert. |
| 📄 **PDF** | Descarga un reporte PDF individual del registro. |

#### Registrar Nuevo Residuo:
1. Haga clic en el botón **"+ Nuevo Registro"** o acceda vía Sidebar → Residuos → Registrar Nuevo.

`[📸 CAPTURA 32: Botón "+ Nuevo Registro" resaltado en la esquina superior derecha]`

2. Complete el formulario:
   - **Fecha** (obligatorio, por defecto la fecha actual).
   - **Tipo de Residuo** (seleccionar de la lista desplegable, obligatorio).
   - **Peso en Kg** (obligatorio, mínimo 0.01).
   - **Entregado Por** (nombre, obligatorio).
   - **Recibido Por** (nombre, obligatorio, por defecto el nombre del administrador).
   - **Imagen** (obligatoria, tamaño máximo: 2MB, formatos: JPEG, PNG, JPG, GIF).
   - **Notas** (opcional).

`[📸 CAPTURA 33: Formulario de nuevo residuo – Todos los campos completos con datos de ejemplo y vista previa de imagen seleccionada]`

3. Haga clic en **"Guardar Registro"**.

`[📸 CAPTURA 34: Mensaje de éxito (SweetAlert) – "¡Éxito! Residuo registrado correctamente"]`

#### Ver Detalles de Residuo:
1. Haga clic en el ícono de ver (👁️) en la fila del residuo.

`[📸 CAPTURA 35: Modal de ver detalles del residuo – Imagen ampliada, tipo, peso, entregado por, recibido por, notas, creador]`

#### Ver Imagen Ampliada:
1. Haga clic en la miniatura de la imagen en la tabla.

`[📸 CAPTURA 36: Modal de imagen ampliada – Imagen del residuo a pantalla completa]`

#### Editar Residuo:
1. Haga clic en el ícono de editar (✏️) en la fila del residuo.

`[📸 CAPTURA 37: Modal de edición de residuo – Campos pre-llenados con datos actuales y opción de cambiar imagen]`

2. Modifique los campos necesarios y haga clic en **"Actualizar"**.

`[📸 CAPTURA 38: Mensaje de éxito (SweetAlert) – "¡Éxito! Residuo actualizado correctamente"]`

#### Eliminar Residuo:
1. Haga clic en el ícono de eliminar (🗑️) en la fila del residuo.

`[📸 CAPTURA 39: Alerta SweetAlert de confirmación de eliminación – "¿Eliminar Registro?" con botones Sí/Cancelar]`

#### Descargar PDF General:
- Haga clic en el botón rojo **PDF** (📄) para descargar un reporte con todos los residuos.

`[📸 CAPTURA 40: PDF generado de un residuo individual – Vista previa del documento]`

`[📸 CAPTURA 41: PDF general de todos los residuos – Vista previa del documento]`

---

### 7.5. Bodega de Clasificación

**Ruta de acceso:** Sidebar → Bodega → Inventario

La bodega muestra el inventario clasificado de residuos orgánicos almacenados.

#### Tarjetas de Inventario:
Se muestra una tarjeta de resumen general y una tarjeta por cada tipo de residuo:

| Tarjeta | Descripción |
|---|---|
| **Total Inventario** | Peso total de todos los residuos almacenados. |
| **Cocina** | Cantidad almacenada de residuos de cocina. |
| **Camas** | Cantidad almacenada de residuos de camas. |
| **Hojas** | Cantidad almacenada de hojas. |
| **Estiércol de Vaca** | Cantidad almacenada. |
| **Gallinaza** | Cantidad almacenada. |
| **Estiércol de Cerdo** | Cantidad almacenada. |
| **Otro** | Otros residuos almacenados. |

`[📸 CAPTURA 42: Vista principal de Bodega – Tarjeta de Total Inventario y tarjetas individuales por cada tipo de residuo con sus cantidades]`

Cada tarjeta de tipo es clicable y lleva a una **vista detallada** de los movimientos de ese tipo específico de residuo.

`[📸 CAPTURA 43: Vista detallada de un tipo específico – Ejemplo: Clic en tarjeta "Cocina" mostrando los movimientos de ese tipo]`

#### Movimientos Recientes:
Se muestra una tabla con los movimientos más recientes del inventario:

| Columna | Descripción |
|---|---|
| **Tipo de Movimiento** | Entrada o Salida de inventario. |
| **Peso (Kg)** | Cantidad del movimiento. |
| **Procesado por** | Usuario que realizó el movimiento. |
| **Acciones** | Ver detalles del movimiento. |

`[📸 CAPTURA 44: Tabla de Movimientos Recientes – Registros de entradas y salidas de inventario]`

#### Acciones disponibles:
- 👁️ **Ver detalles**: Abre un modal con la información completa del movimiento.

`[📸 CAPTURA 45: Modal de detalles de movimiento – Información completa del movimiento de inventario]`

- 📄 **PDF General**: Descarga un reporte PDF de todos los movimientos de inventario.

`[📸 CAPTURA 46: PDF de movimientos de bodega – Vista previa del documento descargado]`

---

### 7.6. Creación de Pilas

#### 7.6.1. Pila

**Ruta de acceso:** Sidebar → Creación de Pilas → Pila → Ver Registros / Registrar Pila

Este módulo gestiona las pilas de compostaje.

##### Tarjetas de Estadísticas:
| Tarjeta | Descripción |
|---|---|
| **Total Pilas** | Cantidad total de pilas registradas. |
| **Pilas Activas** | Pilas actualmente en proceso de compostaje. |
| **Completadas** | Pilas que han finalizado el proceso. |
| **Total Ingredientes** | Número total de ingredientes usados en todas las pilas. |

`[📸 CAPTURA 47: Vista principal de Pilas – Tarjetas de estadísticas (Total Pilas, Pilas Activas, Completadas, Total Ingredientes)]`

##### Tabla de Pilas:
| Columna | Descripción |
|---|---|
| **Imagen** | Miniatura de la imagen de la pila (clic para ampliar). |
| **Pila** | Número identificador de la pila (formato: P-001). |
| **Fecha Inicio** | Fecha de inicio del proceso de compostaje. |
| **Fecha Fin** | Fecha de finalización (N/A si aún está en proceso). |
| **Kg Beneficiados** | Kilogramos de abono producidos ("En proceso" si no ha terminado). |
| **Eficiencia** | Porcentaje de eficiencia del proceso. |
| **Ingredientes** | Cantidad de ingredientes asociados a la pila. |
| **Estado** | Estado actual de la pila (Activa, Completada, etc.). |
| **Creado por** | Rol del usuario que creó la pila (Administrador o Aprendiz). |
| **Acciones** | Botones de acción. |

`[📸 CAPTURA 48: Tabla de pilas con DataTables – Registros con miniaturas, badges de estado y porcentaje de eficiencia]`

##### Acciones disponibles:
| Botón | Descripción |
|---|---|
| 👁️ **Ver** | Abre un modal con información general (fechas, Kg, eficiencia) y la lista detallada de ingredientes con sus cantidades y notas. |
| ✏️ **Editar** | Redirige a la página de edición de la pila. |
| 📄 **PDF** | Descarga un reporte PDF individual de la pila. |
| 🗑️ **Eliminar** | Elimina la pila previa confirmación con SweetAlert. |

##### Crear Nueva Pila:
1. Haga clic en **"+ Nueva Pila"** o acceda vía Sidebar → Creación de Pilas → Pila → Registrar Pila.

`[📸 CAPTURA 49: Formulario de creación de nueva pila – Campos de fecha de inicio, imagen, selección de ingredientes con cantidades]`

2. Complete los datos de la pila (fecha de inicio, imagen, ingredientes con sus cantidades).
3. Guarde el registro.

`[📸 CAPTURA 50: Mensaje de éxito (SweetAlert) – "¡Éxito! Pila creada correctamente"]`

##### Ver Detalles de Pila:
1. Haga clic en el ícono de ver (👁️) en la fila de la pila.

`[📸 CAPTURA 51: Modal de ver detalles de pila – Información general (fechas, Kg, eficiencia) y lista de ingredientes con cantidades y notas]`

##### Ver Imagen de Pila:
1. Haga clic en la miniatura de la imagen.

`[📸 CAPTURA 52: Modal de imagen de pila ampliada]`

##### Editar Pila:
1. Haga clic en el ícono de editar (✏️).

`[📸 CAPTURA 53: Página de edición de pila – Campos pre-llenados con datos actuales]`

##### Eliminar Pila:
1. Haga clic en el ícono de eliminar (🗑️).

`[📸 CAPTURA 54: Alerta SweetAlert de confirmación de eliminación – "¿Eliminar Pila?" con botones Sí/Cancelar]`

##### Descargar PDF General:
- Haga clic en el botón rojo **PDF** para descargar un reporte de todas las pilas.

`[📸 CAPTURA 55: PDF individual de una pila – Vista previa del documento]`

`[📸 CAPTURA 56: PDF general de todas las pilas – Vista previa del documento]`

---

#### 7.6.2. Seguimiento (Tracking)

**Ruta de acceso:** Sidebar → Creación de Pilas → Seguimiento → Ver Seguimientos / Nuevo Seguimiento

Este módulo permite llevar un control periódico del estado de cada pila de compostaje.

##### Tarjetas de Estadísticas:
| Tarjeta | Descripción |
|---|---|
| **Total Pilas** | Número total de pilas en el sistema. |
| **Pilas Activas** | Pilas actualmente en proceso. |
| **Total Seguimientos** | Número total de registros de seguimiento. |
| **Seguimientos Hoy** | Registros de seguimiento realizados hoy. |

`[📸 CAPTURA 57: Vista principal de Seguimiento – Tarjetas de estadísticas y lista de pilas con barras de progreso]`

##### Vista de Seguimientos:
Para cada pila se muestra:
- **Barra de progreso** visual del proceso de compostaje.
- **Indicador de estado** (colores según el estado: activa, completada, etc.).
- **Historial de seguimientos** con fechas, parámetros registrados y observaciones.
- **Días faltantes**: Si hay días sin seguimiento, se muestran como "días faltantes" con opción de crear un nuevo registro.

`[📸 CAPTURA 58: Detalle de una pila – Barra de progreso, estado, historial de seguimientos diarios]`

`[📸 CAPTURA 59: Días faltantes – Vista de días sin seguimiento resaltados con opción de registrar]`

##### Crear Nuevo Seguimiento:
1. Haga clic en **"Nuevo Seguimiento"** o acceda vía Sidebar → Seguimiento → Nuevo Seguimiento.

`[📸 CAPTURA 60: Formulario de nuevo seguimiento – Campos de temperatura, humedad, pH, observaciones y selección de pila]`

2. Complete los datos del seguimiento y guarde.

`[📸 CAPTURA 61: Mensaje de éxito (SweetAlert) – "¡Éxito! Seguimiento registrado correctamente"]`

##### Acciones disponibles:
| Botón | Descripción |
|---|---|
| 🖼️ **Imagen** | Ver la imagen de la pila en un modal. |
| 👁️ **Ver detalles** | Modal con la información completa del seguimiento (temperatura, humedad, pH, observaciones). |
| ✏️ **Editar** | Modal para modificar los datos de un seguimiento existente. |
| 📄 **PDF individual** | Descarga el reporte PDF de una pila específica. |
| 📄 **PDF general** | Descarga el reporte PDF de todas las pilas con sus seguimientos. |

`[📸 CAPTURA 62: Modal de ver detalles de seguimiento – Temperatura, humedad, pH, observaciones, fecha]`

`[📸 CAPTURA 63: Modal de edición de seguimiento – Campos pre-llenados con opción de modificar]`

`[📸 CAPTURA 64: Modal de imagen de pila desde seguimiento – Imagen ampliada]`

`[📸 CAPTURA 65: PDF de seguimiento de una pila – Vista previa del documento]`

---

### 7.7. Maquinaria

#### 7.7.1. Identificación y Especificaciones

**Ruta de acceso:** Sidebar → Maquinaria → Identificación y Especificaciones

Gestión de los datos principales de cada equipo o máquina.

`[📸 CAPTURA 66: Vista principal de Identificación y Especificaciones – Tabla de equipos registrados]`

##### Información registrada:
- Nombre del equipo
- Marca y modelo
- Número de serie
- Ubicación
- Estado operativo
- Imagen del equipo
- Especificaciones técnicas

##### Acciones disponibles:
- Crear, ver, editar y eliminar registros de maquinaria.
- Descargar reportes PDF individuales y generales.

##### Crear Nuevo Equipo:
1. Haga clic en **"+ Nuevo Equipo"**.

`[📸 CAPTURA 67: Formulario de creación de nuevo equipo – Campos de nombre, marca, modelo, número de serie, ubicación, imagen]`

2. Complete todos los campos y guarde.

`[📸 CAPTURA 68: Mensaje de éxito (SweetAlert) – "¡Éxito! Equipo registrado correctamente"]`

##### Ver Detalles de Equipo:

`[📸 CAPTURA 69: Vista/Modal de detalles del equipo – Imagen, especificaciones técnicas, estado operativo]`

##### Editar Equipo:

`[📸 CAPTURA 70: Formulario de edición de equipo – Campos pre-llenados]`

##### Eliminar Equipo:

`[📸 CAPTURA 71: Alerta SweetAlert de confirmación de eliminación de equipo]`

---

#### 7.7.2. Datos del Proveedor

**Ruta de acceso:** Sidebar → Maquinaria → Datos del Proveedor

Gestiona la información de proveedores asociados a la maquinaria.

##### Tarjetas de Estadísticas:
| Tarjeta | Descripción |
|---|---|
| **Total Proveedores** | Cantidad total de proveedores registrados. |
| **Registros Hoy** | Proveedores registrados en el día actual. |
| **Este Mes** | Proveedores registrados en el mes actual. |

`[📸 CAPTURA 72: Vista principal de Datos del Proveedor – Tarjetas de estadísticas y tabla de proveedores]`

##### Tabla de Proveedores:
| Columna | Descripción |
|---|---|
| **Imagen** | Imagen del proveedor o equipo. |
| **Maquinaria** | Equipo asociado al proveedor. |
| **Fabricante** | Nombre del fabricante. |
| **Fecha de Compra** | Fecha de adquisición del equipo. |
| **Contacto** | Información de contacto del proveedor. |
| **Acciones** | Botones de acción. |

##### Acciones disponibles:
| Botón | Descripción |
|---|---|
| 👁️ **Ver** | Modal con detalles completos del proveedor. |
| ✏️ **Editar** | Modal para modificar la información del proveedor. |
| 🗑️ **Eliminar** | Elimina el registro previa confirmación. |
| 📄 **PDF** | Descarga un reporte PDF individual. |

##### Crear Nuevo Proveedor:
1. Haga clic en **"+ Nuevo Proveedor"**.

`[📸 CAPTURA 73: Formulario de nuevo proveedor – Campos de maquinaria, fabricante, fecha de compra, contacto, imagen]`

2. Complete todos los campos y guarde.

##### Ver Detalles de Proveedor:

`[📸 CAPTURA 74: Modal de ver detalles del proveedor – Imagen, maquinaria, fabricante, fecha de compra, contacto]`

##### Editar Proveedor:

`[📸 CAPTURA 75: Modal de edición de proveedor – Campos pre-llenados con datos actuales]`

##### Eliminar Proveedor:

`[📸 CAPTURA 76: Alerta SweetAlert de confirmación de eliminación de proveedor]`

##### Descargar PDF:

`[📸 CAPTURA 77: PDF individual de proveedor – Vista previa del documento]`

---

#### 7.7.3. Control de Actividades (Mantenimiento)

**Ruta de acceso:** Sidebar → Maquinaria → Control de Actividades

Registra y controla las actividades de mantenimiento y operación de la maquinaria.

##### Tarjetas de Estadísticas:
| Tarjeta | Descripción |
|---|---|
| **Total Registros** | Cantidad total de actividades registradas. |
| **Mantenimientos** | Cantidad de registros de tipo Mantenimiento. |
| **Operaciones** | Cantidad de registros de tipo Operación. |
| **Este Mes** | Registros del mes actual. |

`[📸 CAPTURA 78: Vista principal de Control de Actividades – Tarjetas de estadísticas y tabla de actividades]`

##### Tabla de Actividades:
| Columna | Descripción |
|---|---|
| **Imagen** | Imagen del equipo o actividad. |
| **Maquinaria** | Equipo al que se le realizó la actividad. |
| **Fecha** | Fecha de la actividad. |
| **Tipo** | Mantenimiento (naranja) u Operación (azul). |
| **Descripción** | Detalle de la actividad realizada. |
| **Responsable** | Persona encargada de la actividad. |
| **Próximo Mantenimiento** | Fecha y cuenta regresiva del próximo mantenimiento programado. |
| **Acciones** | Botones de acción. |

##### Cuenta Regresiva:
- Para cada equipo con mantenimiento programado, se muestra un **temporizador en vivo** que indica cuánto tiempo falta para el siguiente mantenimiento. Esta cuenta regresiva se actualiza automáticamente.

`[📸 CAPTURA 79: Cuenta regresiva de mantenimiento – Temporizador en vivo mostrando días/horas/minutos restantes]`

##### Acciones disponibles:
| Botón | Descripción |
|---|---|
| 👁️ **Ver** | Modal con detalles completos de la actividad. |
| ✏️ **Editar** | Modal para modificar los datos de la actividad. |
| 🗑️ **Eliminar** | Elimina el registro previa confirmación. |
| 📄 **PDF individual** | Descarga un reporte PDF de la actividad. |
| 📄 **PDF general** | Descarga un reporte PDF de todas las actividades. |

##### Crear Nueva Actividad:
1. Haga clic en **"+ Nueva Actividad"**.

`[📸 CAPTURA 80: Formulario de nueva actividad – Campos de maquinaria, tipo (Mantenimiento/Operación), fecha, descripción, responsable]`

2. Complete todos los campos y guarde.

`[📸 CAPTURA 81: Mensaje de éxito (SweetAlert) – "¡Éxito! Actividad registrada correctamente"]`

##### Ver Detalles de Actividad:

`[📸 CAPTURA 82: Modal de ver detalles de actividad – Toda la información de la actividad con imagen]`

##### Editar Actividad:

`[📸 CAPTURA 83: Modal de edición de actividad – Campos pre-llenados]`

##### Eliminar Actividad:

`[📸 CAPTURA 84: Alerta SweetAlert de confirmación de eliminación de actividad]`

##### Descargar PDF:

`[📸 CAPTURA 85: PDF individual de actividad – Vista previa]`

`[📸 CAPTURA 86: PDF general de todas las actividades – Vista previa]`

---

#### 7.7.4. Control de Uso del Equipo

**Ruta de acceso:** Sidebar → Maquinaria → Control de Uso del Equipo

Registra los períodos de uso de cada equipo.

##### Tarjetas de Estadísticas:
| Tarjeta | Descripción |
|---|---|
| **Total Registros** | Cantidad total de registros de uso. |
| **Total Horas** | Sumatoria de horas totales de uso de todos los equipos. |
| **Registros Hoy** | Registros de uso del día actual. |
| **Este Mes** | Registros de uso del mes actual. |

`[📸 CAPTURA 87: Vista principal de Control de Uso – Tarjetas de estadísticas y tabla de registros de uso]`

##### Tabla de Control de Uso:
| Columna | Descripción |
|---|---|
| **Imagen** | Imagen del equipo. |
| **Maquinaria** | Nombre del equipo utilizado. |
| **Fecha/Hora Inicio** | Momento en que se comenzó a usar el equipo. |
| **Fecha/Hora Fin** | Momento en que se terminó de usar el equipo. |
| **Total Horas** | Horas totales de uso (calculado automáticamente). |
| **Responsable** | Persona que utilizó el equipo. |
| **Acciones** | Botones de acción. |

> **Nota:** El sistema calcula automáticamente las horas totales de uso basándose en las fechas/horas de inicio y fin ingresadas.

##### Acciones disponibles:
| Botón | Descripción |
|---|---|
| 👁️ **Ver** | Modal con detalles completos del registro de uso. |
| ✏️ **Editar** | Modal para modificar los datos del registro. |
| 🗑️ **Eliminar** | Elimina el registro previa confirmación. |
| 📄 **PDF individual** | Descarga un reporte PDF del registro. |
| 📄 **PDF general** | Descarga un reporte PDF de todos los registros de uso. |

##### Crear Nuevo Registro de Uso:
1. Haga clic en **"+ Nuevo Registro"**.

`[📸 CAPTURA 88: Formulario de nuevo registro de uso – Campos de maquinaria, fecha/hora inicio, fecha/hora fin, responsable]`

2. Al ingresar las fechas/horas de inicio y fin, el sistema calcula automáticamente las horas totales.

`[📸 CAPTURA 89: Cálculo automático de horas – Campos de inicio y fin llenados mostrando el total de horas calculado]`

3. Complete todos los campos y guarde.

`[📸 CAPTURA 90: Mensaje de éxito (SweetAlert) – "¡Éxito! Registro de uso creado correctamente"]`

##### Ver Detalles de Uso:

`[📸 CAPTURA 91: Modal de ver detalles de uso – Imagen del equipo, horas de uso, responsable]`

##### Editar Registro de Uso:

`[📸 CAPTURA 92: Modal de edición de registro de uso – Campos pre-llenados]`

##### Eliminar Registro de Uso:

`[📸 CAPTURA 93: Alerta SweetAlert de confirmación de eliminación de registro de uso]`

##### Descargar PDF:

`[📸 CAPTURA 94: PDF individual de registro de uso – Vista previa]`

`[📸 CAPTURA 95: PDF general de todos los registros de uso – Vista previa]`

---

### 7.8. Abono Terminado

**Ruta de acceso:** Sidebar → Abono → Listas / Registro

Este módulo gestiona el registro de entregas de abono terminado (producto final del compostaje).

#### Tarjetas de Estadísticas:
| Tarjeta | Descripción |
|---|---|
| **Cantidad Total** | Suma total del abono entregado (en Kg/L). |
| **Total Registros** | Cantidad total de registros de entrega. |
| **Registros Hoy** | Registros realizados en el día actual. |
| **Cantidad Hoy** | Cantidad de abono entregado hoy (en Kg/L). |

`[📸 CAPTURA 96: Vista principal de Abono Terminado – Tarjetas de estadísticas (Cantidad Total, Total Registros, Registros Hoy, Cantidad Hoy)]`

#### Tabla de Registros de Abono:
| Columna | Descripción |
|---|---|
| **ID** | Identificador único (formato: #001). |
| **Fecha** | Fecha de la entrega. |
| **Hora** | Hora de la entrega. |
| **Pila** | Pila de compostaje de la cual proviene el abono (ej: P-001). |
| **Tipo** | Tipo de abono: Líquido (azul) o Sólido (verde). |
| **Cantidad** | Cantidad entregada (en Kg o L según el tipo). |
| **Solicitante** | Persona o entidad que solicita el abono. |
| **Destino** | Lugar de destino del abono. |
| **Recibido Por** | Persona que recibe el abono. |
| **Entregado Por** | Persona que entrega el abono. |
| **Acciones** | Botones de acción. |

`[📸 CAPTURA 97: Tabla de registros de abono con DataTables – Registros con badges de tipo Líquido (azul) y Sólido (verde)]`

#### Acciones disponibles:
| Botón | Descripción |
|---|---|
| 👁️ **Ver** | Modal con detalles completos del registro, incluyendo notas. |
| ✏️ **Editar** | Modal para modificar los datos del registro. La pila asociada no se puede cambiar después de la creación. |
| 🗑️ **Eliminar** | Elimina el registro previa confirmación. |
| 📄 **PDF** | Descarga un reporte PDF individual. |

#### Registrar Nuevo Abono:
1. Haga clic en **"+ Nuevo Registro"** o acceda vía Sidebar → Abono → Registro.

`[📸 CAPTURA 98: Botón "+ Nuevo Registro" resaltado]`

2. Complete el formulario:
   - **Fecha** y **Hora** (obligatorios).
   - **Pila de origen** (seleccionar la pila de compostaje).
   - **Solicitante** (obligatorio).
   - **Destino** (obligatorio).
   - **Quién Recibe** (obligatorio).
   - **Quién Entrega** (obligatorio).
   - **Tipo de Abono**: Líquido o Sólido (la unidad de medida cambia automáticamente: L para Líquido, Kg para Sólido).
   - **Cantidad** (obligatorio, mínimo 0.01).
   - **Notas** (opcional).

`[📸 CAPTURA 99: Formulario de nuevo abono – Todos los campos completos con datos de ejemplo]`

`[📸 CAPTURA 100: Cambio automático de unidad – Mostrar cómo al seleccionar "Líquido" la unidad cambia a "L" y al seleccionar "Sólido" cambia a "Kg"]`

3. Haga clic en **"Guardar"**.

`[📸 CAPTURA 101: Mensaje de éxito (SweetAlert) – "¡Éxito! Abono registrado correctamente"]`

#### Ver Detalles de Abono:
1. Haga clic en el ícono de ver (👁️) en la fila del registro.

`[📸 CAPTURA 102: Modal de ver detalles de abono – Pila, tipo, cantidad, solicitante, destino, notas]`

#### Editar Abono:
1. Haga clic en el ícono de editar (✏️) en la fila del registro.

`[📸 CAPTURA 103: Modal de edición de abono – Campos pre-llenados (pila de origen no editable)]`

#### Eliminar Abono:
1. Haga clic en el ícono de eliminar (🗑️) en la fila del registro.

`[📸 CAPTURA 104: Alerta SweetAlert de confirmación de eliminación de abono]`

#### Descargar PDF General:
- Haga clic en el botón rojo **PDF** para descargar un reporte con todos los registros de abono.

`[📸 CAPTURA 105: PDF individual de abono – Vista previa del documento]`

`[📸 CAPTURA 106: PDF general de todos los registros de abono – Vista previa del documento]`

---

## 8. Notificaciones

**Acceso:** Ícono de campana 🔔 en la barra superior → Ver historial

El sistema de notificaciones permite al administrador gestionar dos tipos de alertas:

### 8.1. Tipos de Notificaciones:

| Tipo | Descripción |
|---|---|
| **Recordatorio de Mantenimiento** 🔧 | El sistema genera alertas automáticas cuando se acerca la fecha de mantenimiento programado de un equipo. |
| **Solicitud de Eliminación** 🗑️ | Un aprendiz solicita la eliminación de un registro (pila de compostaje o residuo orgánico). El administrador debe aprobar o rechazar la solicitud. |

### 8.2. Tarjetas de Estadísticas:
| Tarjeta | Descripción |
|---|---|
| **Total** | Número total de notificaciones. |
| **Pendientes** | Notificaciones que aún no han sido procesadas (amarillo). |
| **Aprobadas** | Solicitudes de eliminación que fueron aprobadas (verde). |
| **Rechazadas** | Solicitudes de eliminación que fueron rechazadas (rojo). |

`[📸 CAPTURA 107: Vista del historial de notificaciones – Tarjetas de estadísticas (Total, Pendientes, Aprobadas, Rechazadas)]`

### 8.3. Tabla de Notificaciones:
| Columna | Descripción |
|---|---|
| **Fecha** | Fecha y hora de la notificación. |
| **Tipo** | Mantenimiento (naranja) o Solicitud de eliminación (amarillo). |
| **Información** | Detalles de la notificación (nombre del equipo, mensaje del recordatorio, o datos del registro a eliminar con nombre del solicitante). |
| **Estado** | Pendiente, Aprobada, Rechazada o Procesada. |
| **Procesado** | Tiempo transcurrido desde que fue procesada. |
| **Acciones** | Botones de acción según el tipo y estado. |

`[📸 CAPTURA 108: Tabla de notificaciones – Registros con badges de tipo (naranja/amarillo) y estado (Pendiente/Aprobada/Rechazada)]`

### 8.4. Acciones por tipo:

**Para Recordatorios de Mantenimiento (pendientes):**
- 🔧 **Registrar Mantenimiento**: Redirige al formulario de creación de mantenimiento.
- ✅ **Marcar como leída**: Marca la notificación como procesada.

`[📸 CAPTURA 109: Notificación de recordatorio de mantenimiento – Botones "Registrar Mantenimiento" y "Marcar como leída" resaltados]`

`[📸 CAPTURA 110: Proceso de marcar como leída – Resultado después de hacer clic (estado cambia a "Procesada")]`

**Para Solicitudes de Eliminación (pendientes):**
- ✅ **Aprobar**: Aprueba la solicitud y elimina el registro solicitado.
- ❌ **Rechazar**: Rechaza la solicitud de eliminación (el registro se mantiene).

`[📸 CAPTURA 111: Notificación de solicitud de eliminación – Información del registro y nombre del solicitante, botones "Aprobar" y "Rechazar" resaltados]`

`[📸 CAPTURA 112: Proceso de aprobar solicitud – Confirmación SweetAlert y resultado (estado cambia a "Aprobada")]`

`[📸 CAPTURA 113: Proceso de rechazar solicitud – Confirmación SweetAlert y resultado (estado cambia a "Rechazada")]`

---

## 9. Generación de Reportes PDF y Excel

El sistema permite generar reportes en formato PDF y Excel desde diferentes módulos:

### Reportes PDF:
| Módulo | Reporte Individual | Reporte General |
|---|---|---|
| **Usuarios** | ✅ PDF por usuario | ✅ PDF de todos los usuarios |
| **Residuos Orgánicos** | ✅ PDF por registro | ✅ PDF de todos los registros |
| **Pilas de Compostaje** | ✅ PDF por pila | ✅ PDF de todas las pilas |
| **Seguimiento** | ✅ PDF por pila | ✅ PDF general |
| **Abono Terminado** | ✅ PDF por registro | ✅ PDF de todos los registros |
| **Proveedores** | ✅ PDF por proveedor | — |
| **Mantenimiento** | ✅ PDF por actividad | ✅ PDF de todas las actividades |
| **Control de Uso** | ✅ PDF por registro | ✅ PDF de todos los registros |
| **Bodega** | — | ✅ PDF de movimientos |

### Reportes desde Monitoreo:
El módulo de Monitoreo permite exportar datos filtrados por período en **PDF** y **Excel** para cada sub-módulo (Residuos, Pilas, Abono, Maquinaria).

---

## 10. Preguntas Frecuentes

**¿Cómo recupero la contraseña de un usuario?**
Como administrador, puede editar cualquier usuario y asignar una nueva contraseña desde el modal de edición en la sección Gestión de Usuarios.

**¿Puedo acceder desde un dispositivo móvil?**
Sí, el sistema es responsivo y se adapta a diferentes tamaños de pantalla, aunque se recomienda una resolución mínima de 1280x720 para una experiencia óptima.

**¿Qué sucede si desactivo a un usuario?**
El usuario no podrá iniciar sesión en el sistema hasta que se reactive. Sus registros históricos se mantienen intactos.

**¿Puedo eliminar una pila de compostaje?**
Sí, como administrador puede eliminar pilas directamente. Los aprendices deben enviar una solicitud de eliminación que usted aprobará o rechazará.

**¿Cómo funcionan los recordatorios de mantenimiento?**
El sistema genera automáticamente notificaciones cuando se acerca la fecha programada del próximo mantenimiento de un equipo. Puede ver estos recordatorios en el ícono de campana y en el historial de notificaciones.

**¿Se puede cambiar la pila asociada a un registro de abono?**
No. Una vez creado el registro de abono, la pila de origen no se puede modificar. Si necesita corregir esta información, elimine el registro y cree uno nuevo.

**¿Qué formato de imagen se acepta?**
Los formatos aceptados son JPEG, PNG, JPG y GIF, con un tamaño máximo de 2MB.

---

## 11. Solución de Problemas

| Problema | Posible Causa | Solución |
|---|---|---|
| No puedo iniciar sesión | Contraseña incorrecta o cuenta desactivada | Verifique las credenciales. Si la cuenta está desactivada, contacte a otro administrador. |
| La tabla no carga los datos | Problema de conexión o JavaScript deshabilitado | Refresque la página (F5). Asegúrese de tener JavaScript habilitado. |
| No se genera el PDF | Timeout del servidor o datos muy extensos | Intente nuevamente. Si persiste, filtre los datos para reducir el volumen. |
| La imagen no se muestra | Formato no soportado o archivo corrupto | Verifique que el archivo sea JPEG, PNG, JPG o GIF y no exceda 2MB. |
| Error 500 al guardar | Fallo del servidor | Verifique que todos los campos obligatorios estén completos e intente nuevamente. Si persiste, contacte al equipo técnico. |
| Las notificaciones no aparecen | No hay notificaciones pendientes o problemas de caché | Refresque la página. Las notificaciones de mantenimiento se generan automáticamente según la configuración. |
| La cuenta regresiva de mantenimiento no se actualiza | Problema de JavaScript | Refresque la página. La cuenta regresiva se actualiza en tiempo real. |

---

## 12. Datos de Contacto

Para soporte técnico o consultas sobre el sistema:

**Equipo de Desarrollo COMPOST CEFA**
- Centro de Formación Agroindustrial (CEFA)
- Horario de atención: lunes a viernes de 8:00 a.m. a 5:00 p.m.
