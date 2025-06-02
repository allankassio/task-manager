<?php

namespace Allan\TaskManager\Repository;

use Allan\TaskManager\Database;
use Allan\TaskManager\Entity\Task;
use PDO;

class TaskRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function save(Task $task): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO tasks (Title, Description, Due_Date, Set_Date) VALUES (?, ?, ?, NOW())"
        );
        $stmt->execute([$task->title, $task->description, $task->dueDate]);
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM tasks");
        return array_map(function ($row) {
            return new Task(
                $row['ID'],
                $row['Title'],
                $row['Description'],
                $row['Due_Date'],
                (bool)$row['Done'],
                $row['Set_Date']
            );
        }, $stmt->fetchAll());
    }

    public function toggleDone(int $id): array
    {
        // Get current status
        $stmt = $this->pdo->prepare('SELECT Done FROM tasks WHERE ID = ?');
        $stmt->execute([$id]);
        $current = $stmt->fetchColumn();

        if ($current === false) {
            throw new \Exception("Task not found", 404);
        }

        $newStatus = $current ? 0 : 1;

        // Update the task
        $stmt = $this->pdo->prepare('UPDATE tasks SET Done = ? WHERE ID = ?');
        $stmt->execute([$newStatus, $id]);

        // Fetch updated row
        $stmt = $this->pdo->prepare('SELECT * FROM tasks WHERE ID = ?');
        $stmt->execute([$id]);
        $updatedTask = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($updatedTask) {
            $updatedTask['Done'] = (bool)$updatedTask['Done'];
        }

        return $updatedTask;
    }


    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM tasks WHERE ID = ?");
        $stmt->execute([$id]);
    }

    public function updatePartial(int $id, array $fields): array
    {
        $set = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($fields)));
        $fields['id'] = $id;

        $sql = "UPDATE tasks SET $set WHERE ID = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($fields);

        $stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE ID = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }


}
