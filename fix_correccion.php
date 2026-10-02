<?php

require_once __DIR__ . '/config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$idEntrada = $_POST['id'] ?? null;
$cantidadEntrada = $_POST['cantidad'] ?? null;

if (!is_string($idEntrada) || !is_string($cantidadEntrada)) {
    header('Location: index.php?estado=cantidad_invalida');
    exit;
}

$id = filter_var(
    $idEntrada,
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
);
$cantidad = filter_var(
    $cantidadEntrada,
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 0]]
);

if ($id === false || $cantidad === false) {
    header('Location: index.php?estado=cantidad_invalida');
    exit;
}

$sentencia = $conexion->prepare(
    'UPDATE productos
     SET cantidad = :cantidad
     WHERE id = :id'
);

$sentencia->execute([
    'cantidad' => $cantidad,
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

header('Location: index.php?estado=cantidad_actualizada');
exit;