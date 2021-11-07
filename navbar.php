<nav class="navbar">
    <div class="left">
        <a href="index.php">
            <img src="assets/favicon.png" alt="" width="48" height="48">
        </a>
        <form class="find" action="find.php" name="find" method="get">
            <input type="text" name="findstr" placeholder="Искать кино" autocomplete="off" value="<?= (isset($_GET["findstr"]) ? $_GET["findstr"] : "") ?>">
            <button type="success">Искать</button>
        </form>
    </div>
    <div class="right">
        <i class="profile">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 45.532 45.532"><path d="M22.766,0.001C10.194,0.001,0,10.193,0,22.766s10.193,22.765,22.766,22.765c12.574,0,22.766-10.192,22.766-22.765S35.34,0.001,22.766,0.001z M22.766,6.808c4.16,0,7.531,3.372,7.531,7.53c0,4.159-3.371,7.53-7.531,7.53c-4.158,0-7.529-3.371-7.529-7.53C15.237,10.18,18.608,6.808,22.766,6.808z M22.761,39.579c-4.149,0-7.949-1.511-10.88-4.012c-0.714-0.609-1.126-1.502-1.126-2.439c0-4.217,3.413-7.592,7.631-7.592h8.762c4.219,0,7.619,3.375,7.619,7.592c0,0.938-0.41,1.829-1.125,2.438C30.712,38.068,26.911,39.579,22.761,39.579z"/></svg>
        </i>
    </div>
</nav>
<?php
if (isset($_SESSION["userid"])) {
?>
<ul class="menu displayNone">
    <li>Ваш логин: <?= $_SESSION["userlogin"] ?></li>
    <li><a href="my.php?metka=1">Хочу посмотреть</a></li>
    <li><a href="my.php?metka=2">Посмотренно</a></li>
    <li><a href="controllers/auth.php?action=logout">Выход</a></li>
</ul>
<?php
}
else {
?>
<div class="auth displayNone">
    <div class="wind">
        <i class="close">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><rect width="24" height="24" transform="rotate(180 12 12)" opacity="0"/><path d="M13.41 12l4.3-4.29a1 1 0 1 0-1.42-1.42L12 10.59l-4.29-4.3a1 1 0 0 0-1.42 1.42l4.3 4.29-4.3 4.29a1 1 0 0 0 0 1.42 1 1 0 0 0 1.42 0l4.29-4.3 4.29 4.3a1 1 0 0 0 1.42 0 1 1 0 0 0 0-1.42z"/></svg>
        </i>
        <div class="enter displayNone">
            <div class="auth_item">
                <span>Логин</span>
                <input type="text" name="login" autocomplete="off" placeholder="Введите логин">
            </div>
            <div class="auth_item">
                <span>Пароль</span>
                <input type="password" name="password" autocomplete="off" placeholder="Введите пароль">
            </div>
            <div class="auth_item2">
                <span class="auth_error"></span>
            </div>
            <div class="auth_item2">
                <button class="btn" type="button" id="enter">Войти</button>
            </div>
            <div class="auth_item2">
                <a href="#!" class="link" attr-show="reg">Регистрация</a>
            </div>
        </div>
        <div class="reg displayNone">
            <div class="auth_item">
                <span>Логин</span>
                <input type="text" name="login" autocomplete="off" placeholder="Введите логин">
            </div>
            <div class="auth_item">
                <span>Пароль</span>
                <input type="password" name="password" autocomplete="off" placeholder="Введите пароль">
            </div>
            <div class="auth_item">
                <span>Повторите пароль</span>
                <input type="password" name="password2" autocomplete="off" placeholder="Введите пароль">
            </div>
            <div class="auth_item2">
                <span class="auth_error"></span>
            </div>
            <div class="auth_item2">
                <button class="btn" type="button" id="reg">Зарегистрироваться</button>
            </div>
            <div class="auth_item2">
                <a href="#!" class="link" attr-show="enter">Войти</a>
            </div>
        </div>
    </div>
</div>
<?php
}
?>