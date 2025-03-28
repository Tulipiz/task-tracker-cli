<?php
$filePath = 'tasks.json';
date_default_timezone_set('America/Sao_Paulo');
$menu = [
    'help' => function () {
        echo "Opções do Menu\n\n";
        echo "exit - Fechar o CLI.\n";
        echo "add - Adicionar uma nova Tarefa\n";
        echo "clear - limpa o prompt\n";
    },
    'exit' => function () {
        echo "Saindo...\n";
        exit;
    },
    'clear' => function () {
        echo "\033c";
    },
    'add' => function ($args) use ($filePath) {
        /*
        1 - verificar se existe o json
        2 - validar no arquivo json,
        Caso exista: Deve pegar o ID do ultimo registro e somar + 1;
        Caso não exista: é igual a 1;

        3 - fazer o $data 
        id: Um identificador único para a tarefa
        description: Uma breve descrição da tarefa
        status: todo,done,in-progress.
        createdAt: A data e hora em que a tarefa foi criada
        updatedAt: A data e hora em que a tarefa foi atualizada pela última vez

        4 - Salvar no Array.
        */
        $data = [
            "id" => 1,
            "description" => $args,
            "status" => "done",
            "createdAT" =>  date('d/m/Y H:i:s'),
            "updatedAt" => null
        ];

        if(file_exists($filePath) && filesize($filePath) > 0){
            $tasks = file_get_contents($filePath);
            $tasksArray = json_decode($tasks, true);
        } else {
            $tasksArray = [];
        }

        $lastId = !empty($tasksArray) ? end($tasksArray)['id'] : 0;

        $newId = $lastId + 1;

        $data['id'] = $newId;

        $tasksArray[] = $data;

        $json = json_encode($tasksArray, JSON_PRETTY_PRINT);

        if(file_put_contents($filePath, $json)){
            echo "Task added successfully (ID:" . $data['id'] . ")";
        }
    },
];
echo ("\n\n Digite 'help' para listar todos os comandos disponiveis!\n\n");

while (true) {
    echo "task-cli ";
    $input = trim(fgets(STDIN));

    $parts = explode(" ", $input);

    $command = array_shift($parts);
    $args = $parts;

    if (isset($menu[$command])) {
        $menu[$command]($args);
    } else {
        echo "Opção Invalida";
    }
}
