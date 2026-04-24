# ATM Security Portal - Azure Cloud Service

Este es un proyecto de sistema de autenticación seguro inspirado en la interfaz de un cajero automático (ATM), desarrollado como parte de las actividades del curso de Cloud Computing en SENATI. La aplicación permite validar credenciales de usuario contra una base de datos en la nube y mantiene un registro de auditoría en tiempo real.

## 🚀 Propósito
El objetivo principal es demostrar la integración de una aplicación web PHP con servicios de base de datos relacionales en la nube (Azure SQL Database) y el despliegue continuo mediante Azure App Service.

## 🛠️ Tecnologías Utilizadas
* **Backend:** PHP 8.x
* **Base de Datos:** Azure SQL Database (MSSQL)
* **Frontend:** HTML5, CSS3 (Flexbox/Grid), JavaScript (Vanilla)
* **Infraestructura:** Azure App Service
* **Control de Versiones:** Git / GitHub
* **Tipografía:** Google Fonts (Poppins)

## 📁 Estructura del Proyecto
* `index.php`: Contiene toda la lógica de negocio, conexión a la base de datos y la interfaz de usuario en un solo archivo optimizado.
* `styles`: Implementación de diseño responsivo y efectos visuales (Spinners, Tooltips).
* `scripts`: Lógica de interacción para el teclado numérico y manejo de eventos (clic/doble clic).

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
