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
            "status" => "todo",
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
        if (!file_exists($filePath)) {
            echo "Erro: Arquivo não foi criado. \n";
            return;
        }
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

        $found = false;
        foreach ($data as &$task) {
            if ($task['id'] === $id) {
                $task['description'] = $description;
                $task['updatedAt'] = date('d/m/Y H:i:s');
                $found = true;
                break;
            }
        }

        if ($found) {
            $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            file_put_contents($filePath, $json);
            echo "Task updated successfully (ID:" . $id . ")\n";
            return true;
        } else {
            echo "Usuário não encontrado.\n";
            return false;
        }
    },
    'delete' => function ($id) use ($filePath) {
        if (!file_exists($filePath)) {
            echo "Erro: Arquivo não foi criado. \n";
            return;
        }
        $data = json_decode(file_get_contents($filePath), true);

        $found = false;
        foreach ($data as $key => $task) {
            if ($task['id'] == $id) {
                unset($data[$key]);
                $found = true;
                break;
            }
        }

        if ($found) {
            $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            file_put_contents($filePath,  $json);
            echo "Task deleted successfully (ID:" . $id . ")\n";
            return true;
        } else {
            echo "Task not found.\n";
            return false;
        }
    },
    'mark-in-progress' => function ($id) use ($filePath) {
        if (!file_exists($filePath)) {
            echo "Erro: Arquivo não foi criado. \n";
            return;
        }
        $data = json_decode(file_get_contents($filePath), true);
        $found = false;
        foreach ($data as &$task) {
            if ($task['id'] === $id) {
                $task['status'] = 'in-progress';
                $found = true;
                break;
            }
        }
        if ($found) {
            $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            file_put_contents($filePath, $json);
            echo "Task (ID:" . $id . ") in-progress. \n";
            return true;
        } else {
            echo "Task not found.\n";
            return false;
        }
    },
    'mark-done' => function ($id) use ($filePath) {
        if (!file_exists($filePath)) {
            echo "Erro: Arquivo não foi criado. \n";
            return;
        }
        $data = json_decode(file_get_contents($filePath), true);
        $found = false;
        foreach ($data as &$task) {
            if ($task['id'] === $id) {
                $task['status'] = 'done';
                $found = true;
                break;
            }
        }

        if ($found) {
            $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            file_put_contents($filePath, $json);
            echo "Task (ID:" . $id . ") done. \n";
            return true;
        } else {
            echo "Task not found. \n";
            return false;
        }
    },
    'list' => function () use ($filePath) {
        if (!file_exists($filePath)) {
            echo "Erro: Arquivo não foi criado. \n";
            return;
        }
        $data = json_decode(file_get_contents($filePath), true);
        echo "\n ---- All Tasks ---- \n";
        foreach ($data as $task) {
            echo $task['description'] . "\n";
        }
        echo "\n";
    },
    'list-done' => function () use ($filePath) {
        if (!file_exists($filePath)) {
            echo "Erro: Arquivo não foi criado. \n";
            return;
        }
        $data = json_decode(file_get_contents($filePath), true);
        echo "\n ---- Tasks in Done ---- \n";
        foreach ($data as $task) {
            if ($task['status'] === 'done') {
                echo " - " . $task['description'] . "\n";
            }
        }
        echo "\n";
    },
    'list-in-progress' => function () use ($filePath) {
        if (!file_exists($filePath)) {
            echo "Erro: Arquivo não foi criado. \n";
            return;
        }
        $data = json_decode(file_get_contents($filePath), true);
        echo "\n ---- Tasks in Progress ---- \n";
        foreach ($data as $task) {
            if ($task['status'] === 'in-progress') {
                echo " - " . $task['description'] . "\n";
            }
        }
        echo "\n";
    },
    'list-todo' => function () use ($filePath) {
        if (!file_exists($filePath)) {
            echo "Erro: Arquivo não foi criado. \n";
            return;
        }
        $data = json_decode(file_get_contents($filePath), true);
        echo "\n ---- Tasks in Todo ---- \n";
        foreach ($data as $task) {
            if ($task['status'] === 'todo') {
                echo " - " . $task['description'] . "\n";
            }
        }
        echo "\n";
    }


];

echo ("\n\n Digite 'help' para listar todos os comandos disponiveis!\n\n");

while (true) {
    echo "task-cli ";
    $input = trim(fgets(STDIN));

    $specialCommands = ['exit', 'help', 'clear', 'list', 'list-done', 'list-in-progress', 'list-todo'];

    if ($input === 'list done') {
        $input = 'list-done';
    } else if ($input === 'list in-progress') {
        $input = 'list-in-progress';
    } else if ($input === 'list todo') {
        $input = 'list-todo';
    }
    if (in_array($input, $specialCommands)) {
        $menu[$input]();
        continue;
    }
    $markCommands = ['mark-in-progress', 'mark-done'];

    if (preg_match('/^(mark-in-progress|mark-done)\s+(\d+)$/', $input, $matches)) {
        $command = $matches[1];
        $id = (int)$matches[2];

        if (isset($menu[$command])) {
            $menu[$command]($id);
        } else {
            echo "Comando inválido.\n";
        }

        continue; 
    }

    preg_match('/^(\w+)\s+(\d+)?(?:\s*"([^"]+)")?/', $input, $matches);

    $command = $matches[1] ?? null;
    $id = isset($matches[2]) ? (int)$matches[2] : null;
    $args = $matches[3] ?? null;

    if (isset($menu[$command])) {

        if ($command === 'add') {
            $menu[$command]($args);
        } elseif ($command === 'update' && $id) {
            $menu[$command]($id, $args);
        } elseif (($command === 'delete') && $id) {
            $menu[$command]($id);
        } else {
            echo "Erro: ID da tarefa é necessário para o comando update!\n";
        }
    } else {
        echo "Opção Inválida\n";
    }
}
