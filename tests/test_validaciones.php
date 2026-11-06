<?php

require_once __DIR__ . '/../src/validaciones.php';

$casosNombre = [
    ['Producto válido', true],
    ['  Producto con espacios  ', true],
    [str_repeat('a', 60), true],
    [str_repeat('á', 60), true],
    ['', false],
    ['   ', false],
    [str_repeat('a', 61), false],
    [[], false]
];

foreach ($casosNombre as [$nombre, $esperado]) {
    if (validarNombreProducto($nombre) !== $esperado) {
        fwrite(STDERR, "ERROR: validación incorrecta de nombre.\n");
        exit(1);
    }
}

$casosCantidad = [
    ['0', true],
    ['5', true],
    [5, true],
    [5.0, false],
    [true, false],
    ['-1', false],
    ['5.5', false],
    ['texto', false],
    ['', false],
    [[], false]
];

foreach ($casosCantidad as [$cantidad, $esperado]) {
    if (validarCantidadProducto($cantidad) !== $esperado) {
        fwrite(STDERR, "ERROR: validación incorrecta de cantidad.\n");
        exit(1);
    }
}

echo "CORRECTO: validaciones de nombre y cantidad.\n";
