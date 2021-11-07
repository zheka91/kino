<?php
session_start();
if (!isset($_GET["id"])) {
    header("Location: index.php");
    exit();
}
$id = (int)$_GET["id"];
if (!$id) {
    header("Location: index.php");
    exit();
}
require_once 'classes/Kinopoisk.php';
require_once 'classes/DB.php';
require_once 'functions/checkFilmsee.php';
$kino = new Kinopoisk();
$data = $kino->getFilm($id);
$ids = [];
if (count($data) > 0) {
    $ids[] = $data["kinopoiskId"];
}
$datadb = [];
if (count($ids) > 0 && isset($_SESSION["userid"])) {
    $db = new DB();
    foreach ($db->getFilmseeIds($ids, $_SESSION["userid"]) as $value) {
        $datadb[$value["filmId"]] = $value["metka"];
    }
}
?>

<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=10">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>КиноПросмотр</title>
	<link rel="shortcut icon" href="assets/favicon.png" type="image/png">
	<link rel="stylesheet" type="text/css" href="assets/css/css.css?_=<?=time()?>">
</head>
<body>
    <?php include "navbar.php"; ?>
    <div class="container">
        <?= (!count($data) ? '<h2 class="film_title error">Извините: данные по выбранному фильму отсутствуют в API.</h2>' : "") ?>
        <div class="descriptions">
            <div class="poster">
                <img src="<?= $data["posterUrlPreview"]?>" alt="" width="300">
            </div>
            <div class="text">
                <h2 class="film_title"><?= $data["nameRu"] ?></h2>
                <p class="film_title_en"><?= (strlen($data["nameEn"]) > 0 ? $data["nameEn"] : $data["nameOriginal"]) ?></p>
                <p class="film_short"><?= $data["shortDescription"]?></p>
                <?= checkFilmsee($data["kinopoiskId"], $datadb, $_SESSION["userid"]) ?>
                <?=
                    strlen($data["year"]) > 0 ? 
                    '<div class="film_item">
                        <p>Год производства</p>
                        <p>' . $data["year"] . '</p>
                    </div>' : 
                    ""
                ?>
                <?=
                    strlen($data["filmLength"]) > 0 ? 
                    '<div class="film_item">
                        <p>Продолжительность</p>
                        <p>' . $data["filmLength"] . ' мин.</p>
                    </div>' : 
                    ""
                ?>
                <?=
                    strlen($data["ratingKinopoisk"]) > 0 ? 
                    '<div class="film_item">
                        <p>Kinopoisk</p>
                        <p>' . $data["ratingKinopoisk"] . '</p>
                    </div>' : 
                    ""
                ?>
                <?=
                    strlen($data["ratingImdb"]) > 0 ? 
                    '<div class="film_item">
                        <p>IMDB</p>
                        <p>' . $data["ratingImdb"] . '</p>
                    </div>' : 
                    ""
                ?>
                <?=
                    strlen($data["countries"]) > 0 ? 
                    '<div class="film_item">
                        <p>Страна</p>
                        <p>' . $data["countries"] . '</p>
                    </div>' : 
                    ""
                ?>
                <?=
                    strlen($data["genres"]) > 0 ? 
                    '<div class="film_item">
                        <p>Жанр</p>
                        <p>' . $data["genres"] . '</p>
                    </div>' : 
                    ""
                ?>
                <?=
                    strlen($data["genres"]) > 0 ? 
                    '<div class="film_item_c">
                        <p>Обзор</p>
                        <p>' . $data["description"] . '</p>
                    </div>' : 
                    ""
                ?>
            </div>
        </div>
    </div>
    <?php include "load.php"; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="assets/js/js.js?_=<?=time()?>"></script>
</body>
</html>