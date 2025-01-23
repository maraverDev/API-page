<?php
$url_api = 'https://openlibrary.org/subjects/fantasy.json?limit=10';
$conexion = curl_init($url_api);
curl_setopt($conexion, CURLOPT_RETURNTRANSFER, true);
curl_setopt($conexion, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$respuesta = curl_exec($conexion);

if (curl_errno($conexion)) {
    echo 'Error en la solicitud: ' . curl_error($conexion);
    curl_close($conexion);
    exit;
}

curl_close($conexion);

$datos = json_decode($respuesta, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo 'Error al decodificar JSON: ' . json_last_error_msg();
    exit;
}

$libros = $datos['works'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Libros de Fantasía</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Lista de Libros de Fantasía</h1>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-lg shadow-md">
                <thead class="bg-purple-700 text-white">
                    <tr>
                        <th class="py-3 px-6 text-left">Título</th>
                        <th class="py-3 px-6 text-left">Autor(es)</th>
                        <th class="py-3 px-6 text-left">Año Publicación</th>
                        <th class="py-3 px-6 text-left">Portada</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <?php foreach ($libros as $libro): ?>
                        <tr class="border-b hover:bg-gray-100">
                            <td class="py-3 px-6"><?php echo htmlspecialchars($libro['title']); ?></td>
                            <td class="py-3 px-6">
                                <?php
                                if (!empty($libro['authors'])) {
                                    $autores = array_map(fn($autor) => $autor['name'], $libro['authors']);
                                    echo htmlspecialchars(implode(', ', $autores));
                                } else {
                                    echo 'Desconocido';
                                }
                                ?>
                            </td>
                            <td class="py-3 px-6"><?php echo htmlspecialchars($libro['first_publish_year'] ?? 'Desconocido'); ?></td>
                            <td class="py-3 px-6">
                                <?php if (!empty($libro['cover_id'])): ?>
                                    <img src="https://covers.openlibrary.org/b/id/<?php echo htmlspecialchars($libro['cover_id']); ?>-S.jpg" alt="Portada" class="w-12 h-16 object-cover">
                                <?php else: ?>
                                    <span>No disponible</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
