MANUAL DE USUARIO DEL APRENDIZ
Sistema de Gestión de Compostaje – COMPOST CEFA
Versión: 1.0
Fecha de elaboración: 16/02/2025
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
   7.2. Residuos Orgánicos
   7.3. Bodega de Clasificación
   7.4. Creación de Pilas
       7.4.1. Pila
       7.4.2. Seguimiento
   7.5. Maquinaria
       7.5.1. Identificación y Especificaciones
       7.5.2. Datos del Proveedor
       7.5.3. Control de Actividades (Mantenimiento)
       7.5.4. Control de Uso del Equipo
   7.6. Abono Terminado
8. Sistema de Permisos y Solicitudes de Eliminación
9. Notificaciones
10. Generación de Reportes PDF
11. Preguntas Frecuentes
12. Solución de Problemas
13. Datos de Contacto

---

## 1. Objetivo

El presente manual tiene como objetivo proporcionar una guía completa y detallada sobre el funcionamiento del **Sistema de Gestión de Compostaje – COMPOST CEFA**, dirigido específicamente al usuario con rol de **Aprendiz**. Se describen todos los módulos disponibles, funcionalidades, acciones permitidas, restricciones de permisos y el procedimiento de solicitud de permisos para eliminación de registros.

---

## 2. Alcance

Este manual cubre todas las funcionalidades disponibles para el perfil de **Aprendiz** del sistema, incluyendo:

- Registro y administración de residuos orgánicos (propios).
- Consulta de la bodega de clasificación (inventario y movimientos).
- Creación y seguimiento de pilas de compostaje.
- Gestión de maquinaria (especificaciones, proveedores, mantenimiento y uso).
- Registro y control de abono terminado.
- **Sistema de permisos para eliminación de registros** (solicitudes al administrador).
- Gestión de notificaciones (respuestas a solicitudes y recordatorios de mantenimiento).
- Generación de reportes PDF.

> **Importante:** El rol de Aprendiz tiene restricciones de permisos. **No tiene acceso** a los módulos de Gestión de Usuarios ni Monitoreo. Además, solo puede editar y solicitar eliminación de los registros que haya creado personalmente.

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
| **Solicitud de Eliminación** | Petición que el aprendiz envía al administrador para poder eliminar un registro. |
| **DataTables** | Componente de tablas interactivas que permite buscar, ordenar y paginar registros. |
| **SweetAlert** | Componente de alertas interactivas para confirmaciones y mensajes del sistema. |
| **PDF** | Formato de documento portable para la generación de reportes descargables. |

---

## 4. Introducción al Sistema

**COMPOST CEFA** es una plataforma web desarrollada para automatizar y gestionar el proceso integral de compostaje del Centro de Formación Agroindustrial. Como aprendiz, usted participa activamente en el registro de datos del proceso de compostaje, desde la recepción de residuos hasta la entrega del abono terminado.

### Características Principales para el Aprendiz:
- **Registro de información**: Crear nuevos registros en todos los módulos operativos.
- **Edición de registros propios**: Modificar únicamente los registros que usted haya creado.
- **Sistema de permisos**: Solicitar autorización al administrador para eliminar registros propios.
- **Visualización completa**: Ver todos los registros del sistema (de todos los usuarios).
- **Generación de reportes**: Exportación a PDF para documentación.
- **Notificaciones**: Alertas de mantenimiento y respuestas a solicitudes de permisos.
- **Interfaz responsiva**: Diseño adaptable a diferentes dispositivos.

### Requisitos del Sistema:
- Navegador web moderno (Google Chrome, Mozilla Firefox, Microsoft Edge).
- Conexión a Internet estable.
- Resolución de pantalla mínima recomendada: 1280 x 720 píxeles.

---

## 5. Acceso al Sistema (Inicio de Sesión)

Para acceder al sistema como Aprendiz:

1. Abra su navegador web e ingrese la URL del sistema.

`[📸 CAPTURA 1: Pantalla de inicio de sesión – Formulario con campos de correo electrónico y contraseña]`

2. En la página de inicio de sesión, ingrese su **correo electrónico** y **contraseña**.
3. Haga clic en el botón **"Iniciar Sesión"**.
4. Si las credenciales son correctas, será redirigido al **Dashboard** del Panel de Aprendiz.

`[📸 CAPTURA 2: Redirección exitosa al Dashboard del Panel de Aprendiz después de iniciar sesión]`

> **Nota:** Si olvidó su contraseña, contacte al administrador del sistema para restablecerla. Si su cuenta está desactivada, no podrá acceder al sistema.

---

## 6. Estructura de Navegación

Al ingresar al sistema, el aprendiz encontrará una interfaz dividida en tres secciones principales:

`[📸 CAPTURA 3: Vista general de la interfaz del Aprendiz – Señalar las 3 secciones: Sidebar, Barra Superior y Área de Contenido]`

### 6.1. Barra Lateral (Sidebar)
Ubicada en el lado izquierdo, contiene el menú de navegación con los módulos disponibles para el aprendiz:

| Icono | Módulo | Descripción |
|---|---|---|
| 🌐 | **Dashboard** | Panel principal con resumen general. |
| ♻️ | **Residuos** | Gestión de residuos orgánicos (Ver Registros / Registrar Nuevo). |
| 🏭 | **Bodega** | Inventario de la bodega de clasificación. |
| ⛰️ | **Creación de Pilas** | Gestión de pilas y seguimiento. |
| ⚙️ | **Maquinaria** | Equipos, proveedores, mantenimiento y uso. |
| 🌱 | **Abono** | Registro y control de abono terminado. |

`[📸 CAPTURA 4: Barra lateral del Aprendiz – Todos los módulos visibles]`

> **Nota:** A diferencia del Administrador, el Aprendiz **NO** tiene acceso a los módulos de **Gestión de Usuarios** ni **Monitoreo**.

Algunos módulos tienen **submenús desplegables** que se abren al hacer clic en el nombre del módulo.

`[📸 CAPTURA 5: Sidebar con submenús desplegados – Mostrar menús expandidos de Residuos, Creación de Pilas, Maquinaria y Abono]`

### 6.2. Barra Superior (Header)
Ubicada en la parte superior, muestra:
- El título **"Panel de Aprendiz"**.
- Un **ícono de campana** 🔔 para acceder a las notificaciones (con badge rojo indicando la cantidad de notificaciones pendientes).
- Un **menú de usuario** con el nombre del aprendiz, la etiqueta "Aprendiz" y opciones de perfil y cerrar sesión.

`[📸 CAPTURA 6: Barra superior del Aprendiz – Señalar el título "Panel de Aprendiz", ícono de campana con badge y menú de usuario]`

`[📸 CAPTURA 7: Menú desplegable de notificaciones del Aprendiz – Mostrando recordatorios de mantenimiento y respuestas a solicitudes]`

`[📸 CAPTURA 8: Menú desplegable de usuario – Nombre, email, opciones de Perfil, Welcome y Cerrar Sesión]`

### 6.3. Área de Contenido
Espacio central donde se muestra el contenido del módulo seleccionado.

---

## 7. Módulos del Sistema

---

### 7.1. Dashboard

**Ruta de acceso:** Sidebar → Dashboard

El Dashboard es la pantalla principal del sistema y muestra un resumen general con las estadísticas más relevantes de los módulos disponibles para el aprendiz.

- **Tarjetas de estadísticas** con datos clave de cada módulo.
- Se actualiza cada vez que se accede a él.
- Muestra la fecha actual (zona horaria: América/Bogotá).

`[📸 CAPTURA 9: Vista completa del Dashboard del Aprendiz – Todas las tarjetas de estadísticas visibles]`

---

### 7.2. Residuos Orgánicos

**Ruta de acceso:** Sidebar → Residuos → Ver Registros / Registrar Nuevo

Este módulo gestiona el registro de todos los residuos orgánicos que ingresan al sistema.

#### Tarjetas de Estadísticas:
| Tarjeta | Descripción |
|---|---|
| **Peso Total** | Suma total del peso de todos los residuos registrados (en Kg). |
| **Total Registros** | Cantidad total de registros de residuos. |
| **Registros Hoy** | Cantidad de registros realizados en el día actual. |
| **Peso Hoy** | Peso total de los residuos registrados hoy (en Kg). |

`[📸 CAPTURA 10: Vista principal de Residuos Orgánicos del Aprendiz – Tarjetas de estadísticas]`

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
| **Acciones** | Botones de acción (varían según permisos). |

`[📸 CAPTURA 11: Tabla de residuos orgánicos – Registros con miniaturas, badges de tipo y columna de acciones]`

#### Restricciones de Permisos en Acciones:

> **⚠️ IMPORTANTE:** Las acciones disponibles dependen de si usted es el creador del registro:

**Si USTED creó el registro:**
| Botón | Descripción |
|---|---|
| 👁️ **Ver** | Abre un modal con los detalles completos del residuo. |
| ✏️ **Editar** | Abre un modal para modificar los datos del registro. |
| 🗑️ **Solicitar Eliminación** | Envía una solicitud al administrador para autorizar la eliminación. |
| 📄 **PDF** | Descarga un reporte PDF individual del registro. |

**Si OTRO usuario creó el registro:**
| Botón | Descripción |
|---|---|
| 👁️ **Ver** | Abre un modal con los detalles completos del residuo. |
| 🔒 **Editar** | Bloqueado – No puede editar registros de otros usuarios. |
| 🔒 **Eliminar** | Bloqueado – No puede eliminar registros de otros usuarios. |
| 📄 **PDF** | Descarga un reporte PDF individual del registro. |

`[📸 CAPTURA 12: Acciones en registro propio – Botones de ver, editar, solicitar eliminación y PDF visibles]`

`[📸 CAPTURA 13: Acciones en registro ajeno – Botones de editar y eliminar aparecen con candado (🔒) gris bloqueado]`

#### Estados de la Solicitud de Eliminación:
En la columna de acciones, el ícono de eliminar cambia según el estado de la solicitud:

| Ícono | Estado | Descripción |
|---|---|---|
| 🗑️ (rojo) | **Sin solicitud** | Puede enviar una solicitud de eliminación. |
| ⏳ (amarillo) | **Pendiente** | La solicitud fue enviada y espera la respuesta del administrador. |
| ✅🗑️ (rojo) | **Aprobada** | El administrador aprobó la eliminación. Haga clic para eliminar. |
| 🚫 (rojo) | **Rechazada** | El administrador rechazó la solicitud. No se puede eliminar. |

`[📸 CAPTURA 14: Ícono de estado "Pendiente" (⏳ amarillo) – Solicitud enviada al administrador]`

`[📸 CAPTURA 15: Ícono de estado "Aprobada" – El botón de eliminar está habilitado para proceder]`

`[📸 CAPTURA 16: Ícono de estado "Rechazada" (🚫 rojo) – Alerta mostrando que fue rechazada al hacer clic]`

#### Proceso de Solicitud de Eliminación (Paso a Paso):
1. Localice el registro que desea eliminar en la tabla (solo registros creados por usted).
2. Haga clic en el ícono de eliminar 🗑️ (rojo).

`[📸 CAPTURA 17: Alerta SweetAlert de confirmación – "¿Solicitar permiso para eliminar?" con botones Sí, solicitar / Cancelar]`

3. Se mostrará una alerta de confirmación. Haga clic en **"Sí, solicitar"**.

`[📸 CAPTURA 18: Mensaje de éxito – "Solicitud enviada. Se ha enviado la solicitud de eliminación al administrador."]`

4. El ícono cambiará a ⏳ (reloj amarillo) indicando que la solicitud está **pendiente**.
5. Espere la respuesta del administrador. Recibirá una **notificación** cuando el administrador apruebe o rechace.
6. Si fue **aprobada**: el ícono cambiará a un botón de eliminar habilitado. Haga clic para eliminar definitivamente.

`[📸 CAPTURA 19: Alerta SweetAlert de confirmación final de eliminación – "El administrador aprobó. ¿Eliminar definitivamente?" con botones Sí / Cancelar]`

`[📸 CAPTURA 20: Mensaje de éxito – "¡Éxito! Registro eliminado correctamente"]`

7. Si fue **rechazada**: al hacer clic en el ícono 🚫, se mostrará una alerta indicando que la solicitud fue rechazada.

`[📸 CAPTURA 21: Alerta al hacer clic en solicitud rechazada – "Solicitud rechazada. Tu solicitud para eliminar este registro fue rechazada por el administrador."]`

#### Registrar Nuevo Residuo:
1. Haga clic en el botón **"+ Nuevo Registro"** o acceda vía Sidebar → Residuos → Registrar Nuevo.

`[📸 CAPTURA 22: Botón "+ Nuevo Registro" resaltado en la esquina superior derecha]`

2. Complete el formulario:
   - **Fecha** (obligatorio, por defecto la fecha actual).
   - **Tipo de Residuo** (seleccionar de la lista desplegable, obligatorio).
   - **Peso en Kg** (obligatorio, mínimo 0.01).
   - **Entregado Por** (nombre, obligatorio).
   - **Recibido Por** (nombre, obligatorio, por defecto el nombre del aprendiz).
   - **Imagen** (obligatoria, tamaño máximo: 2MB, formatos: JPEG, PNG, JPG, GIF).
   - **Notas** (opcional).

`[📸 CAPTURA 23: Formulario de nuevo residuo – Todos los campos completos con datos de ejemplo y vista previa de imagen]`

3. Haga clic en **"Guardar Registro"**.

`[📸 CAPTURA 24: Mensaje de éxito (SweetAlert) – "¡Éxito! Residuo registrado correctamente"]`

#### Ver Detalles de Residuo:
1. Haga clic en el ícono de ver (👁️) en la fila del residuo.

`[📸 CAPTURA 25: Modal de ver detalles del residuo – Imagen, tipo, peso, entregado por, recibido por, notas, creador]`

#### Editar Residuo (solo propios):
1. Haga clic en el ícono de editar (✏️) en la fila de su registro.

`[📸 CAPTURA 26: Modal de edición de residuo – Campos pre-llenados con datos actuales, opción de cambiar imagen]`

2. Modifique los campos necesarios y haga clic en **"Actualizar Registro"**.

`[📸 CAPTURA 27: Mensaje de éxito – "¡Éxito! Residuo actualizado correctamente"]`

#### Intento de Editar/Eliminar Registro Ajeno:
1. Al hacer clic en el candado (🔒) de un registro que no es suyo:

`[📸 CAPTURA 28: Alerta de permisos – "No tienes permiso para editar/eliminar registros de otros usuarios"]`

#### Descargar PDF:
- Haga clic en el botón rojo **PDF** (📄) en cada registro para descargar un PDF individual.
- Haga clic en el botón rojo **PDF general** para descargar un reporte de todos los residuos.

`[📸 CAPTURA 29: PDF individual de un residuo – Vista previa del documento]`

`[📸 CAPTURA 30: PDF general de todos los residuos – Vista previa del documento]`

---

### 7.3. Bodega de Clasificación

**Ruta de acceso:** Sidebar → Bodega → Inventario

La bodega muestra el inventario clasificado de residuos orgánicos almacenados.

#### Tarjetas de Inventario:
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

`[📸 CAPTURA 31: Vista principal de Bodega del Aprendiz – Tarjetas de inventario por tipo de residuo]`

Cada tarjeta de tipo es clicable y lleva a una **vista detallada** de los movimientos de ese tipo específico.

`[📸 CAPTURA 32: Vista detallada de un tipo – Ejemplo: clic en tarjeta "Cocina" mostrando movimientos de ese tipo]`

#### Movimientos Recientes:
| Columna | Descripción |
|---|---|
| **Tipo de Movimiento** | Entrada o Salida de inventario. |
| **Peso (Kg)** | Cantidad del movimiento. |
| **Procesado por** | Usuario que realizó el movimiento. |
| **Acciones** | Ver detalles del movimiento. |

`[📸 CAPTURA 33: Tabla de movimientos recientes de la bodega]`

#### Acciones disponibles:
- 👁️ **Ver detalles**: Abre un modal con la información completa del movimiento.

`[📸 CAPTURA 34: Modal de detalles de movimiento de bodega]`

---

### 7.4. Creación de Pilas

#### 7.4.1. Pila

**Ruta de acceso:** Sidebar → Creación de Pilas → Pila → Ver Registros / Registrar Pila

Este módulo gestiona las pilas de compostaje.

##### Tarjetas de Estadísticas:
| Tarjeta | Descripción |
|---|---|
| **Total Pilas** | Cantidad total de pilas registradas. |
| **Pilas Activas** | Pilas actualmente en proceso. |
| **Completadas** | Pilas que han finalizado el proceso. |
| **Total Ingredientes** | Número total de ingredientes usados. |

`[📸 CAPTURA 35: Vista principal de Pilas del Aprendiz – Tarjetas de estadísticas]`

##### Tabla de Pilas:
| Columna | Descripción |
|---|---|
| **Imagen** | Miniatura de la imagen de la pila. |
| **Pila** | Número identificador (formato: P-001). |
| **Fecha Inicio** | Fecha de inicio del compostaje. |
| **Fecha Fin** | Fecha de finalización (N/A si en proceso). |
| **Kg Beneficiados** | Kilogramos producidos. |
| **Eficiencia** | Porcentaje de eficiencia. |
| **Ingredientes** | Cantidad de ingredientes. |
| **Estado** | Activa, Completada, etc. |
| **Creado por** | Rol del creador. |
| **Acciones** | Botones de acción (varían según permisos). |

`[📸 CAPTURA 36: Tabla de pilas – Registros con badges de estado, permisos diferenciados en acciones]`

##### Restricciones de Permisos:
- **Solo puede editar y solicitar eliminación de pilas creadas por usted.**
- Los registros de otros usuarios muestran un candado (🔒) en los botones de editar y eliminar.
- El proceso de solicitud de eliminación es **idéntico** al descrito en la sección 8.

`[📸 CAPTURA 37: Acciones en pila propia – Botones de ver, editar, solicitar eliminación y PDF]`

`[📸 CAPTURA 38: Acciones en pila ajena – Botones bloqueados con candado (🔒)]`

##### Crear Nueva Pila:
1. Haga clic en **"+ Nueva Pila"** o acceda vía Sidebar → Pila → Registrar Pila.

`[📸 CAPTURA 39: Formulario de creación de nueva pila – Campos de fecha, imagen, ingredientes]`

2. Complete los datos de la pila y guarde.

`[📸 CAPTURA 40: Mensaje de éxito – "¡Éxito! Pila creada correctamente"]`

##### Ver Detalles de Pila:

`[📸 CAPTURA 41: Modal de detalles de pila – Información general e ingredientes]`

##### Editar Pila (solo propia):

`[📸 CAPTURA 42: Página de edición de pila – Campos pre-llenados]`

##### Descargar PDF:

`[📸 CAPTURA 43: PDF individual de una pila – Vista previa]`

`[📸 CAPTURA 44: PDF general de todas las pilas – Vista previa]`

---

#### 7.4.2. Seguimiento (Tracking)

**Ruta de acceso:** Sidebar → Creación de Pilas → Seguimiento → Ver Seguimientos / Nuevo Seguimiento

Control periódico del estado de cada pila de compostaje.

##### Tarjetas de Estadísticas:
| Tarjeta | Descripción |
|---|---|
| **Total Pilas** | Número total de pilas. |
| **Pilas Activas** | Pilas en proceso. |
| **Total Seguimientos** | Total de registros de seguimiento. |
| **Seguimientos Hoy** | Registros realizados hoy. |

`[📸 CAPTURA 45: Vista principal de Seguimiento del Aprendiz – Tarjetas y lista de pilas con barras de progreso]`

##### Vista de Seguimientos:
- **Barra de progreso** visual del compostaje.
- **Indicador de estado** (colores según estado).
- **Historial de seguimientos** con parámetros.
- **Días faltantes** con opción de crear nuevo registro.

`[📸 CAPTURA 46: Detalle de una pila – Barra de progreso, historial de seguimientos]`

`[📸 CAPTURA 47: Días faltantes – Días sin seguimiento resaltados con opción de registrar]`

##### Crear Nuevo Seguimiento:
1. Haga clic en **"Nuevo Seguimiento"**.

`[📸 CAPTURA 48: Formulario de nuevo seguimiento – Campos de temperatura, humedad, pH, observaciones]`

2. Complete los datos y guarde.

`[📸 CAPTURA 49: Mensaje de éxito – "¡Éxito! Seguimiento registrado correctamente"]`

##### Acciones disponibles:
| Botón | Descripción |
|---|---|
| 🖼️ **Imagen** | Ver imagen de la pila. |
| 👁️ **Ver detalles** | Modal con información completa del seguimiento. |
| ✏️ **Editar** | Modificar seguimiento (solo propios). |
| 📄 **PDF** | Descargar reporte PDF. |

`[📸 CAPTURA 50: Modal de detalles de seguimiento – Temperatura, humedad, pH, observaciones]`

`[📸 CAPTURA 51: Modal de edición de seguimiento – Campos pre-llenados]`

`[📸 CAPTURA 52: PDF de seguimiento – Vista previa]`

---

### 7.5. Maquinaria

#### 7.5.1. Identificación y Especificaciones

**Ruta de acceso:** Sidebar → Maquinaria → Identificación y Especificaciones

Gestión de los datos principales de cada equipo.

`[📸 CAPTURA 53: Vista principal de Maquinaria del Aprendiz – Tabla de equipos]`

##### Acciones (con restricciones):
- **Ver, Crear y PDF**: Disponibles para todos los registros.
- **Editar y Solicitar Eliminación**: Solo en registros creados por usted.
- **Registros ajenos**: Botones bloqueados con candado (🔒).

`[📸 CAPTURA 54: Formulario de nueva maquinaria – Campos de nombre, marca, modelo, serie, ubicación, imagen]`

`[📸 CAPTURA 55: Modal de detalles de maquinaria – Especificaciones técnicas]`

`[📸 CAPTURA 56: Acciones en registro propio vs. registro ajeno con candado]`

---

#### 7.5.2. Datos del Proveedor

**Ruta de acceso:** Sidebar → Maquinaria → Datos del Proveedor

Gestiona la información de proveedores asociados a la maquinaria.

##### Tarjetas de Estadísticas:
| Tarjeta | Descripción |
|---|---|
| **Total Proveedores** | Cantidad total de proveedores. |
| **Registros Hoy** | Proveedores registrados hoy. |
| **Este Mes** | Proveedores del mes actual. |

`[📸 CAPTURA 57: Vista principal de Proveedores del Aprendiz – Tarjetas y tabla]`

##### Acciones (con restricciones):
- Aplican las mismas restricciones: solo editar/solicitar eliminación de registros propios.

`[📸 CAPTURA 58: Formulario de nuevo proveedor – Campos completos]`

`[📸 CAPTURA 59: Modal de detalles de proveedor]`

`[📸 CAPTURA 60: Solicitud de eliminación de proveedor – SweetAlert de confirmación]`

---

#### 7.5.3. Control de Actividades (Mantenimiento)

**Ruta de acceso:** Sidebar → Maquinaria → Control de Actividades

Registro de actividades de mantenimiento y operación.

##### Tarjetas de Estadísticas:
| Tarjeta | Descripción |
|---|---|
| **Total Registros** | Total de actividades. |
| **Mantenimientos** | Registros de tipo Mantenimiento. |
| **Operaciones** | Registros de tipo Operación. |
| **Este Mes** | Registros del mes actual. |

`[📸 CAPTURA 61: Vista principal de Control de Actividades del Aprendiz – Tarjetas y tabla]`

##### Tabla de Actividades:
| Columna | Descripción |
|---|---|
| **Imagen** | Imagen del equipo o actividad. |
| **Maquinaria** | Equipo al que se le realizó la actividad. |
| **Fecha** | Fecha de la actividad. |
| **Tipo** | Mantenimiento (naranja) u Operación (azul). |
| **Descripción** | Detalle de la actividad. |
| **Responsable** | Persona encargada. |
| **Próximo Mantenimiento** | Fecha y cuenta regresiva. |
| **Acciones** | Botones (con restricciones). |

##### Cuenta Regresiva:
- Temporizador en vivo para el próximo mantenimiento programado.

`[📸 CAPTURA 62: Cuenta regresiva de mantenimiento – Temporizador en vivo]`

##### Acciones (con restricciones):

`[📸 CAPTURA 63: Formulario de nueva actividad – Campos de maquinaria, tipo, fecha, descripción, responsable]`

`[📸 CAPTURA 64: Modal de detalles de actividad]`

`[📸 CAPTURA 65: Solicitud de eliminación de actividad – SweetAlert]`

---

#### 7.5.4. Control de Uso del Equipo

**Ruta de acceso:** Sidebar → Maquinaria → Control de Uso del Equipo

Registro de períodos de uso de cada equipo.

##### Tarjetas de Estadísticas:
| Tarjeta | Descripción |
|---|---|
| **Total Registros** | Total de registros de uso. |
| **Total Horas** | Sumatoria de horas de uso. |
| **Registros Hoy** | Registros del día actual. |
| **Este Mes** | Registros del mes actual. |

`[📸 CAPTURA 66: Vista principal de Control de Uso del Aprendiz – Tarjetas y tabla]`

##### Tabla de Control de Uso:
| Columna | Descripción |
|---|---|
| **Imagen** | Imagen del equipo. |
| **Maquinaria** | Nombre del equipo. |
| **Fecha/Hora Inicio** | Inicio del uso. |
| **Fecha/Hora Fin** | Fin del uso. |
| **Total Horas** | Calculado automáticamente. |
| **Responsable** | Persona que utilizó el equipo. |
| **Acciones** | Botones (con restricciones). |

> **Nota:** El sistema calcula automáticamente las horas totales basándose en las fechas/horas de inicio y fin.

`[📸 CAPTURA 67: Formulario de nuevo registro de uso – Campos con cálculo automático de horas]`

`[📸 CAPTURA 68: Cálculo automático de horas – Inicio y fin llenados mostrando total calculado]`

`[📸 CAPTURA 69: Modal de detalles de uso]`

`[📸 CAPTURA 70: PDF de registro de uso – Vista previa]`

---

### 7.6. Abono Terminado

**Ruta de acceso:** Sidebar → Abono → Listas / Registro

Registro de entregas de abono terminado (producto final).

#### Tarjetas de Estadísticas:
| Tarjeta | Descripción |
|---|---|
| **Cantidad Total** | Suma total del abono entregado (Kg/L). |
| **Total Registros** | Total de registros de entrega. |
| **Registros Hoy** | Registros del día actual. |
| **Cantidad Hoy** | Abono entregado hoy (Kg/L). |

`[📸 CAPTURA 71: Vista principal de Abono del Aprendiz – Tarjetas de estadísticas]`

#### Tabla de Registros:
| Columna | Descripción |
|---|---|
| **ID** | Identificador único (#001). |
| **Fecha** | Fecha de entrega. |
| **Hora** | Hora de entrega. |
| **Pila** | Pila de origen (P-001). |
| **Tipo** | Líquido (azul) o Sólido (verde). |
| **Cantidad** | Cantidad entregada (Kg o L). |
| **Solicitante** | Persona que solicita. |
| **Destino** | Lugar de destino. |
| **Recibido Por** | Persona que recibe. |
| **Entregado Por** | Persona que entrega. |
| **Acciones** | Botones (con restricciones). |

`[📸 CAPTURA 72: Tabla de abono – Registros con badges Líquido/Sólido, acciones diferenciadas]`

#### Restricciones de Permisos:
- Aplican las mismas restricciones: solo editar/solicitar eliminación de registros propios.

#### Registrar Nuevo Abono:
1. Haga clic en **"+ Nuevo Registro"** o acceda vía Sidebar → Abono → Registro.

`[📸 CAPTURA 73: Formulario de nuevo abono – Todos los campos con datos de ejemplo]`

2. Complete el formulario:
   - **Fecha** y **Hora** (obligatorios).
   - **Pila de origen** (seleccionar la pila).
   - **Solicitante** (obligatorio).
   - **Destino** (obligatorio).
   - **Quién Recibe** y **Quién Entrega** (obligatorios).
   - **Tipo de Abono**: Líquido o Sólido (la unidad cambia automáticamente: L para Líquido, Kg para Sólido).
   - **Cantidad** (obligatorio, mínimo 0.01).
   - **Notas** (opcional).

`[📸 CAPTURA 74: Cambio automático de unidad – Seleccionar "Líquido" la unidad cambia a "L", "Sólido" cambia a "Kg"]`

3. Haga clic en **"Guardar"**.

`[📸 CAPTURA 75: Mensaje de éxito – "¡Éxito! Abono registrado correctamente"]`

#### Ver Detalles:

`[📸 CAPTURA 76: Modal de detalles de abono – Pila, tipo, cantidad, notas]`

#### Editar (solo propio):

`[📸 CAPTURA 77: Modal de edición de abono – Campos pre-llenados (pila no editable)]`

#### Descargar PDF:

`[📸 CAPTURA 78: PDF individual de abono – Vista previa]`

`[📸 CAPTURA 79: PDF general de todos los registros de abono – Vista previa]`

---

## 8. Sistema de Permisos y Solicitudes de Eliminación

Como aprendiz, usted **NO puede eliminar registros directamente**. Debe solicitar autorización al administrador. Este sistema aplica a **TODOS los módulos** del sistema.

### 8.1. Flujo del Sistema de Permisos:

```
┌─────────────┐    ┌──────────────┐    ┌───────────────────┐    ┌────────────────┐
│ 1. Aprendiz │───▶│ 2. Solicitud │───▶│ 3. Administrador  │───▶│ 4. Resultado   │
│ solicita    │    │ PENDIENTE ⏳ │    │ revisa y decide   │    │ APROBADA ✅    │
│ eliminar    │    │              │    │                   │    │ o RECHAZADA ❌ │
└─────────────┘    └──────────────┘    └───────────────────┘    └────────────────┘
```

`[📸 CAPTURA 80: Diagrama visual del flujo de permisos en la interfaz – Desde la solicitud hasta la respuesta]`

### 8.2. Estados de la Solicitud:

| Estado | Ícono | Color | Descripción | Acción disponible |
|---|---|---|---|---|
| **Sin solicitud** | 🗑️ | Rojo | No se ha enviado solicitud. | Hacer clic para enviar solicitud. |
| **Pendiente** | ⏳ | Amarillo | Esperando respuesta del administrador. | Ninguna (esperar). |
| **Aprobada** | 🗑️ | Rojo | El administrador aprobó la eliminación. | Hacer clic para eliminar definitivamente. |
| **Rechazada** | 🚫 | Rojo | El administrador rechazó la solicitud. | Ver motivo (clic muestra alerta). |

### 8.3. Módulos donde aplica el sistema de permisos:

| Módulo | Tipo de registro |
|---|---|
| **Residuos Orgánicos** | Registros de residuos orgánicos. |
| **Pilas de Compostaje** | Pilas de compostaje. |
| **Maquinaria** | Registros de equipos. |
| **Proveedores** | Registros de proveedores. |
| **Control de Actividades** | Actividades de mantenimiento/operación. |
| **Control de Uso** | Registros de uso de equipos. |
| **Abono Terminado** | Registros de entrega de abono. |

### 8.4. Paso a Paso General (aplica a todos los módulos):

1. **Ubicar el registro** que desea eliminar (debe ser creado por usted).
2. Haga clic en el **ícono de eliminar** 🗑️.

`[📸 CAPTURA 81: SweetAlert de solicitud – "¿Solicitar permiso para eliminar este registro?" con botones Sí, solicitar / Cancelar]`

3. Confirme la solicitud haciendo clic en **"Sí, solicitar"**.

`[📸 CAPTURA 82: Mensaje de éxito – "Solicitud enviada al administrador"]`

4. El ícono cambiará a ⏳ (pendiente).

`[📸 CAPTURA 83: Antes y después – El ícono cambia de 🗑️ rojo a ⏳ amarillo]`

5. **Espere la notificación** del administrador.

6. **Si fue APROBADA:**
   - Recibirá una notificación (campana 🔔 + badge).
   - El ícono cambiará a 🗑️ habilitado.
   - Haga clic para eliminar definitivamente.

`[📸 CAPTURA 84: Notificación de aprobación en la campana – "Solicitud Aprobada"]`

`[📸 CAPTURA 85: Eliminación final – SweetAlert "¿Eliminar definitivamente?" con confirmación]`

7. **Si fue RECHAZADA:**
   - Recibirá una notificación indicando el rechazo.
   - El ícono cambiará a 🚫.
   - Al hacer clic, verá un mensaje de rechazo.

`[📸 CAPTURA 86: Notificación de rechazo en la campana – "Solicitud Rechazada"]`

`[📸 CAPTURA 87: Alerta al hacer clic en 🚫 – "Tu solicitud fue rechazada por el administrador"]`

---

## 9. Notificaciones

**Acceso:** Ícono de campana 🔔 en la barra superior → Ver historial

El sistema de notificaciones del aprendiz gestiona dos tipos de alertas:

### 9.1. Tipos de Notificaciones:

| Tipo | Descripción |
|---|---|
| **Recordatorio de Mantenimiento** 🔧 | Alertas automáticas cuando se acerca la fecha de mantenimiento de un equipo. |
| **Respuesta a Solicitud de Eliminación** | Notificación cuando el administrador aprueba o rechaza una solicitud de eliminación. |

### 9.2. Notificaciones en la Campana:
Al hacer clic en la campana 🔔, se muestra un desplegable con las notificaciones pendientes:

**Para Recordatorios de Mantenimiento:**
- Muestra el nombre de la maquinaria y el mensaje del recordatorio.
- Botón **"Marcar como leída"** para cerrar la notificación.

`[📸 CAPTURA 88: Desplegable de campana – Recordatorio de mantenimiento con botón "Marcar como leída"]`

**Para Respuestas a Solicitudes:**
- Muestra si fue **Aprobada** (ícono verde ✅) o **Rechazada** (ícono rojo ❌).
- Indica el tipo y número de registro (ej: "Pila de compostaje #P-001").
- Botón **"Marcar como leída"**.
- Si fue aprobada, incluye enlace **"Ver registros"** para ir al módulo correspondiente.

`[📸 CAPTURA 89: Desplegable de campana – Solicitud Aprobada con botones "Marcar como leída" y "Ver registros"]`

`[📸 CAPTURA 90: Desplegable de campana – Solicitud Rechazada con botón "Marcar como leída"]`

### 9.3. Alerta Emergente de Mantenimiento:
Cuando existen recordatorios de mantenimiento sin leer, al ingresar a cualquier página se mostrará automáticamente una **alerta SweetAlert** indicando:

> "Tiene recordatorios de mantenimiento sin leer. La información se encuentra en Notificaciones."

`[📸 CAPTURA 91: Alerta emergente de mantenimiento – SweetAlert con temporizador de 15 segundos]`

### 9.4. Historial de Solicitudes:

**Ruta de acceso:** Campana → Ver historial

El historial muestra todas las solicitudes de permisos enviadas por el aprendiz.

#### Tarjetas de Estadísticas:
| Tarjeta | Descripción |
|---|---|
| **Total** | Número total de solicitudes enviadas. |
| **Pendientes** | Solicitudes que esperan respuesta (amarillo). |
| **Aprobadas** | Solicitudes aprobadas (verde). |
| **Rechazadas** | Solicitudes rechazadas (rojo). |

`[📸 CAPTURA 92: Vista del historial de solicitudes – Tarjetas de estadísticas (Total, Pendientes, Aprobadas, Rechazadas)]`

#### Tabla del Historial:
| Columna | Descripción |
|---|---|
| **Fecha Solicitud** | Fecha y hora en que se envió la solicitud. |
| **Registro** | ID y detalle del registro (tipo y peso del residuo). |
| **Estado** | Pendiente (amarillo), Aprobada (verde) o Rechazada (rojo). |
| **Respuesta** | Fecha/hora de la respuesta del administrador ("Esperando respuesta" si pendiente). |
| **Leída** | Si la respuesta ha sido marcada como leída (Leída/No leída). |

`[📸 CAPTURA 93: Tabla del historial completo – Solicitudes con badges de estado y marca de lectura]`

---

## 10. Generación de Reportes PDF

El aprendiz puede generar reportes en formato PDF desde los diferentes módulos:

### Reportes PDF Disponibles:
| Módulo | Reporte Individual | Reporte General |
|---|---|---|
| **Residuos Orgánicos** | ✅ PDF por registro | ✅ PDF de todos los registros |
| **Pilas de Compostaje** | ✅ PDF por pila | ✅ PDF de todas las pilas |
| **Seguimiento** | ✅ PDF por pila | ✅ PDF general |
| **Abono Terminado** | ✅ PDF por registro | ✅ PDF de todos los registros |
| **Maquinaria** | ✅ PDF por equipo | ✅ PDF de todos los equipos |
| **Proveedores** | ✅ PDF por proveedor | — |
| **Mantenimiento** | ✅ PDF por actividad | ✅ PDF de todas las actividades |
| **Control de Uso** | ✅ PDF por registro | ✅ PDF de todos los registros |

---

## 11. Preguntas Frecuentes

**¿Puedo editar registros creados por otros usuarios?**
No. Como aprendiz, solo puede editar los registros que usted haya creado. Los registros de otros usuarios muestran un candado (🔒) en los botones de edición.

**¿Puedo eliminar registros directamente?**
No. Debe enviar una solicitud de eliminación al administrador, quien aprobará o rechazará la petición. Si es aprobada, podrá eliminar el registro.

**¿Qué hago si mi solicitud de eliminación fue rechazada?**
Si necesita eliminar el registro, contacte al administrador directamente para explicar la situación. No puede volver a enviar una solicitud para el mismo registro una vez rechazada.

**¿Cómo sé si mi solicitud fue respondida?**
Recibirá una notificación en el ícono de campana 🔔. Además, el badge rojo del ícono aumentará. También puede ir al historial de solicitudes para ver el estado.

**¿Puedo acceder al módulo de Gestión de Usuarios?**
No. Ese módulo está reservado exclusivamente para el rol de Administrador.

**¿Puedo acceder al módulo de Monitoreo?**
No. El módulo de Monitoreo con gráficas y exportación a Excel está disponible solo para el Administrador.

**¿Cómo recupero mi contraseña?**
Contacte al administrador del sistema. Él puede asignarle una nueva contraseña.

**¿Puedo acceder desde un dispositivo móvil?**
Sí, el sistema es responsivo, aunque se recomienda una resolución mínima de 1280x720 para mejor experiencia.

**¿Qué formato de imagen se acepta?**
Los formatos aceptados son JPEG, PNG, JPG y GIF, con un tamaño máximo de 2MB.

**¿Qué sucede si el administrador desactiva mi cuenta?**
No podrá iniciar sesión hasta que el administrador reactive su cuenta. Sus registros históricos se mantienen intactos.

---

## 12. Solución de Problemas

| Problema | Posible Causa | Solución |
|---|---|---|
| No puedo iniciar sesión | Contraseña incorrecta o cuenta desactivada | Verifique las credenciales. Si la cuenta está desactivada, contacte al administrador. |
| No puedo editar un registro | El registro fue creado por otro usuario | Solo puede editar registros que usted haya creado. |
| El ícono de eliminar muestra un candado | El registro no es suyo | No puede eliminar registros de otros usuarios. |
| Mi solicitud de eliminación sigue pendiente | El administrador no ha respondido | Espere a que el administrador procese la solicitud. Puede verificar en el historial. |
| No aparece el módulo de Usuarios | Es exclusivo del Administrador | El módulo de Gestión de Usuarios no está disponible para aprendices. |
| No aparece el módulo de Monitoreo | Es exclusivo del Administrador | El módulo de Monitoreo no está disponible para aprendices. |
| La tabla no carga los datos | Problema de conexión o JavaScript deshabilitado | Refresque la página (F5). Asegúrese de tener JavaScript habilitado. |
| No se genera el PDF | Timeout del servidor | Intente nuevamente. Si persiste, filtre los datos. |
| La imagen no se muestra | Formato no soportado o archivo corrupto | Verifique que sea JPEG, PNG, JPG o GIF y no exceda 2MB. |
| Error 500 al guardar | Fallo del servidor | Verifique campos obligatorios e intente nuevamente. Si persiste, contacte al equipo técnico. |
| Las notificaciones no aparecen | No hay notificaciones pendientes | Refresque la página. Las notificaciones se generan automáticamente. |
| La cuenta regresiva de mantenimiento no funciona | Problema de JavaScript | Refresque la página. |

---

## 13. Datos de Contacto

Para soporte técnico o consultas sobre el sistema:

**Equipo de Desarrollo COMPOST CEFA**
- Centro de Formación Agroindustrial (CEFA)
- Horario de atención: lunes a viernes de 8:00 a.m. a 5:00 p.m.
