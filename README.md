# Sistema de Gestión de Usuarios

![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?logo=bootstrap&logoColor=white)
![MVC](https://img.shields.io/badge/Pattern-MVC-blue)
![License](https://img.shields.io/badge/License-MIT-green)

## 📌 Descripción del proyecto
Este es un sistema web básico para la gestión de usuarios, desarrollado como un reto técnico.  
Permite a los usuarios registrarse, iniciar sesión y, dependiendo de su rol (**admin** o **user**), gestionar perfiles de usuario a través de un **CRUD** (Crear, Leer, Actualizar, Eliminar).

---

## ✨ Características
- **Autenticación**: Inicio de sesión seguro con manejo de sesiones.  
- **Gestión de Usuarios**: CRUD completo para administradores y edición de perfil para usuarios normales.  
- **Roles de Usuario**: Control de acceso basado en roles (admin y user).  
- **Diseño Responsivo**: Interfaz de usuario adaptada a diferentes dispositivos, con un diseño moderno y minimalista.  
- **Animación**: Fondo dinámico en las páginas de login y registro usando **Vanta.js**.  

---

## 📂 Estructura del proyecto
```
config/       → Archivos de configuración, incluida la conexión a la base de datos.
controllers/  → Lógica de negocio y manejo de la interacción con los modelos.
models/       → Clases para interactuar con la base de datos (POO).
public/       → Archivos públicos como CSS y JavaScript.
views/        → Vistas y archivos PHP para la interfaz de usuario.
database.sql  → Script para crear la base de datos y la tabla de usuarios.
```

---

## ⚙️ Requisitos de instalación
- Servidor web (Apache, Nginx, etc.)  
- PHP **7.4 o superior**  
- Base de datos **MySQL**  

---

## 🚀 Instrucciones de instalación y ejecución

1. **Clona el repositorio**  
   ```bash
   git clone [URL_DEL_REPOSITORIO]
   ```

2. **Configura la base de datos**  
   - Importa el archivo `database.sql` en tu gestor de base de datos MySQL.  
   - Abre el archivo `config/Database.php` y actualiza las credenciales de la base de datos (`host`, `dbname`, `username`, `password`).  

3. **Inicia el servidor web**  
   - Coloca los archivos en tu servidor web local (por ejemplo, en la carpeta `htdocs` si usas XAMPP).  
   - Navega a la carpeta principal del proyecto en tu navegador.  

---

## 🛠️ Decisiones técnicas

### Arquitectura de Software
Se optó por un patrón de diseño **Modelo-Vista-Controlador (MVC)**, aunque de forma simplificada.  
Esto ayuda a separar la lógica del negocio de la interfaz de usuario y la manipulación de datos, haciendo el código más modular y fácil de mantener.  

### Programación Orientada a Objetos (POO)
Se aplicó POO en las clases **Database**, **AuthController** y **UserController** para encapsular la funcionalidad y reutilizar el código, lo que mejora la calidad y escalabilidad del proyecto.  

### Seguridad
- **Validación de datos**: Se implementó tanto en el frontend (HTML `required`) como en el backend, previniendo inyecciones SQL y otros ataques.  
- **Contraseñas**: Se almacenan de forma segura usando `password_hash()` en PHP.  
- **Control de Acceso**: Validación de roles en el backend, impidiendo que un usuario normal acceda a rutas restringidas.  

### Diseño e Interfaz de Usuario
- Se utilizó **Bootstrap 5** para un diseño responsivo y predefinido, agilizando el desarrollo.  
- Se integró **Vanta.js** para añadir un fondo animado en login y registro, mejorando la estética y experiencia de usuario.  
