<?php

namespace Allan\TaskManager\Entity;

/**
 * @OA\Schema(
 *     schema="Task",
 *     title="Task entity",
 *     description="A task object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Finish report"),
 *     @OA\Property(property="description", type="string", example="Finalize the monthly report"),
 *     @OA\Property(property="due_date", type="string", format="date-time", example="2025-06-10T18:00:00"),
 *     @OA\Property(property="done", type="boolean", example=false),
 *     @OA\Property(property="set_date", type="string", format="date-time", nullable=true, example="2025-06-01T10:00:00")
 * )
 */
class Task
{
    public function __construct(
        public ?int    $id,
        public string  $title,
        public string  $description,
        public string  $dueDate,
        public bool    $done = false,
        public ?string $setDate = null
    )
    {
    }
}
