<?php
session_start();
require('models/task.php');

class TaskManagerController {
    public static function addTask(string $name, string $email, string $text) {
        $task = new TaskModel();
        if ($task->add(htmlspecialchars($name), htmlspecialchars($email), htmlspecialchars($text))) {
            return 0;
        } else {
            return 1;
        }
    }

    public static function getTasks(string $order, string $orderDir, int $start, int $end) {
        $task = new TaskModel();
        return $task->getAll($order, $orderDir, $start, $end);
    }

    public static function getTasksNumber() {
        $task = new TaskModel();
        return $task->countAll();
    }

    public static function editTask(int $id, string $name, string $email, string $text, int $done, int $edited) {
        $task = new TaskModel();
        return $task->update($id, htmlspecialchars($name), htmlspecialchars($email), htmlspecialchars($text), $done, $edited);
    }
}
