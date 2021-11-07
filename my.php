<?php
session_start();
if (!isset($_GET["metka"]) || !isset($_SESSION["userid"])) {
    header("Location: index.php");
    exit();
}
$metka = $_GET["metka"];
if ($metka < 1 || $metka > 2) {
    header("Location: index.php");
    exit();
}
$title = "";
if ($metka == 1) {
    $title = "Хочу посмотреть";
}
else if ($metka == 2) {
    $title = "Посмотренно";
}
require_once 'classes/DB.php';
require_once 'functions/checkFilmsee.php';
$db = new DB();
$data = $db->getFilmseeMyMetka($_SESSION["userid"], $metka);
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
        <h2 class="title"><?= $title ?></h2>
        <div class="film__list">
            <?php
                foreach ($data as $film) {
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
                                            (strlen($film["nameEn"]) > 0 ? " (" . $film["nameEn"] . ")" : "")
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
                                <a class="link" href="film.php?id=<?= $film["filmId"] ?>">Подробнее</a>
                                <?= checkFilmsee($film["filmId"], [$film["filmId"] => $metka], $_SESSION["userid"]) ?>
                            </div>
                        </div>
                    </div>
            <?php
                }
            ?>
        </div>
    </div>
    <?php include "load.php"; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="assets/js/js.js?_=<?=time()?>"></script>
</body>
</html>