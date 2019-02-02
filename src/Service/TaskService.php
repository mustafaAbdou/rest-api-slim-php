<?php

namespace App\Service;

use App\Exception\TaskException;
use App\Repository\TaskRepository;

/**
 * Tasks Service.
 */
class TaskService extends BaseService
{
    /**
     * @var TaskRepository
     */
    protected $taskRepository;

    /**
     * @param TaskRepository $taskRepository
     */
    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @return TaskRepository
     */
    protected function getTaskRepository()
    {
        return $this->taskRepository;
    }

    /**
     * Check if the task exists.
     *
     * @param int $taskId
     * @return object
     */
    protected function checkTask($taskId)
    {
        return $this->getTaskRepository()->checkTask($taskId);
    }

    /**
     * Get all tasks.
     *
     * @return array
     */
    public function getTasks()
    {
        return $this->getTaskRepository()->getTasks();
    }

    /**
     * Get one task by id.
     *
     * @param int $taskId
     * @return object
     */
    public function getTask($taskId)
    {
        return $this->checkTask($taskId);
    }

    /**
     * Search tasks by name.
     *
     * @param string $tasksName
     * @return array
     */
    public function searchTasks($tasksName)
    {
        return $this->getTaskRepository()->searchTasks($tasksName);
    }

    /**
     * Create a task.
     *
     * @param array $input
     * @return object
     * @throws TaskException
     */
    public function createTask($input)
    {
        $task = new \stdClass();
        $data = json_decode(json_encode($input), false);
        if (empty($data->name)) {
            throw new TaskException(TaskException::TASK_NAME_REQUIRED, 400);
        }
        $task->name = self::validateTaskName($data->name);
        $task->status = 0;
        if (isset($data->status)) {
            $task->status = self::validateStatus($data->status);
        }

        return $this->getTaskRepository()->createTask($task);
    }

    /**
     * Update a task.
     *
     * @param array $input
     * @param int $taskId
     * @return object
     * @throws TaskException
     */
    public function updateTask($input, $taskId)
    {
        $task = $this->checkTask($taskId);
        if (!isset($input['name']) && !isset($input['status'])) {
            throw new TaskException(TaskException::TASK_INFO_REQUIRED, 400);
        }
        if (isset($input['name'])) {
            $task->name = self::validateTaskName($input['name']);
        }
        if (isset($input['status'])) {
            $task->status = self::validateStatus($input['status']);
        }

        return $this->getTaskRepository()->updateTask($task);
    }

    /**
     * Delete a task.
     *
     * @param int $taskId
     * @return string
     */
    public function deleteTask($taskId)
    {
        $this->checkTask($taskId);

        return $this->getTaskRepository()->deleteTask($taskId);
    }
}
