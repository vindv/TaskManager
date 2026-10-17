<?php

namespace TaskManager\task;

class TaskRepository
{
    private string $filePath;
    private array $errors = [];

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function load(): array
    {
        $this->errors = [];
        $tasks = [];

        if (!file_exists($this->filePath)) {
            return $tasks;
        }

        $rawTasks = json_decode(file_get_contents($this->filePath), true);

        if (!is_array($rawTasks)) {
            return $tasks;
        }

        foreach ($rawTasks as $rawTask) {
            if (!is_array($rawTask)) {
                continue;
            }

            try {
                $tasks[] = Task::fromArray($rawTask);
            } catch (\InvalidArgumentException | \TypeError $e) {
                $this->errors[] = $e->getMessage();
            }
        }

        return $tasks;
    }


    public function save(array $tasks): void
    {
        $data = [];

        foreach ($tasks as $task) {
            $data[] = $task->toArray();
        }

        file_put_contents($this->filePath, json_encode($data, JSON_PRETTY_PRINT));
    }


    public function getErrors(): array
    {
        return $this->errors;
    }
}