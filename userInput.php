<?php
echo "\n Digite seu nome: ";
$nome = trim(fgets(STDIN));
echo "\n Olá, $nome! \n";

if (file_exists('usuarios.json') && filesize('usuarios.json') > 0) {
    $usuariosExistentes = json_decode(file_get_contents('usuarios.json'), true);
    if (!is_array($usuariosExistentes)) {
        $usuariosExistentes = [];
    }
} else {
    $usuariosExistentes = [];
}
$usuariosExistentes[] = [
    "nome" => $nome,
];

$json = json_encode($usuariosExistentes, JSON_PRETTY_PRINT);

file_put_contents('usuarios.json', $json);

echo "\nUsuário salvo com sucesso!\n";

$usuarios = file_get_contents('usuarios.json');
$usuarioDecodificados = json_decode($usuarios, true);

echo "\nLista de usuários salvos:\n\n";

foreach ($usuarioDecodificados as $user) {
    echo "- " . $user['nome'] . "\n\n";
}
