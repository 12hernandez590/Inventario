# Inventario

## 1. ¿Qué hace el proyecto?

- Agregar nuevos productos con nombre, cantidad y precio.
- Corregir el precio de productos registrados.
- Ver el listado de productos registrados.
- Mostrar la fecha de registro.
- Validar que los campos no queden vacíos.
- Indicar si el producto está disponible o agotado según su cantidad.
---
## 2. ¿Qué tecnología utiliza?
- PHP 8.1.10 o compatible
- MariaDB
- HTML5
- CSS3
- Apache (usualmente con Laragon en Windows)

## 3. ¿Qué se necesita instalar?
- Laragon o xampp
- PHP
- MySQL o MariaDB
- Un navegador web 

## 4.configuro y ejecuto?
-Clonar o copiar el proyecto
-Crear la base de datos
-Configurar la conexión

## 5. Agregar precio a una base existente

Si la base de datos ya fue creada antes de agregar el campo de precio, ejecuta
`migracion_precio.sql` una sola vez sobre la base `inventario`. Los productos
existentes conservarán sus datos y recibirán un precio inicial de `0.00`.
Las instalaciones nuevas ya incluyen la columna `precio` en `base_datos.sql`.