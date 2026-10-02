<?php

require_once __DIR__ . '/config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$idEntrada = $_POST['id'] ?? null;
$precioEntrada = $_POST['precio'] ?? null;

if (!is_string($idEntrada) || !is_string($precioEntrada)) {
    header('Location: index.php?estado=precio_invalido');
    exit;
}

$id = filter_var(
    $idEntrada,
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
);
$precioEntrada = trim($precioEntrada);

if (
    $id === false
    || !preg_match('/^(?:0|[0-9]{1,8})(?:\.[0-9]{1,2})?$/D', $precioEntrada)
) {
    header('Location: index.php?estado=precio_invalido');
    exit;
}

[$parteEntera, $parteDecimal] = array_pad(
    explode('.', $precioEntrada, 2),
    2,
    ''
);
$parteEntera = ltrim($parteEntera, '0');
$parteEntera = $parteEntera === '' ? '0' : $parteEntera;
$precio = $parteEntera . '.' . str_pad($parteDecimal, 2, '0');

$sentencia = $conexion->prepare(
    'UPDATE productos
     SET precio = :precio
     WHERE id = :id'
);

$sentencia->execute([
    'precio' => $precio,
    'id' => $id
]);

if ($sentencia->rowCount() === 0) {
    $verificacion = $conexion->prepare(
        'SELECT 1 FROM productos WHERE id = :id'
    );
    $verificacion->execute(['id' => $id]);

    if (!$verificacion->fetchColumn()) {
        header('Location: index.php');
        exit;
    }
}

header('Location: index.php?estado=precio_actualizado');
exit;