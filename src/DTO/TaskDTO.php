<?php

namespace Allan\TaskManager\DTO;

/**
 * @OA\Schema(
 *     schema="TaskInput",
 *     type="object",
 *     required={"title", "description", "due_date"},
 *     @OA\Property(property="title", type="string", example="Finish project"),
 *     @OA\Property(property="description", type="string", example="Write unit tests"),
 *     @OA\Property(property="due_date", type="string", format="date-time", example="2025-06-10T18:00:00Z")
 * )
 */
class TaskDTO
{
    public function __construct(
        public string $title,
        public string $description,
        public string $dueDate
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['title'],
            $data['description'],
            $data['due_date']
        );
    }
}
