<?php

namespace TaskManager\task;

class TaskService
{
    private TaskRepository $repository;
    private array $tasks;

    public function __construct(TaskRepository $repository)
    {
        $this->repository = $repository;
        $this->tasks = $repository->load();
    }

    public function all(): array
    {
        return $this->tasks;
    }

    public function count(): int
    {
        return count($this->tasks);
    }

    public function isEmpty(): bool
    {
        return empty($this->tasks);
    }

    public function find(int $id): ?Task
    {
        foreach($this->tasks as $task) {
            if($task->getId() === $id) {
                return $task;
            }
        }

        return null;
    }

    public function add(string $name): Task
    {
        $task = new Task($this->nextId(), $name);

        $this->tasks[] = $task;
        $this->repository->save($this->tasks);

        return $task;
    }

    public function complete(Task $task): void
    {
        $task->setCompleted(true);

        $this->repository->save($this->tasks);
    }

    public function remove(int $id): void
    {
        $remainingTasks = [];

        foreach($this->tasks as $task) {
            if($task->getId() !== $id) {
                $remainingTasks[] = $task;
            }
        }

        $this->tasks = $remainingTasks;
        $this->repository->save($this->tasks);
    }

    private function nextId(): int
    {
        $maxId = 0;

        foreach($this->tasks as $task) {
            if($task->getId() > $maxId) {
                $maxId = $task->getId();
            }
        }

        return $maxId + 1;
    }
}