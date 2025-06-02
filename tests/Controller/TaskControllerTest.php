<?php

use PHPUnit\Framework\TestCase;

/**
 * Test suite for TaskController endpoints.
 */
class TaskControllerTest extends TestCase
{
    private string $baseUrl = 'http://localhost:8000/task';
    private static int $createdTaskId = 0;

    /**
     * Test task creation endpoint.
     */
    public function testCreateTask(): void
    {
        $body = [
            'title' => 'Test Task',
            'description' => 'Testing the creation endpoint',
            'due_date' => '2025-06-10 18:00:00'
        ];

        $response = $this->sendJsonRequest('/create', 'POST', $body);

        $this->assertEquals(201, $response['status']);
        $this->assertEquals('created', $response['body']['status'] ?? null);
    }

    /**
     * Test listing tasks.
     * Also demonstrates nullsafe-like logic on first result.
     */
    public function testListTasks(): void
    {
        $response = $this->sendJsonRequest('/list', 'GET');

        $this->assertEquals(200, $response['status']);
        $this->assertIsArray($response['body']);

        $firstTask = $response['body'][0] ?? null;
        $title = $firstTask['title'] ?? null;

        // Save first task ID for later tests
        if (isset($firstTask['id'])) {
            self::$createdTaskId = (int)$firstTask['id'];
        }

        $this->assertTrue(is_string($title) || is_null($title));
    }

    /**
     * Test toggling task's done status.
     */
    public function testToggleTask(): void
    {
        $this->assertGreaterThan(0, self::$createdTaskId, 'No valid task ID found');

        $response = $this->sendJsonRequest('/done', 'PUT', ['id' => self::$createdTaskId]);

        $this->assertEquals(200, $response['status']);
        $this->assertEquals(self::$createdTaskId, $response['body']['ID'] ?? null);
    }

    /**
     * Test updating a task.
     */
    public function testUpdateTask(): void
    {
        $this->assertGreaterThan(0, self::$createdTaskId, 'No valid task ID found');

        $body = [
            'id' => self::$createdTaskId,
            'title' => 'Updated Task',
            'description' => 'This task has been updated',
            'due_date' => '2025-06-10 18:00:00',
            'done' => true
        ];

        $response = $this->sendJsonRequest('/update', 'PUT', $body);

        $this->assertEquals(200, $response['status']);
        $this->assertEquals('Updated Task', $response['body']['Title'] ?? null);
    }

    /**
     * Test deleting a task.
     */
    public function testDeleteTask(): void
    {
        $this->assertGreaterThan(0, self::$createdTaskId, 'No valid task ID found');

        $response = $this->sendJsonRequest('/delete', 'DELETE', ['id' => self::$createdTaskId]);

        $this->assertEquals(200, $response['status']);
        $this->assertEquals('deleted', $response['body']['status'] ?? null);
    }

    /**
     * Helper method to send JSON HTTP requests to the API.
     */
    private function sendJsonRequest(string $path, string $method = 'GET', array $body = []): array
    {
        $ch = curl_init($this->baseUrl . $path);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($method !== 'GET') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        }

        $rawResponse = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'status' => $statusCode,
            'body' => json_decode($rawResponse, true)
        ];
    }
}
