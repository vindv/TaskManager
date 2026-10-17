<?php

require __DIR__ . '/vendor/autoload.php';

use TaskManager\Console;

use TaskManager\task\Task;
use TaskManager\task\TaskRepository;
use TaskManager\task\TaskService;

try {
    $repository = new TaskRepository(__DIR__ . '/tasks.json');
    $taskService = new TaskService($repository);
}catch(RuntimeException $e) {
    Console::error($e->getMessage());
    exit(1);
}

foreach($repository->getErrors() as $loadError) {
    Console::warning($loadError);
}

function showMenu(): void
{
    Console::title("=== Task manager ===");
    Console::write("1. Add task");
    Console::write("2. List tasks");
    Console::write("3. Complete Task");
    Console::write("4. Remove task");
    Console::write("5. Exit");
    echo PHP_EOL;
}
function formatTask(Task $task): string
{
    $status = $task->isCompleted() ? "[x]" : "[ ]";

    return "#" . $task->getId() . " " . $status . " " . $task->getName();
}

function showTasks(array $tasks): void
{
    foreach($tasks as $task) {
        Console::write(formatTask($task));
    }
}

$option = "";

while($option !== "5") {
    showMenu();

    $option = trim(readline("Choose an option: "));

    try {
        switch($option) {
            case "1":
                $name = trim(readline("Task name: "));

                if($name === "") {
                    Console::error("Task name cannot be empty");
                }else{
                    $task = $taskService->add($name);
                    Console::success("Task added: " . $task->getName());
                }

                echo PHP_EOL;
                break;
            case "2":
                Console::info("Total tasks: " . $taskService->count());

                if($taskService->isEmpty()) {
                    Console::warning("No tasks yet");
                }else{
                    showTasks($taskService->all());
                }

                echo PHP_EOL;
                break;
            case "3":
                if($taskService->isEmpty()) {
                    Console::warning("No tasks yet");
                }else{
                    showTasks($taskService->all());

                    $selectedId = (int) trim(readline("Select id: "));
                    $task = $taskService->find($selectedId);

                    if($task === null) {
                        Console::error("Task not found");
                    }elseif($task->isCompleted()) {
                        Console::warning("Task already completed");
                    }else{
                        $taskService->complete($task);
                        Console::success("Task completed: " . $task->getName());
                    }
                }

                echo PHP_EOL;
                break;
            case "4":
                if($taskService->isEmpty()) {
                    Console::warning("No tasks yet");
                }else{
                    showTasks($taskService->all());

                    $selectedId = (int) trim(readline("Select id: "));
                    $task = $taskService->find($selectedId);

                    if($task === null) {
                        Console::error("Task not found");
                    }else{
                        $taskService->remove($selectedId);
                        Console::success("Task removed: " . $task->getName());
                    }
                }

                echo PHP_EOL;
                break;
            case "5":
                break;
            default:
                Console::error("Invalid option");
                echo PHP_EOL;
        }
    }catch(RuntimeException $e) {
        Console::error($e->getMessage());
        echo PHP_EOL;
    }
}

Console::success("Goodbye!");