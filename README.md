# Inventario

## 1. ¿Qué hace el proyecto?

- Agregar nuevos productos con nombre y cantidad.
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

## 5. Pruebas automatizadas

Las pruebas unitarias de validación pueden ejecutarse con:

```sh
php tests/test_validaciones.php
```

La prueba de integración requiere una base MySQL/MariaDB llamada
`inventariopruebas`, la tabla definida en `tests/estructura_pruebas.sql` y
las variables `DB_HOST`, `DB_NAME`, `DB_USER` y `DB_PASSWORD`. La prueba
inserta y consulta un producto dentro de una transacción que luego revierte.
GitHub Actions ejecuta ambas pruebas automáticamente en cada push y en
solicitudes de cambio hacia `main`.
