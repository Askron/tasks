<?php
class UserModel {
    private $db;

    function __construct() {
        $this->$db = new mysqli('localhost', 'id2843834_taskuser', 'q1w2e3r4t5y6', 'id2843834_tasks');
    }

    function __destruct() {
        $this->$db->close();
    }

    public function login(string $login, string $pass) {
        if ($this->$db->query("SELECT count(1) FROM `users` WHERE login='" . $this->$db->real_escape_string($login) . "' AND password=SHA1('" . $this->$db->real_escape_string($pass) . "')")->fetch_row()[0] == 1) {
            $this->$db->query("UPDATE `users` SET online='1' WHERE login='" . $this->$db->real_escape_string($login) . "'");
            return 1;
        }
        return 0;
    }

    public function logout() {
        return $this->$db->query("UPDATE `users` SET online='0' WHERE login='admin'");
    }
}
