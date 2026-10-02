<?php

require_once __DIR__ . '/config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = filter_var(
    $_POST['id'] ?? null,
    FILTER_VALIDATE_INT
);

if ($id === false || $id === null || $id < 1) {
    header('Location: index.php');
    exit;
}

$sentencia = $conexion->prepare(
    'DELETE FROM productos WHERE id = :id'
);

$sentencia->execute(['id' => $id]);

header('Location: index.php?estado=eliminado');
exit;