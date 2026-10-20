
<?php

require_once __DIR__ . '/../src/validaciones.php';

$correctas = 0;
$total = 0;

function probar(string $nombre, bool $esperado, bool $obtenido): void
{
    global $correctas, $total;

    $total++;

    if ($esperado === $obtenido) {
        $correctas++;
        echo "[OK] $nombre\n";
    } else {
        echo "[ERROR] $nombre"
            . " | Esperado: " . ($esperado ? 'true' : 'false')
            . " | Obtenido: " . ($obtenido ? 'true' : 'false')
            . "\n";
    }
}

echo "=== PRUEBAS DE VALIDACIONES ===\n\n";

probar("Nombre valido", true, validarNombreProducto("Teclado"));
probar("Nombre corto", false, validarNombreProducto("AB"));
probar("Nombre vacio", false, validarNombreProducto(""));
probar("Cantidad positiva", true, validarCantidadProducto(5));
probar("Cantidad cero", true, validarCantidadProducto(0));
probar("Cantidad negativa", false, validarCantidadProducto(-3));
probar("Cantidad decimal", false, validarCantidadProducto(2.5));
probar("Cantidad con letras", false, validarCantidadProducto("abc"));

echo "\nResultado: $correctas de $total pruebas correctas.\n";

if ($correctas !== $total) {
    echo "Hay pruebas que fallaron.\n";
    exit(1);
}

echo "Todas las pruebas pasaron correctamente.\n";
