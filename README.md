# Task Manager (PHP CLI)

A small console task manager written in PHP. You can add, list, complete and remove tasks from a colored menu, and your tasks are saved in a JSON file so they are still there the next time you run it.

I built it step by step to practice object-oriented PHP: classes, validation, exceptions, Composer autoloading and separating logic from output.

## Features

- Add tasks (empty names are rejected)
- List tasks with their status: `[ ]` pending, `[x]` completed
- Mark a task as completed
- Remove a task
- Tasks are saved automatically in `tasks.json`
- Colored messages in the terminal (success, warning, error)

## Requirements

- PHP 8.1 or higher, with the `readline` extension
- [Composer](https://getcomposer.org/)

## Installation

```bash
git clone https://github.com/vindv/TaskManager.git
cd TaskManager
composer install
```

`composer install` does not download any packages (the project has no dependencies). It generates the autoloader in `vendor/` and checks that `readline` is available.

## Usage

```bash
php index.php
```

```
=== Task manager ===
1. Add task
2. List tasks
3. Complete Task
4. Remove task
5. Exit

Choose an option:
```

- **1. Add task**: asks for a name and saves the task with a new id.
- **2. List tasks**: shows every task with its status.
- **3. Complete Task**: shows the tasks with their ids and asks which one to complete.
- **4. Remove task**: shows the tasks with their ids and asks which one to remove.
- **5. Exit**: closes the program.

## How it works

- **`Task`** validates its own data: the id must be positive and the name cannot be empty. It can convert itself to an array (`toArray()`) and be built from one (`fromArray()`).
- **`TaskRepository`** is the only class that touches the file. If a task in the JSON is damaged, it is skipped and a warning is shown.
- **`TaskService`** holds the task list and the rules (new ids never repeat, even after removing tasks). It does not print anything.
- **`index.php`** reads what the user types and shows the results.

Example of the saved file:

```json
[
    {
        "id": 1,
        "name": "buy milk",
        "completed": true
    },
    {
        "id": 2,
        "name": "go to the doctor",
        "completed": false
    }
]
```

