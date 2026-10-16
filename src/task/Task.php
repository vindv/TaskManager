<?php

namespace TaskManager\task\task;

class Task
{
    private int $id;
    private string $name;
    private bool $completed = false;

    public function __construct(int $id, string $name, bool $completed = false)
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException("id must be a positive integer");
        }

        $this->id = $id;
        $this->setName($name);
        $this->completed = $completed;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        if (trim($name) === "") {
            throw new \InvalidArgumentException("name can't be empty");
        }

        $this->name = trim($name);
    }

    public function isCompleted(): bool
    {
        return $this->completed;
    }

    public function setCompleted(bool $completed): void
    {
        $this->completed = $completed;
    }


    public function toArray(): array
    {
        return [
            "id" => $this->getId(),
            "name" => $this->getName(),
            "completed" => $this->isCompleted()
        ];
    }

    public static function fromArray(array $data): Task
    {
        if(!array_key_exists("name", $data)) {
            throw new \InvalidArgumentException("missing key: name");
        }
        if(!array_key_exists("id", $data)) {
            throw new \InvalidArgumentException("missing key: id");
        }
        if(!array_key_exists("completed", $data)) {
            throw new \InvalidArgumentException("missing key: completed");
        }

        return new self($data["id"], $data["name"], $data["completed"]);
    }
}