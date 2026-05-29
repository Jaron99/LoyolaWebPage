# Portal San Ignacio - Sistema de Gestión Académica

Plataforma integral desarrollada para el Colegio Parroquial San Ignacio de Loyola (CPSIL) que permite la gestión de matrículas, asignación docente y control de calificaciones.

## 🚀 Características Principales (Fase Actual)
* **Arquitectura MVC:** Separación lógica de Modelos, Vistas y Controladores.
* **Autenticación Segura:** Sistema de inicio de sesión validado contra base de datos y protegido por variables de sesión en PHP.
* **Control de Acceso por Roles:** Redirección automática y protección de rutas para Administradores, Docentes y Estudiantes.
* **Interfaz Responsiva:** Diseño limpio y moderno construido con Bootstrap 5, respetando la identidad visual institucional (Amarillo y Verde).

## 🛠️ Tecnologías Utilizadas
* **Frontend:** HTML5, CSS3, Bootstrap 5, Bootstrap Icons.
* **Backend:** PHP (Orientado a Objetos).
* **Base de Datos:** MySQL (Diseño relacional normalizado en 3FN).

## 📂 Estructura del Proyecto
* `/Models`: Clases PHP para la conexión (`conexion.model.php`) y consultas a la base de datos (`usuarios_model.php`).
* `/Imagenes`: Recursos gráficos y logotipos institucionales.
* `login.php`: Controlador y vista unificada para el acceso al portal.
* `sistema_*.php`: Paneles de control protegidos para cada rol de usuario.