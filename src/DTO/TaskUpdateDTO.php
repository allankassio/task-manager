<?php

namespace Allan\TaskManager\DTO;

/**
 * @OA\Schema(
 *     schema="TaskUpdateInput",
 *     type="object",
 *     required={"id"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="New title"),
 *     @OA\Property(property="description", type="string", example="Updated description"),
 *     @OA\Property(property="due_date", type="string", format="date-time", example="2025-06-15T12:00:00Z"),
 *     @OA\Property(property="done", type="boolean", example=true)
 * )
 */
class TaskUpdateDTO
{
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public ?string $dueDate = null,
        public ?bool $done = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'] ?? null,
            description: $data['description'] ?? null,
            dueDate: $data['due_date'] ?? null,
            done: $data['done'] ?? null
        );
    }

    public function toSqlFields(): array
    {
        $fields = [];
        if ($this->title !== null) $fields['Title'] = $this->title;
        if ($this->description !== null) $fields['Description'] = $this->description;
        if ($this->dueDate !== null) $fields['Due_Date'] = $this->dueDate;
        if ($this->done !== null) $fields['Done'] = $this->done ? 1 : 0;
        return $fields;
    }
}
