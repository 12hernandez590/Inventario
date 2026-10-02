<?php

require_once __DIR__ . '/config/conexion.php';

$consulta = $conexion->query(
    'SELECT
        id,
        nombre,
        cantidad,
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

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
                Escriba el nombre del producto y su cantidad.
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

            <div class="buscador-wrapper">
                <label class="label-buscador" for="buscar-producto">
                    Buscar producto
                </label>

                <input
                    id="buscar-producto"
                    class="buscador"
                    type="text"
                    placeholder="Busca por nombre, ID, cantidad, estado o fecha..."
                    aria-label="Buscar productos"
                >

                <p id="resultado-buscador" class="resultado-busqueda">
                    Mostrando <?php echo count($productos); ?> de <?php echo count($productos); ?> productos
                </p>
            </div>

            <div class="tabla-contenedor">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>

                    <tbody id="tbody-productos">
                        <?php if (count($productos) === 0): ?>
                            <tr>
                                <td colspan="5" class="sin-registros">
                                    No hay productos registrados.
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($productos as $producto): ?>
                            <?php
                            $estadoProducto = (int) $producto['cantidad'] > 0 ? 'disponible' : 'agotado';
                            $estadoLabel = (int) $producto['cantidad'] > 0 ? 'Disponible' : 'Sin existencia';
                            $textoBusqueda = strtolower(
                                trim(
                                    $producto['id'] . ' ' .
                                    $producto['nombre'] . ' ' .
                                    $producto['cantidad'] . ' ' .
                                    $estadoLabel . ' ' .
                                    $producto['fecharegistro']
                                )
                            );
                            ?>
                            <tr
                                class="fila-producto"
                                data-busqueda="<?php echo htmlspecialchars($textoBusqueda, ENT_QUOTES, 'UTF-8'); ?>"
                            >
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

    <script>
        const buscador = document.getElementById('buscar-producto');
        const filas = Array.from(document.querySelectorAll('.fila-producto'));
        const resultadoBuscador = document.getElementById('resultado-buscador');

        const normalizarTexto = (valor) => {
            return valor
                .normalize('NFD')
                .replace(/([\u0300-\u036f])/g, '')
                .toLowerCase()
                .trim();
        };

        const actualizarVista = () => {
            const termino = normalizarTexto(buscador.value || '');
            let visibles = 0;

            filas.forEach((fila) => {
                const texto = normalizarTexto(fila.dataset.busqueda || '');
                const coincide = !termino || texto.includes(termino);
                fila.style.display = coincide ? '' : 'none';

                if (coincide) {
                    visibles += 1;
                }
            });

            const total = filas.length;
            resultadoBuscador.textContent = termino
                ? `Mostrando ${visibles} de ${total} productos`
                : `Mostrando ${total} de ${total} productos`;

            const filaSinResultados = document.getElementById('fila-sin-resultados');
            if (filaSinResultados) {
                filaSinResultados.remove();
            }

            if (visibles === 0) {
                const filaVacia = document.createElement('tr');
                filaVacia.id = 'fila-sin-resultados';
                filaVacia.innerHTML = '<td colspan="5" class="sin-registros">No se encontraron productos que coincidan con la búsqueda.</td>';
                document.getElementById('tbody-productos').appendChild(filaVacia);
            }
        };

        let temporizadorBusqueda;
        buscador.addEventListener('input', () => {
            clearTimeout(temporizadorBusqueda);
            temporizadorBusqueda = setTimeout(actualizarVista, 700);
        });

        actualizarVista();
    </script>
</body>
</html>