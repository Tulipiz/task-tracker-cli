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
        $description = trim($args, "\"");
        if (empty(trim($description))) {
            echo "Erro: A descrição não pode estar vazia!\n";
            return;
        }
        $data = [
            "id" => 1,
            "description" => $description,
            "status" => "done",
            "createdAT" =>  date('d/m/Y H:i:s'),
            "updatedAt" => null
        ];

        if (file_exists($filePath) && filesize($filePath) > 0) {
            $tasks = file_get_contents($filePath);
            $tasksArray = json_decode($tasks, true);
        } else {
            $tasksArray = [];
        }

        $lastId = !empty($tasksArray) ? end($tasksArray)['id'] : 0;
        $newId = $lastId + 1;
        $data['id'] = $newId;

        $tasksArray[] = $data;

        $json = json_encode($tasksArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if (file_put_contents($filePath, $json)) {
            echo "Task added successfully (ID:" . $data['id'] . ")\n";
        }
    },
    'update' => function ($id, $args) use ($filePath) {
        $data = json_decode(file_get_contents($filePath), true);
        $description = trim($args, "\"");
        if (empty(trim($description))) {
            echo "Erro: A descrição não pode estar vazia!\n";
            return;
        }

        if (!$data) {
            echo "erro ao ler o arquivo JSON.\n";
            return;
        }

        $userFound = false;
        foreach ($data as &$task) {
            if ($task['id'] === $id) {
                $task['description'] = $description;
                $task['updatedAt'] = date('d/m/Y H:i:s');
                $userFound = true;
                break;
            }
        }
  
        if($userFound){
            $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            file_put_contents($filePath, $json);
            echo "Task added successfully (ID:" . $id . ")\n";
        } else {
            echo "Usuário não encontrado.\n";
        }

    }

];
echo ("\n\n Digite 'help' para listar todos os comandos disponiveis!\n\n");

while (true) {
    echo "task-cli ";
    $input = trim(fgets(STDIN));

    $specialCommands = ['exit', 'help', 'clear'];
    if (in_array($input, $specialCommands)) {
        $menu[$input]();
        continue;
    }

    preg_match('/^(\w+)\s+(\d+)?\s*"([^"]+)"/', $input, $matches);

    $command = $matches[1] ?? null;
    $id = isset($matches[2]) ? (int)$matches[2] : null;
    $args = $matches[3] ?? null;

    if (isset($menu[$command])) {

        if ($command === 'add') {
            $menu[$command]($args);
        } elseif ($command === 'update' && $id) {
            $menu[$command]($id, $args);
        } else {
            echo "Erro: ID da tarefa é necessário para o comando update!\n";
        }
    } else {
        echo "Opção Inválida\n";
    }
}
