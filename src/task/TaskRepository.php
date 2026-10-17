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

        if(!file_exists($this->filePath)) {
            return [];
        }

        $content = file_get_contents($this->filePath);

        if($content === false) {
            throw new \RuntimeException("Could not read " . $this->filePath);
        }
        if(trim($content) === "") {
            return [];
        }

        try {
            $rawTasks = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        }catch(\JsonException $e) {
            throw new \RuntimeException("Invalid JSON in " . $this->filePath, 0, $e);
        }

        if(!is_array($rawTasks) || !array_is_list($rawTasks)) {
            throw new \RuntimeException("Invalid task data in " . $this->filePath);
        }

        $tasks = [];
        foreach($rawTasks as $rawTask) {
            if(!is_array($rawTask)) {
                continue;
            }

            try {
                $tasks[] = Task::fromArray($rawTask);
            }catch(\InvalidArgumentException | \TypeError $e) {
                $this->errors[] = $e->getMessage();
            }
        }

        return $tasks;
    }


    public function save(array $tasks): void
    {
        $data = [];

        foreach($tasks as $task) {
            $data[] = $task->toArray();
        }

        try {
            $encodedTasks = json_encode($data, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
        }catch(\JsonException $e) {
            throw new \RuntimeException("Could not encode tasks as JSON", 0, $e);
        }

        $tempPath = $this->filePath . ".tmp";

        try{
            $bytesWritten = file_put_contents($tempPath, $encodedTasks, LOCK_EX);

            if($bytesWritten === false || $bytesWritten !== strlen($encodedTasks)) {
                throw new \RuntimeException("Could not write " . $tempPath);
            }

            if(!rename($tempPath, $this->filePath)) {
                throw new \RuntimeException("Could not replace " . $this->filePath);
            }
        }finally{
            if(file_exists($tempPath)) {
                unlink($tempPath);
            }
        }
    }


    public function getErrors(): array
    {
        return $this->errors;
    }
}