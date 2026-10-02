<?php

require_once __DIR__ . '/config/conexion.php';

$consulta = $conexion->query(
    'SELECT
        id,
        nombre,
        cantidad,
        precio,
        fecharegistro
    FROM productos
    ORDER BY id asc'
);

$productos = $consulta->fetchAll(PDO::FETCH_ASSOC);

$estado = $_GET['estado'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>INVENTARIO</title>

    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>
    <header class="encabezado">
        <div class="contenido-encabezado">
            <p class="etiqueta">GPDS</p>

            <h1>INVENTARIO</h1>

            <p>
                Registro y consulta de productos
            </p>
        </div>
    </header>

    <main class="contenedor">
        <section class="tarjeta formulario">
            <h2>Registrar producto</h2>

            <p class="descripcion">
                Escriba el nombre, la cantidad y el precio del producto.
            </p>

            <?php if ($estado === 'guardado'): ?>
                <div class="mensaje correcto">
                    Producto registrado correctamente.
                </div>
            <?php endif; ?>

            <?php if ($estado === 'eliminado'): ?>
                <div class="mensaje correcto">
                    Producto eliminado correctamente.
                </div>
            <?php endif; ?>

            <?php if ($estado === 'cantidad_actualizada'): ?>
                <div class="mensaje correcto">
                    Cantidad corregida correctamente.
                </div>
            <?php endif; ?>

            <?php if ($estado === 'precio_actualizado'): ?>
                <div class="mensaje correcto">
                    Precio corregido correctamente.
                </div>
            <?php endif; ?>

            <?php if ($estado === 'incompleto'): ?>
                <div class="mensaje error">
                    Debe completar todos los campos.
                </div>
            <?php endif; ?>

            <?php if ($estado === 'cantidad_invalida'): ?>
                <div class="mensaje error">
                    La cantidad debe ser un número entero igual o mayor que cero.
                </div>
            <?php endif; ?>

            <?php if ($estado === 'precio_invalido'): ?>
                <div class="mensaje error">
                    El precio debe ser un número positivo con hasta dos decimales.
                </div>
            <?php endif; ?>

            <form action="guardar.php" method="POST">
                <div class="campo">
                    <label for="nombre">
                        Nombre del producto
                    </label>

                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        maxlength="100"
                        placeholder="Ejemplo: Café"
                        required
                    >
                </div>

                <div class="campo">
                    <label for="cantidad">
                        Cantidad
                    </label>

                    <input
                        type="number"
                        id="cantidad"
                        name="cantidad"
                        placeholder="Ejemplo: 10"
                        required
                    >
                </div>

                <div class="campo">
                    <label for="precio">
                        Precio
                    </label>

                    <input
                        type="number"
                        id="precio"
                        name="precio"
                        min="0"
                        max="99999999.99"
                        step="0.01"
                        placeholder="Ejemplo: 25.50"
                        required
                    >
                </div>

                <button type="submit">
                    Registrar producto
                </button>
            </form>
        </section>

        <section class="tarjeta listado">
            <div class="titulo-listado">
                <div>
                    <h2>Productos registrados</h2>

                    <p class="descripcion">
                        Total: <?php echo count($productos); ?>
                    </p>
                </div>
            </div>

            <div class="tabla-contenedor">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (count($productos) === 0): ?>
                            <tr>
                                <td colspan="6" class="sin-registros">
                                    No hay productos registrados.
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($productos as $producto): ?>
                            <tr>
                                <td>
                                    <?php echo $producto['id']; ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $producto['nombre']
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php echo $producto['cantidad']; ?>

                                    <form
                                        class="formulario-fix-correccion"
                                        action="fix_correccion.php"
                                        method="POST"
                                    >
                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?php echo (int) $producto['id']; ?>"
                                        >
                                        <input
                                            type="number"
                                            name="cantidad"
                                            value="<?php echo (int) $producto['cantidad']; ?>"
                                            min="0"
                                            step="1"
                                            aria-label="Nueva cantidad para <?php echo htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?>"
                                            required
                                        >
                                        <button
                                            class="boton-fix-correccion"
                                            type="submit"
                                        >
                                            Corregir
                                        </button>
                                    </form>
                                </td>

                                <td>
                                    <?php
                                    echo number_format(
                                        (float) $producto['precio'],
                                        2,
                                        '.',
                                        ','
                                    );
                                    ?>

                                    <form
                                        class="formulario-fix-precio"
                                        action="fix_precio.php"
                                        method="POST"
                                    >
                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?php echo (int) $producto['id']; ?>"
                                        >
                                        <input
                                            type="number"
                                            name="precio"
                                            value="<?php echo number_format((float) $producto['precio'], 2, '.', ''); ?>"
                                            min="0"
                                            max="99999999.99"
                                            step="0.01"
                                            aria-label="Nuevo precio para <?php echo htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?>"
                                            required
                                        >
                                        <button
                                            class="boton-fix-precio"
                                            type="submit"
                                        >
                                            FIX Precio
                                        </button>
                                    </form>
                                </td>

                                <td>
                                    <?php if ($producto['cantidad'] > 0): ?>
                                        <span class="estado disponible">
                                            Disponible
                                        </span>
                                    <?php else: ?>
                                        <span class="estado agotado">
                                            Sin existencia
                                        </span>
                                    <?php endif; ?>

                                    <form
                                        class="formulario-eliminar"
                                        action="eliminar.php"
                                        method="POST"
                                        onsubmit="return confirm('¿Deseas eliminar este producto?');"
                                    >
                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?php echo (int) $producto['id']; ?>"
                                        >
                                        <button
                                            class="boton-eliminar"
                                            type="submit"
                                        >
                                            Eliminar
                                        </button>
                                    </form>
                                </td>

                                <td>
                                    <?php
                                    echo $producto['fecharegistro'];
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer>
        U1. Planeación del proceso de desarrollo de software
    </footer>
</body>
</html>