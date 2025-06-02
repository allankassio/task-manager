<?php

namespace Allan\TaskManager\Controller;

use Allan\TaskManager\DTO\TaskDTO;
use Allan\TaskManager\DTO\TaskUpdateDTO;
use Allan\TaskManager\Service\TaskService;

/**
 * @OA\Tag(
 *     name="Task",
 *     description="Operations about tasks"
 * )
 *
 * @OA\PathItem(path="/task")
 */
class TaskController
{
    public function __construct(
        private TaskService $service = new TaskService()
    )
    {}

    /**
     * @OA\Post(
     *     path="/task/create",
     *     summary="Create a new task",
     *     tags={"Task"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TaskInput")
     *     ),
     *     @OA\Response(response=201, description="Task created successfully"),
     *     @OA\Response(response=400, description="Invalid JSON"),
     *     @OA\Response(response=422, description="Missing required fields"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function create(): void
    {
        header('Content-Type: application/json');

        // Get the raw JSON input
        $rawInput = file_get_contents('php://input');
        $data = json_decode($rawInput, true);

        // Check for invalid JSON
        if (!is_array($data)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid JSON']);
            return;
        }

        try {
            // Convert input to DTO
            $dto = TaskDTO::fromArray($data);

            // Validate required fields
            if (empty($dto->title) || empty($dto->description) || empty($dto->dueDate)) {
                http_response_code(422);
                echo json_encode(['error' => 'Missing required fields']);
                return;
            }

            // Create task using the service
            $this->service->create($dto);

            // Success response
            http_response_code(201);
            echo json_encode(['status' => 'created']);

        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * @OA\Get(
     *     path="/task/list",
     *     summary="List all tasks",
     *     tags={"Task"},
     *     @OA\Response(
     *         response=200,
     *         description="List of tasks",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Task"))
     *     )
     * )
     */
    public function list(): void
    {
        $tasks = $this->service->list();
        $this->respond($tasks);
    }

    /**
     * @OA\Put(
     *     path="/task/toggle-done",
     *     summary="Toggle the done status of a task",
     *     tags={"Task"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Task updated", @OA\JsonContent(ref="#/components/schemas/Task")),
     *     @OA\Response(response=400, description="Missing or invalid ID"),
     *     @OA\Response(response=405, description="Method not allowed")
     * )
     */
    public function toggleDone(): void
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (!is_array($input) || !isset($input['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing or invalid ID']);
            return;
        }

        try {
            $id = (int)$input['id'];
            $updatedTask = $this->service->toggleDone($id);
            echo json_encode($updatedTask);
        } catch (\Throwable $e) {
            http_response_code($e->getCode() >= 400 ? $e->getCode() : 500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * @OA\Delete(
     *     path="/task/delete",
     *     summary="Delete a task by ID",
     *     tags={"Task"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Task deleted"),
     *     @OA\Response(response=400, description="Missing or invalid ID"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function delete(): void
    {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);

        if (!is_array($input) || !isset($input['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing or invalid ID']);
            return;
        }

        try {
            $id = (int)$input['id'];
            $this->service->delete($id);
            http_response_code(200);
            echo json_encode(['status' => 'deleted']);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * @OA\Put(
     *     path="/task/update",
     *     summary="Update task fields (title, description, dueDate)",
     *     tags={"Task"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TaskUpdateInput")
     *     ),
     *     @OA\Response(response=200, description="Task updated", @OA\JsonContent(ref="#/components/schemas/Task")),
     *     @OA\Response(response=400, description="Missing or invalid ID"),
     *     @OA\Response(response=405, description="Method not allowed"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function update(): void
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (!is_array($input) || !isset($input['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing or invalid ID']);
            return;
        }

        try {
            $id = (int) $input['id'];
            $dto = TaskUpdateDTO::fromArray($input);
            $task = $this->service->updateTask($id, $dto);

            echo json_encode($task);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function respond(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
