<?php

require_once __DIR__ . '/config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$nombreEntrada = $_POST['nombre'] ?? '';
$cantidad = $_POST['cantidad'] ?? '';
$precioEntrada = $_POST['precio'] ?? '';

if (!is_string($nombreEntrada)) {
    header('Location: index.php?estado=nombre_invalido');
    exit;
}

$nombre = trim($nombreEntrada);

if ($nombre === '' || $cantidad === '' || $precioEntrada === '') {
    header('Location: index.php?estado=incompleto');
    exit;
}

if (!preg_match('/^[^\r\n]{1,60}$/uD', $nombre)) {
    header('Location: index.php?estado=nombre_invalido');
    exit;
}

if (!is_numeric($cantidad)) {
    header('Location: index.php?estado=cantidad_invalida');
    exit;
}

if (
    !is_string($precioEntrada)
    || !preg_match('/^(?:0|[0-9]{1,8})(?:\.[0-9]{1,2})?$/D', trim($precioEntrada))
) {
    header('Location: index.php?estado=precio_invalido');
    exit;
}

[$parteEntera, $parteDecimal] = array_pad(
    explode('.', trim($precioEntrada), 2),
    2,
    ''
);
$parteEntera = ltrim($parteEntera, '0');
$parteEntera = $parteEntera === '' ? '0' : $parteEntera;
$precio = $parteEntera . '.' . str_pad($parteDecimal, 2, '0');

$sentencia = $conexion->prepare(
    'INSERT INTO productos (nombre, cantidad, precio)
     VALUES (:nombre, :cantidad, :precio)'
);

$sentencia->execute([
    'nombre' => $nombre,
    'cantidad' => (int) $cantidad,
    'precio' => $precio
]);

header('Location: index.php?estado=guardado');
exit;