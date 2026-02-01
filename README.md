# CampusCash (`/aw`)

Aplicación web en **PHP + MySQL/MariaDB** para la **gestión de finanzas personales y de grupo**: registra ingresos/gastos, analiza datos con gráficos, gestiona grupos y divide gastos comunes, e incluye **chat por grupo** y un **panel de administración**.

## Funcionalidades

- Registro e inicio de sesión.
- Gestión de gastos personales: ingresos/gastos, categorías, historial y edición.
- Gráficos con **Chart.js** (resúmenes mensuales, donut por categorías, etc.).
- Grupos: crear/modificar/eliminar, añadir miembros, gastos grupales y **balance** con pagos sugeridos.
- Chat por grupo (lectura/envío) desde la barra de navegación.
- Administración:
  - Gestión de usuarios (activar/desactivar, bloquear 1h).
  - Panel de estadísticas.
  - Modo mantenimiento (redirige a pantalla de mantenimiento para no-admin).

## Requisitos

- **XAMPP** (o equivalente) con:
  - **PHP 8.x** con extensión `mysqli`.
  - **MariaDB/MySQL**.
- Navegador moderno (JS habilitado).

## Instalación (local con XAMPP)

1. Copia/clona el proyecto dentro de `htdocs` con este nombre de carpeta:
   - `D:\xampp\htdocs\aw\`
2. Crea la base de datos y el usuario (valores por defecto del proyecto):
   - BD: `awp2`
   - Usuario: `awp2`
   - Password: `awpass`

   SQL orientativo:
   ```sql
   CREATE DATABASE awp2 CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
   CREATE USER 'awp2'@'localhost' IDENTIFIED BY 'awpass';
   GRANT ALL PRIVILEGES ON awp2.* TO 'awp2'@'localhost';
   FLUSH PRIVILEGES;
   ```
3. Importa el esquema:
   - `includes/mysql/Estructura.sql`
4. (Opcional) Importa datos de ejemplo:
   - `includes/mysql/Datos.sql`
5. Inicia Apache + MySQL/MariaDB y abre:
   - `http://localhost/aw/`

## Configuración

- Conexión a BD y rutas:
  - `includes/config.php`
  - Ajusta `BD_HOST`, `BD_USER`, `BD_NAME`, `BD_PASS` y, si cambias la carpeta, `RUTA_APP` (por defecto `'/aw/'`).
- Modo mantenimiento:
  - `includes/config_app.php` (`maintenance_mode`)
  - Se puede cambiar desde `admin_configuracion.php` (solo admin).

## Acceso Admin (nota)

El registro crea usuarios con rol `usuario`. Para acceder al panel admin (`admin.php`) necesitas un usuario con rol `admin`.

- Si importas `includes/mysql/Datos.sql`, se insertan usuarios de ejemplo con rol `admin` (las contraseñas están almacenadas como hash y no vienen en texto plano).
- Alternativa rápida: cambia el campo `rol` a `admin` para tu usuario en la tabla `usuarios` y/o actualiza el `password` con un hash generado por `password_hash`.

## Rutas/páginas principales

- Inicio: `index.php`
- Login/registro: `login.php`, `registro.php`
- Gastos: `gastos.php`, `historial_gastos.php`
- Grupos: `grupos.php`, `grupo_detalles.php`, `grupo_balance.php`, `historial_gasto_grupal.php`
- Gráficos: `graficos.php`
- Admin: `admin.php`, `admin_estadisticas.php`, `admin_configuracion.php`

## Estructura del proyecto

- `includes/`: configuración, clases (`includes/clases/`), vistas/plantillas (`includes/vistas/`) y SQL (`includes/mysql/`).
- `js/`: lógica de UI (charts, chat, modales, validación).
- `css/`, `img/`: estilos y recursos.

## Licencia

No hay una licencia definida en el repositorio.
