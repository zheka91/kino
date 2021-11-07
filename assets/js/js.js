this.load = function () { };
load.prototype.show = function () {
    $('.load').removeClass('displayNone');
    $('body').css({ 'overflow': 'hidden' });
};
load.prototype.hide = function () {
    $('body').css({ 'overflow': 'auto' });
    $('.load').addClass('displayNone');
};
$(window).on('load', function (event) {
    load.prototype.hide();
});

$('.profile').on('click', function (event) {
    event.preventDefault();
    if ($('.auth').length > 0) {
        $('.auth input').val('');
        $('.auth_error').parent().addClass('displayNone');
        $('.reg').addClass('displayNone');
        $('.enter, .auth').removeClass('displayNone');
        $('body').css({ 'overflow': 'hidden' });
    }
    else if ($('.menu').length > 0) {
        $('.menu').removeClass('displayNone');
    }
    return false;
});

$(document).on('click', function (event) {
    if ($(event.target).closest(".menu").length === 0) {
        $(".menu").addClass('displayNone');
    }
});

$('.close').on('click', function (event) {
    $('.auth input').val('');
    $('.auth_error').parent().addClass('displayNone');
    $('body').css({ 'overflow': 'auto' });
    $('.auth, .enter, .reg').addClass('displayNone');
});

$('.auth').on('click', '.link', function (event) {
    $('.auth input').val('');
    $('.auth_error').parent().addClass('displayNone');
    $('.enter, .reg').addClass('displayNone');
    $('.' + $(this).attr('attr-show')).removeClass('displayNone');
});

$('#reg').on('click', function (event) {
    $('.reg .auth_error').parent().addClass('displayNone');

    let login = $('.reg input[name="login"]:eq(0)').val().trim();
    let pass1 = $('.reg input[name="password"]:eq(0)').val().trim();
    let pass2 = $('.reg input[name="password2"]:eq(0)').val().trim();

    if (!login.length) {
        $('.reg .auth_error').html('Логин не может быть пустым').parent().removeClass('displayNone');
        return;
    }
    else if (login.length < 3) {
        $('.reg .auth_error').html('Пароль не может быть короче 3 символов').parent().removeClass('displayNone');
        return;
    }
    else if (!pass1.length) {
        $('.reg .auth_error').html('Пароль не может быть пустым').parent().removeClass('displayNone');
        return;
    }
    else if (pass1.length < 8) {
        $('.reg .auth_error').html('Пароль не может быть короче 8 символов').parent().removeClass('displayNone');
        return;
    }
    else if (!pass2.length) {
        $('.reg .auth_error').html('Повторите пароль').parent().removeClass('displayNone');
        return;
    }
    else if (pass1 !== pass2) {
        $('.reg .auth_error').html('Пароли должны совпадать').parent().removeClass('displayNone');
        return;
    }

    load.prototype.show();

    $.ajax({
        type: 'POST',
        url: 'controllers/auth.php',
        data: {
            action: 'reg',
            login: login,
            pass: pass1,
        },
        error: function (req, text, error) {
            $('.reg .auth_error').html('Ошибка регистрации. Попробуйте повторить.').parent().removeClass('displayNone');
            load.prototype.hide();
        },
        success: function (data) {
            if (data.error !== undefined) {
                $('.reg .auth_error').html(data.error).parent().removeClass('displayNone');
            }
            else {
                $('.auth input').val('');
                $('.reg .auth_error').html('<span style="color: rgba(0, 255, 0, 0.5);">Вы успешно зарегистрированы</span>').parent().removeClass('displayNone');
            }
            load.prototype.hide();
        },
        dataType: 'json',
    });
});

$('#enter').on('click', function (event) {
    $('.enter .auth_error').parent().addClass('displayNone');

    let login = $('.enter input[name="login"]:eq(0)').val().trim();
    let pass1 = $('.enter input[name="password"]:eq(0)').val().trim();

    if (!login.length) {
        $('.enter .auth_error').html('Логин не может быть пустым').parent().removeClass('displayNone');
        return;
    }
    else if (login.length < 3) {
        $('.enter .auth_error').html('Пароль не может быть короче 3 символов').parent().removeClass('displayNone');
        return;
    }
    else if (!pass1.length) {
        $('.enter .auth_error').html('Пароль не может быть пустым').parent().removeClass('displayNone');
        return;
    }
    else if (pass1.length < 8) {
        $('.enter .auth_error').html('Пароль не может быть короче 8 символов').parent().removeClass('displayNone');
        return;
    }

    load.prototype.show();

    $.ajax({
        type: 'POST',
        url: 'controllers/auth.php',
        data: {
            action: 'enter',
            login: login,
            pass: pass1,
        },
        error: function (req, text, error) {
            $('.enter .auth_error').html('Ошибка входа. Попробуйте повторить.').parent().removeClass('displayNone');
            load.prototype.hide();
        },
        success: function (data) {
            if (data.error !== undefined) {
                $('.enter .auth_error').html(data.error).parent().removeClass('displayNone');
                load.prototype.hide();
            }
            else {
                window.location.reload();
            }
        },
        dataType: 'json',
    });
});

let old = 0;
$('.selectstatus').on('focus', function (event) {
    old = $(this).val();
}).on('change', function (event) {
    let self = $(this);
    let id = +self.attr('attr-id');
    let val = +self.val();
    $.ajax({
        type: 'POST',
        url: 'controllers/filmsee.php',
        data: {
            action: 'change',
            id: id,
            val: val,
        },
        error: function (req, text, error) {
            self.val(old);
            alert('Ошибка. Не удалось добавить метку. Попробуйте повторить.');
        },
        success: function (data) {
            if (data.error !== undefined) {
                self.val(old);
                alert(data.error);
            }
            else {
                if (window.location.pathname.indexOf('my.php') != -1) {
                    self.parents('.film__list_item').remove();
                }
            }
        },
        dataType: 'json',
    });
});
