<?php
session_start();
require('models/user.php');

class AuthController {
    public static function login(string $login, string $pass) {
        $user = new UserModel();
        return $user->login(htmlspecialchars($login), htmlspecialchars($pass));
    }

    public static function logout() {
        $user = new UserModel();
        return $user->logout(htmlspecialchars());
    }
}
