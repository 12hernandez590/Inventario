<?php

require_once __DIR__ . '/config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$idEntrada = $_POST['id'] ?? null;
$nombreEntrada = $_POST['nombre'] ?? null;

if (!is_string($idEntrada) || !is_string($nombreEntrada)) {
    header('Location: index.php?estado=nombre_invalido');
    exit;
}

$id = filter_var(
    $idEntrada,
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
);
$nombre = trim($nombreEntrada);

if ($id === false || !preg_match('/^[^\r\n]{1,60}$/uD', $nombre)) {
    header('Location: index.php?estado=nombre_invalido');
    exit;
}

$sentencia = $conexion->prepare(
    'UPDATE productos
     SET nombre = :nombre
     WHERE id = :id'
);

$sentencia->execute([
    'nombre' => $nombre,
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

header('Location: index.php?estado=nombre_actualizado');
exit;