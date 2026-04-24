# ATM Security Portal - Azure Cloud Service

Este es un proyecto de sistema de autenticación seguro inspirado en la interfaz de un cajero automático (ATM), desarrollado como parte de las actividades del curso de Cloud Computing en SENATI. La aplicación permite validar credenciales de usuario contra una base de datos en la nube y mantiene un registro de auditoría en tiempo real.

## 🚀 Propósito

El objetivo principal es demostrar la integración de una aplicación web PHP con servicios de base de datos relacionales en la nube (Azure SQL Database) y el despliegue continuo mediante Azure App Service.

## 🛠️ Tecnologías Utilizadas

- **Backend:** PHP 8.2
- **Base de Datos:** Azure SQL Database (MSSQL)
- **Frontend:** HTML5, CSS3 (Flexbox/Grid), JavaScript (Vanilla)
- **Infraestructura:** Azure App Service
- **Control de Versiones:** Git / GitHub
- **Tipografía:** Google Fonts (Poppins)

## 📋 Requisitos de Despliegue

- **PHP:** Versión 8.2 o superior con la extensión `sqlsrv` habilitada para conectar a SQL Server.
- **Base de Datos:** Azure SQL Database configurada con las tablas `usuarios` y `auditoria`.
- **Navegador:** Compatible con HTML5 y CSS3 para una experiencia óptima.
- **Conexión a Internet:** Requerida para acceder a Azure SQL Database y Google Fonts.

## 📁 Estructura del Proyecto

- `index.php`: Archivo principal que integra la lógica de negocio y la interfaz de usuario.
  - **Autenticación:** Procesa las peticiones POST, valida las credenciales y maneja las sesiones mediante parámetros URL.
  - **Interfaz ATM:** Implementa el teclado numérico virtual, campos de texto dinámicos y el sistema de feedback visual (Spinners).
  - **Panel de Auditoría:** Realiza consultas en tiempo real a Azure SQL para mostrar los últimos 10 intentos de acceso (Correctos/Errores).
  - **Scripts:** Contiene la lógica de JavaScript para el control del PIN de 6 dígitos y el borrado inteligente (clic/doble clic).

- `conexion.php`: Módulo de conectividad centralizado.
  - Configura los parámetros de conexión con Azure SQL Database (ServerName, DB, UID, PWD).
  - Implementa un **Manejador de Excepciones**: si la conexión falla, redirige automáticamente al usuario hacia `error.php` para evitar la exposición de errores técnicos.

- `error.php`: Página de contingencia y experiencia de usuario.
  - Diseñada para informar fallos de red o mantenimiento en la nube.
  - Incluye una opción de "Reintento" que permite al usuario volver a intentar la conexión una vez que el servicio de Azure esté disponible.

- `README.md`: Documentación completa del proyecto, tecnologías y guía de despliegue.

## 🔑 Características Principales

1.  **Teclado Numérico Virtual:** Restricción de entrada física para emular la seguridad de un ATM real.
2.  **Validación en la Nube:** Conexión segura mediante `sqlsrv` hacia Azure SQL.
3.  **Sistema de Auditoría:** Registro automático de intentos (ÉXITO/ERROR) con marca de tiempo.
4.  **Feedback Visual:** Indicadores de carga (Spinner) y mensajes de estado dinámicos.
5.  **Control de Errores:** Manejo de parámetros mediante método GET para notificar estados de login.
6.  **Acciones Inteligentes:** Botón de borrado con doble función (un clic para PIN, doble clic para limpiar todo).

## ⚙️ Cómo Funciona

1.  **Inicio de Sesión:** El usuario ingresa su nombre y utiliza el teclado virtual para un PIN de 6 dígitos.
2.  **Procesamiento:** Al hacer clic en "Iniciar Sesión", se activa un spinner de carga y se envía una petición POST.
3.  **Autenticación:** El servidor ejecuta una consulta preparada (`sqlsrv_query`) para evitar inyecciones SQL.
4.  **Registro:** Independientemente del resultado, el sistema inserta el intento en la tabla `auditoria`.
5.  **Panel:** Si el acceso es correcto, se redirige a un Panel de Auditoría que muestra los últimos 10 movimientos registrados en la base de datos de Azure.

## 🌐 Despliegue

El proyecto está configurado con **GitHub Actions** para un despliegue automático (CI/CD) en **Azure App Service**. Cada cambio subido a la rama principal se refleja inmediatamente en el entorno de producción.

---

Desarrollado por **Jorge Luis Muñante** - Estudiante de Ingeniería de Software con IA, SENATI.
