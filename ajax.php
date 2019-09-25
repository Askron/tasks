<?php
    ini_set("display_errors", 0);
    session_start();
    require('controllers/auth.php');
    require('controllers/taskManager.php');

switch ($_POST['act']) {
    case 'addTask':
        echo TaskManagerController::addTask($_POST['name'], $_POST['email'], $_POST['text']);
        break;
    case 'getTasks':
        header('Content-Type: application/json');
        echo json_encode(TaskManagerController::getTasks($_POST['sort'], $_POST['order'], $_POST['start'], $_POST['end']));
        break;
    case 'getTasksNumber':
        echo TaskManagerController::getTasksNumber();
        break;
    case 'editTask':
        echo TaskManagerController::editTask((int) $_POST['id'], $_POST['name'], $_POST['email'], $_POST['text'], $_POST['done'], $_POST['edited']);
        break;
    case 'logout':
        $_SESSION['loggedin'] = false;
        echo AuthController::logout();
        break;
    case 'isLoggedin':
        echo $_SESSION['loggedin'];
        break;
}
