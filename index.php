<?php
ini_set("display_errors", 0);
session_start();
require('controllers/auth.php');
require('controllers/taskManager.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Tasks</title>
        <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="robots" content="all">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="keywords" content="">
            <meta name="description" content="">
            <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
            <link href="/views/css/main.css" rel="stylesheet">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
            <script src="/views/js/main.js"></script>
    </head>
    <?php
    $loggedIn = false;
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
       $loggedIn = true;
    } else {
        if (isset($_POST['login'])) {
            if (AuthController::login($_POST['login'], $_POST['pass']) == 1) {
                $loggedIn = true;
                $_SESSION['loggedin'] = true;
            } else {
                ?> <script>
                    $(document).ready(function() {
                        $('#auth').modal('show');
                        $('#auth input[name=pass]').after('<small class="form-text text-muted">Неправильный логин или пароль.</small>');
                    });
                </script> <?php
            }
        }
    }
    ?>
    <script>
        let loggedIn = <?= (int) $loggedIn; ?>;
    </script>
    <body>
        <?php include('views/main.php'); ?>
    </body>
</html>
