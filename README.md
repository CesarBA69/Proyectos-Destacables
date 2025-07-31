# Dulzón: Gestión para Dulcerías

### Descripción

**Dulzón** es una aplicación web enfocada en la administración integral de una dulcería. Permite gestionar ventas, productos, clientes, empleados y usuarios a través de un panel amigable y funcional. El sistema está diseñado para ser claro, rápido y adaptable a las necesidades del negocio.

---

### Requisitos del Sistema

Para ejecutar correctamente este sistema, necesitas tener instalado lo siguiente:

* Servidor web: IIS (Internet Information Services)
* PHP: versión 8.0 o superior
* Base de datos: MySQL 
* Navegador moderno: Compatible con HTML5 y Bootstrap 5

---

### Tecnologías Utilizadas

* PHP 8 – Lógica del servidor
* MySQL – Gestión de base de datos relacional
* HTML5 / CSS3 – Estructura y estilos básicos
* Bootstrap 5 – Diseño responsivo y componentes visuales
  CDN utilizado:
  `https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css`
* JavaScript – Interactividad del cliente
* Html2Pdf – Generación de reportes PDF

---

### Funcionalidades Principales

* Inicio de sesión para administradores y empleados
* Registro y consulta de productos
* Registro de clientes
* Registro y edición de empleados
* Generación de reportes en PDF
* Panel de atención para ventas y selección de productos
* Personalización visual de la página

---

### Estructura del Proyecto

```bash
├── index.php
├── login.php
├── logout_cliente.php
├── registro_cliente.php
├── registro_trabajador.php
├── eliminar_producto.php
├── eliminar_trabajador.php
├── agregar_producto.php
├── obtener_producto.php
├── main.php
├── pagina_cliente.php
├── admin_inicio/
│   └── [archivos del panel de administrador]
├── atencion_inicio/
│   └── [archivos del panel de atención]
├── reporteventas/
│   ├── reporte.php
│   ├── PlantillaRventas.php
│   └── Plantillareporte.php
├── db/
│   └── dulzon.sql
├── assets/
│   ├── img/
│   ├── css/
│   └── js/
├── utils/
│   └── [funciones y utilidades comunes]
```

### Modelo Entidad-Relación (E-R)
El sistema Dulzón se apoya en un modelo entidad-relación sencillo pero robusto. A continuación, se describen las entidades principales:

Entidades:
Empleado
Atributos: id_empleado, nombre, correo, usuario, contraseña, rol, etc.
Cada empleado puede realizar múltiples ventas.

Cliente
Atributos: id_cliente, nombre, correo, teléfono.
Los clientes pueden estar asociados a múltiples ventas.

Producto
Atributos: id_producto, nombre, descripción, precio, stock, categoría.
Los productos pueden aparecer en muchas ventas.

### Instalación

1. Clona o descarga el repositorio del sistema.
2. Coloca los archivos en la carpeta correspondiente de IIS (por ejemplo, `C:\inetpub\wwwroot\dulzon`).
3. Importa la base de datos `dulzon.sql` en tu gestor MySQL.
4. Configura el archivo `includes/conexion.php` con tus datos de conexión.
5. Abre el navegador y accede a `http://localhost/dulzon`.

---

### Reportar Errores

Si encuentras errores o deseas sugerir mejoras, puedes contactar directamente a los desarrolladores del proyecto.

---

### Autor


* Cesar Aarón Briseño Arista – Desarrollador principal

---

---

### Página del Proyecto

Actualmente no disponible en línea. Puedes ejecutarlo localmente en tu servidor IIS.

---
