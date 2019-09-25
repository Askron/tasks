<?php
class TaskModel {
    private $db;

    function __construct() {
        $this->$db = new mysqli('localhost', 'id2843834_taskuser', 'q1w2e3r4t5y6', 'id2843834_tasks');
    }

    function __destruct() {
        $this->$db->close();
    }

    public function add(string $name, string $email, string $text) {
        return $this->$db->query("INSERT INTO `tasks` (`name`, `email`, `text`) VALUES ('" . $this->$db->real_escape_string($name) . "', '" . $this->$db->real_escape_string($email) . "', '" . $this->$db->real_escape_string($text) . "')");
    }

    public function getAll(string $order, string $orderDir, int $start, int $end) {
        $tasks = [];
        foreach ($this->$db->query("SELECT * FROM `tasks` ORDER BY " . $this->$db->real_escape_string($order) . " " . $this->$db->real_escape_string($orderDir) . " LIMIT " . $this->$db->real_escape_string($start) . ", " . $this->$db->real_escape_string($end)) as $task) {
            array_push($tasks, $task);
        }
        return $tasks;
    }

    public function countAll() {
        return (int) $this->$db->query("SELECT count(1) FROM `tasks`")->fetch_row()[0];
    }

    public function update(int $id, string $name, string $email, string $text, int $done, int $edited) {
        return $this->$db->query("UPDATE `tasks` SET name='" . $this->$db->real_escape_string($name) . "', email='" . $this->$db->real_escape_string($email) . "', text='" . $this->$db->real_escape_string($text) . "', done='" . $this->$db->real_escape_string($done) . "', edited='" . $this->$db->real_escape_string($edited) . "' WHERE id='" . $this->$db->real_escape_string($id) . "'");
    }
}
