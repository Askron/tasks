$(window).on('load', () => {
    let texts = [];

    let setTasksBtnsEvents = () => {
        $('.btn-edit').on('click', function () {
            $.post('/ajax.php', {'act': 'isLoggedin'}, (response) => {
                if (response == 1) {
                    $('.task.active .btn-cancel').trigger('click');
                    let task = $(this).closest('.task');
                    $(task).addClass('active');
                    texts = [];
                    $(task).find('.col:not(:last)').each(function () {
                        texts.push($(this).text());
                        $(this).remove();
                    });
                    $(task).find('.btn-edit-wrap').before(`
            <div class="col"><input value="${texts[0]}" placeholder="Имя" name="name" type="text" required class="form-control"></div>
            <div class="col"><input value="${texts[1]}" placeholder="Email" name="email" type="text" required class="form-control"></div>
            <div class="col"><input value="${texts[2]}" placeholder="Text" name="text" type="text" required class="form-control"></div>
            <div class="col">
                <select name="done" class="form-control">
                    <option data-sort="name" ${(texts[3] === 'Не выполнено') ? 'selected' : ''}>Не выполнено</option>
                    <option data-sort="email" ${(texts[3] === 'Выполнено') ? 'selected' : ''}>Выполнено</option>
                </select>
            </div>
                    `);
                    $(task).find('.btn-edit-wrap .btn-edit').css('display', 'none');
                    $(task).find('.btn-edit-wrap .btn-cancel').css('display', 'inline');
                    $(task).find('.btn-edit-wrap .btn-save').css('display', 'inline');
                } else {
                    $('#auth').modal('show');
                }
            });

        });

        $('.btn-cancel').on('click', function () {
            let task = $(this).closest('.task');
            $(task).removeClass('active');
            $(task).find('.col:not(:last)').each(function () {
                $(this).remove();
            });
            $(task).find('.btn-edit-wrap').before(`
    <div class="col">${texts[0]}</div>
    <div class="col">${texts[1]}</div>
    <div class="col">${texts[2]}</div>
    <div class="col">${texts[3]}</div>
            `);
            $(task).find('.btn-edit-wrap .btn-edit').css('display', 'inline');
            $(task).find('.btn-edit-wrap .btn-cancel').css('display', 'none');
            $(task).find('.btn-edit-wrap .btn-save').css('display', 'none');
        });

        $('.btn-save').on('click', function () {
            let task = $(this).closest('.task');
            var edited = 1;
            if ($(task).find('input[name="name"]').val() === texts[0] && $(task).find('input[name="email"]').val() === texts[1] && $(task).find('input[name="text"]').val() === texts[2]) {
                edited = 0;
            }
            $.post('/ajax.php', {
                'act': 'editTask',
                'id': $(task).attr('data-id'),
                'name': $(task).find('input[name="name"]').val(),
                'email': $(task).find('input[name="email"]').val(),
                'text': $(task).find('input[name="text"]').val(),
                'done': ($(task).find('select[name="done"]').val() === 'Не выполнено') ? 0 : 1,
                'edited': edited,
            }, (response) => {console.log('editted: ' + response);
                if (response != 1) {
                    console.error('Ошибка при обновлении.');
                }
                $(task).find('.btn-edit-wrap .btn-edit').css('display', 'inline');
                $(task).find('.btn-edit-wrap .btn-cancel').css('display', 'none');
                $(task).find('.btn-edit-wrap .btn-save').css('display', 'none');
                updateTasks();
            });
        });
    };

    let updateTasks = () => {
        let start = 0;
        if ($('.pagination')[0]) {
            start = Number($('.pagination button.page.active').text()) * 3 - 3;
        }
        $.post('/ajax.php', {
            'act': 'getTasks',
            'sort': $('.header__select-sort>option:eq(' + $('.header__select-sort')[0].selectedIndex + ')')[0].dataset.sort,
            'order': $('.header__select-order>option:eq(' + $('.header__select-order')[0].selectedIndex + ')')[0].dataset.order,
            'start': start,
            'end': 3,
        }, (response) => {console.log(response);
            $('.task').remove();
            $.each(JSON.parse(response), (k, v) => {
                $('form.add-form').before(`
<div data-id="${v.id}" class="task row">
    <div class="col">${v.name}</div>
    <div class="col">${v.email}</div>
    <div class="col">${v.text}</div>
    <div class="col">${(v.done == 0) ? 'Не выполнено' : 'Выполнено'}</div>
    <div class="col btn-edit-wrap">
        ${(loggedIn) ? `
<button type="button" class="btn-cancel btn btn-danger">Отменить</button>
<button type="button" class="btn-save btn btn-success">Сохранить</button>
<button type="button" class="btn-edit btn btn-info">Редактировать</button>
        ` : ((v.edited == 0) ? '' : 'отредактировано администратором')}
    </div>
</div>
                `);
            });
            setTasksBtnsEvents();
        });
    };

    let updatePagination = () => {
        $.post('/ajax.php', {'act': 'getTasksNumber'}, (response) => {
            if (!$('.pagination')[0] && response > 3) {
                $('form.add-form').after(`<div class="pagination row justify-content-center"></div>`);
            }
            let activeIndex = $('.pagination button.page.active').closest('.btn-wrap').index();
            $('.pagination .btn-wrap').remove();
            for (var i = response, num = 1; i > 0; i -= 3, num++) {
                $('.pagination').append(`
<div class="btn-wrap col-auto">
    <button class="page btn btn-link">${num}</button>
</div>
                `);
            }
            $(`.pagination .btn-wrap:eq(${activeIndex}) button.page`).addClass('active');
            $('.pagination button.page').on('click', function () {
                $('.pagination button.page').removeClass('active');
                $(this).addClass('active');
                updateTasks();
            });
            if (response == 4) {
                $('.pagination button.page').removeClass('active');
                $('.pagination button.page:first').addClass('active');
            }
        });
    };

    $('.btn-logout').on('click', () => {
        location.href = '/';
        $.post('/ajax.php', {
            'act': 'logout'
        }, (response) => {
            console.log(response);
        });
    });

    $('.header__select').on('change', function () {
        if ($('.header__select-sort').val() == 'по статусу') {
            $('.header__select-order option:first').text('cначала невыполненные');
            $('.header__select-order option:last').text('cначала выполненные');
        } else {
            $('.header__select-order option:first').text('алфавитный');
            $('.header__select-order option:last').text('обратный алфавитный');
        }
        updateTasks();
    });

    $('.add-form button[type="submit"]').on('click', (e) => {
        e.preventDefault();
        if ($('.add-form input[name="name"]').val() == '') {
            $('.add-form input[name="name"]').addClass('wrong');
        } else {
            $('.add-form input[name="name"]').removeClass('wrong');
        }
        if ($('.add-form input[name="text"]').val() == '') {
            $('.add-form input[name="text"]').addClass('wrong');
        } else {
            $('.add-form input[name="text"]').removeClass('wrong');
        }
        if (!/^\w+@\w+\.\w+$/.test($('.add-form input[name="email"]').val()) || $('.add-form input[name="email"]').val().length >= 256) {
            $('.add-form input[name="email"]').addClass('wrong');
            return;
        } else {
            $('.add-form input[name="email"]').removeClass('wrong');
        }
        $.post('/ajax.php', $('.add-form').serialize(), (response) => {
            updateTasks();
            updatePagination();
            $('#added').modal('show');
            $('input.form-control, textarea.form-control').val('');
        });
    });

    $('.pagination button.page').on('click', function () {
        $('.pagination button.page').removeClass('active');
        $(this).addClass('active');
        updateTasks();
    });

    setTasksBtnsEvents();
});
