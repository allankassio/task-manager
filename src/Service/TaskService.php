<?php

namespace Allan\TaskManager\Service;

use Allan\TaskManager\DTO\TaskDTO;
use Allan\TaskManager\DTO\TaskUpdateDTO;
use Allan\TaskManager\Entity\Task;
use Allan\TaskManager\Repository\TaskRepository;

class TaskService
{
    public function __construct(
        private TaskRepository $repository = new TaskRepository()
    )
    {
    }

    public function create(TaskDTO $dto): void
    {
        $task = new Task(null, $dto->title, $dto->description, $dto->dueDate);
        $this->repository->save($task);
    }

    public function list(): array
    {
        return $this->repository->findAll();
    }

    public function toggleDone(int $id): array
    {
        return $this->repository->toggleDone($id);
    }

    public function delete(int $id): void
    {
        $this->repository->delete($id);
    }

    public function updateTask(int $id, TaskUpdateDTO $dto): array
    {
        return $this->repository->updatePartial($id, $dto->toSqlFields());
    }
}
