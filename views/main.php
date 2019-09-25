<header class="container">
    <div class="row justify-content-between">
        <div class="col-auto">
            Сортировать задачи по
            <select class="header__select header__select-sort form-control">
                <option data-sort="name">имени пользователя</option>
                <option data-sort="email">по email</option>
                <option data-sort="done">по статусу</option>
            </select>
            <br>Порядок сортировки
            <select class="header__select header__select-order form-control">
                <option data-order="ASC">алфавитный</option>
                <option data-order="DESC">обратный алфавитный</option>
            </select>
        </div>
        <div class="col-auto">
            <?php if ($loggedIn): ?>
                <button type="button" class="btn-logout btn btn-danger">Выйти</button>
            <?php else: ?>
                <button type="button" data-toggle="modal" data-target="#auth" class="btn-login btn btn-primary">Войти</button>
            <?php endif; ?>
        </div>
    </div>
</header>
<main class="container">
    <div class="header row">
        <div class="col">Имя</div>
        <div class="col">Email</div>
        <div class="col">Текст</div>
        <div class="col">Статус</div>
        <div class="col"></div>
    </div>
    <?php foreach (TaskManagerController::getTasks('name', 'ASC', 0, 3) as $task): ?>
        <div data-id="<?= $task['id'] ?>" class="task row">
            <div class="col"><?= $task['name'] ?></div>
            <div class="col"><?= $task['email'] ?></div>
            <div class="col"><?= $task['text'] ?></div>
            <div class="col"><?= ($task['done'] == 0) ? 'Не выполнено' : 'Выполнено' ?></div>
            <div class="col btn-edit-wrap">
                <?php if ($loggedIn): ?>
                    <button type="button" class="btn-cancel btn btn-danger">Отменить</button>
                    <button type="button" class="btn-save btn btn-success">Сохранить</button>
                    <button type="button" class="btn-edit btn btn-info">Редактировать</button>
                <?php else: ?>
                    <?= ($task['edited'] == 0) ? '' : 'отредактировано администратором' ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
    <form class="add-form row justify-content-center">
        <div class="col-2"><input placeholder="Имя" name="name" type="text" required class="form-control"></div>
        <div class="col-2"><input placeholder="Email" name="email" type="email" required class="form-control"></div>
        <div class="col-6"><textarea placeholder="Текст" name="text" type="text" required class="add-form__textarea form-control"></textarea></div>
        <input value="addTask" name="act" type="hidden">
        <div class="submit-wrap col-2">
            <button class="btn btn-success" name="name" type="submit">Добавить задачу</button>
        </div>
    </form>
    <?php
    $rowsCount = TaskManagerController::getTasksNumber();
    if ($rowsCount > 3):
    ?>
        <div class="pagination row justify-content-center">
            <div class="btn-wrap col-auto">
                <button class="page btn btn-link active">1</button>
            </div>
            <?php for ($i = $rowsCount - 3, $num = 2; $i > 0 ; $i -= 3, $num++): ?>
                <div class="btn-wrap col-auto">
                    <button class="page btn btn-link"><?= $num ?></button>
                </div>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</main>
<div class="modal fade" id="auth" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Вход</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/" method="post" id="loginForm">
                    <div class="form-group">
                      <label for="emailInput">Логин</label>
                      <input name="login" type="text" class="form-control" id="emailInput" required>
                    </div>
                    <div class="form-group">
                      <label for="passInput">Пароль</label>
                      <input name="pass" type="password" class="form-control" id="passInput" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="loginForm" class="btn btn-primary">Войти</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="added" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Задача</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Успешно создана
            </div>
        </div>
    </div>
</div>
