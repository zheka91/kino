<?php
session_start();
require_once 'classes/Kinopoisk.php';
require_once 'classes/DB.php';
require_once 'functions/checkFilmsee.php';
$kino = new Kinopoisk();
$data = $kino->getPremiere(2021, 11);
$ids = [];
foreach ($data as $value) {
    foreach ($value as $film) {
        $ids[] = $film["kinopoiskId"];
    }
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
        <h2 class="title">Премьеры этого месяца:</h2>
        <div class="film__list">
            <?php
                $months = [
                    "январь",
                    "февраль",
                    "март",
                    "апрель",
                    "май",
                    "июнь",
                    "июль",
                    "август",
                    "сентябрь",
                    "октябрь",
                    "ноябрь",
                    "декабрь",
                ];
                foreach ($data as $dt => $value) {
                    $d = strtotime($dt);
            ?>
                    <div class="film__list_dt">
                        <span class="month"><?= $months[(int)date("m", $d) - 1] ?></span>
                        <span class="day"><?= (int)date("d", $d) ?></span>
                    </div>
            <?php
                    foreach ($value as $film) {
            ?>
                        <div class="film__list_item">
                            <div class="film__list_item_poster">
                                <img src="<?= $film["posterUrlPreview"] ?>" height="150" alt="Постер">
                            </div>
                            <div class="film__list_item_desc">
                                <div class="top">
                                    <p class="filmtitle">
                                            <?=
                                                $film["nameRu"] .
                                                (strlen($film["nameEn"]) > 0 ? " (" . $film["nameEn"] . "). " : ". ") .
                                                $film["duration"] .
                                                " мин."
                                            ?>
                                    </p>
                                    <p class="countri">
                                            <?=
                                                $film["countries"] .
                                                " (" . $film["year"] . ")"
                                            ?>
                                    </p>
                                    <p class="genres"><?= $film["genres"] ?></p>
                                </div>
                                <div class="bottom">
                                    <a class="link" href="film.php?id=<?= $film["kinopoiskId"] ?>">Подробнее</a>
                                    <?= checkFilmsee($film["kinopoiskId"], $datadb, $_SESSION["userid"]) ?>
                                </div>
                            </div>
                        </div>
            <?php
                    }
                }
            ?>
        </div>
    </div>
    <?php include "load.php"; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="assets/js/js.js?_=<?=time()?>"></script>
</body>
</html>