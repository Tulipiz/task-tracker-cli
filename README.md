# Task Tracker

[![Projeto Task Tracker](https://roadmap.sh/projects/task-tracker)](https://roadmap.sh/projects/task-tracker)

Task Tracker é uma ferramenta CLI simples para gerenciar tarefas.

## Como Iniciar

Certifique-se de ter o PHP instalado e execute o seguinte comando para iniciar o CLI:

```sh
php task-tracker-input.php
```

## Comandos Disponíveis

### Lista todos os comandos
```sh
help 
```

### Sair da aplicação
```sh
exit
```


### Limpa o terminal
```sh
clear 
```

### Adicionar uma nova tarefa
```sh
add "Descrição da tarefa"
```
Exemplo:
```sh
add "Finalizar documentação do projeto"
```

### Atualizar uma tarefa existente
```sh
update <ID> "Nova descrição da tarefa"
```
Exemplo:
```sh
update 1 "Atualizar descrição da tarefa"
```

### Excluir uma tarefa existente

```sh
delete <ID>
```
Exemplo:
```sh
delete 1
```

### Marcar uma tarefa em progresso

```sh
mark-in-progress <ID> 
```
Exemplo:
```sh
mark-in-progress 1
```

### Marcar uma tarefa como feita

```sh
mark-done <ID>
```
Exemplo:
```sh
mark-done 1
```

### Listar todas as tarefas pendentes

```sh
list todo
```

### Listar todas as tarefas em progresso

```sh
list in-progress
```

### Lista todas as tarefas feitas

```sh
list done
```

### Lista todas as tarefas
```sh
list 
```


