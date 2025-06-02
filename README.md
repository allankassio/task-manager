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

# TODOs

### Authentication and User Management

- [ ] Implement user registration and login system
- [ ] Use JWT or session-based authentication
- [ ] Add middleware to protect endpoints for authenticated users only

### User Accounts and Task Ownership

- [ ] Associate tasks with specific users
- [ ] Ensure users can only access and modify their own tasks
- [ ] Add an endpoint to retrieve the current user profile (`/user/me`)

### Notifications and Alerts

- [ ] Implement alerts for tasks nearing their due date
- [ ] Allow users to set custom reminders (e.g., X minutes/hours/days before due date)
- [ ] Create a background scheduler or cron to check for upcoming due tasks

### Recurring Tasks

- [ ] Add support for recurring tasks (daily, weekly, monthly)
- [ ] Automatically generate the next instance of a recurring task after completion

### Task Prioritization

- [ ] Add a `priority` field (e.g., low, medium, high) to tasks
- [ ] Allow tasks to be sorted and filtered by priority

### Task Tagging and Categorization

- [ ] Add support for tagging tasks with keywords or categories
- [ ] Add filtering logic to retrieve tasks by specific tag or category

### File Attachments

- [ ] Allow users to upload files (e.g., PDFs, documents) related to a task
- [ ] Save file metadata and store files in a secure directory or cloud service
- [ ] Implement download and delete endpoints for task attachments

### Task Sharing and Collaboration

- [ ] Add support for sharing tasks between users
- [ ] Implement permission levels (e.g., read-only, edit)
- [ ] Generate public links for shared access with optional expiration

### Task History and Audit Logging

- [ ] Track task changes (title, due date, description, status)
- [ ] Store a log of updates with timestamps and user IDs
- [ ] Add an endpoint to retrieve task history

### Completion Feedback and Reflection

- [ ] Store optional feedback or notes after a task is marked as complete
- [ ] Add support for post-completion status such as rating, notes, or outcome

### Search and Filtering

- [ ] Implement full-text search on task title and description
- [ ] Allow searching by partial matches and keywords

### Bulk Actions

- [ ] Add bulk delete functionality
- [ ] Add bulk toggle for completion status
- [ ] Support multi-task updates via a single API call

### Statistics and Metrics

- [ ] Add an endpoint for aggregated task statistics (e.g., completed vs. pending)
- [ ] Return historical metrics such as tasks completed per week or month
