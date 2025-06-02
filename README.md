# Task Manager API

A simple RESTful API for managing tasks, built with PHP 8.2, Apache, Composer, and documented using Swagger (OpenAPI).

## Features

- Create, update, list, and delete tasks.
- Swagger documentation generated automatically.
- Follows Clean Architecture principles (Controller, DTO, Service, Repository).

---

## Requirements

- Docker & Docker Compose
- Make (optional for easier commands)

---

## Quickstart (with Docker)

### 1. Clone the repository

```bash
git clone https://github.com/allankassio/task-manager.git
cd task-manager
```

### 2. Create your .env file
Create a .env file with the following contents:

```
APP_ENV=dev
DB_ROOT_PASSWORD=root
DB_NAME=taskdb
DB_USER=taskuser
DB_PASSWORD=secret
```

### 3. Start containers
```bash
docker-compose up --build
```

The API will be available at: http://localhost:8000

Swagger UI: http://localhost:8080/


## Running Tests
This project uses PHPUnit for automated testing.

### Prerequisites

1. Install PHPUnit using composer, if not installed

```bash
composer require --dev phpunit/phpunit
```

2. Run the API
```bash
docker-compose up --build
```

3. Run the Tests
```bash
./vendor/bin/phpunit
```

If it's all good you will see something like that:
```bash
PHPUnit 10.x by Sebastian Bergmann and contributors.

.                                                                   1 / 1 (100%)

Time: 00:00.123, Memory: 6.00 MB

OK (1 test, 2 assertions)
```

### Run specific test

Execute the PHPUnit directly on the test
```bash
./vendor/bin/phpunit tests/Controller/TaskControllerTest.php
```


Tip
Tests are located under the tests/ directory. The main test suite is defined in phpunit.xml.



---

## API Endpoints

Below are the main endpoints available for managing tasks:

---

#### `POST /task/create`
**Description:** Creates a new task.

**Request body (JSON):**
```json
{
  "title": "Finish project",
  "description": "Write unit tests",
  "due_date": "2025-06-10T18:00:00Z"
}
```

**Responses:**
- `201`: Task created successfully
- `400`: Invalid JSON
- `422`: Missing required fields
- `500`: Internal server error

---

#### `GET /task/list`
**Description:** Lists all registered tasks.

**Response (`200`):**
```json
[
  {
    "id": 1,
    "title": "Finish report",
    "description": "Finalize the monthly report",
    "due_date": "2025-06-10T18:00:00",
    "done": false,
    "set_date": "2025-06-01T10:00:00"
  }
]
```

---

#### `PUT /task/toggle-done`
**Description:** Toggles the completion status (`done`) of a task by its `id`.

**Request body (JSON):**
```json
{
  "id": 1
}
```

**Responses:**
- `200`: Task updated
- `400`: Missing or invalid ID
- `405`: Method not allowed

---

#### `DELETE /task/delete`
**Description:** Deletes a task by its `id`.

**Request body (JSON):**
```json
{
  "id": 1
}
```

**Responses:**
- `200`: Task deleted
- `400`: Missing or invalid ID
- `500`: Internal server error

---

#### `PUT /task/update`
**Description:** Updates task fields (title, description, due date, completion status).

**Request body (JSON):**
```json
{
  "id": 1,
  "title": "New title",
  "description": "Updated description",
  "due_date": "2025-06-15T12:00:00Z",
  "done": true
}
```

**Responses:**
- `200`: Task updated
- `400`: Missing or invalid ID
- `405`: Method not allowed
- `500`: Internal server error  