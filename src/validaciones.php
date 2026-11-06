<?php

function validarNombreProducto(mixed $nombre): bool
{
    if (!is_string($nombre)) {
        return false;
    }

    $nombre = trim($nombre);

    return $nombre !== ''
        && preg_match('/\A.{1,60}\z/us', $nombre) === 1;
}

function validarCantidadProducto(mixed $cantidad): bool
{
    if (!is_string($cantidad) && !is_int($cantidad)) {
        return false;
    }

    return filter_var(
        $cantidad,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 0]]
    ) !== false;
}
