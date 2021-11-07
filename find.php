<?php
session_start();
if (!isset($_GET["findstr"])) {
    header("Location: index.php");
    exit();
}
$findstr = $_GET["findstr"];
if (!strlen($findstr)) {
    header("Location: index.php");
    exit();
}
$page = 1;
if (isset($_GET["page"])) {
    $page = (int)$_GET["page"];
    if (!$page) {
        $page = 1;
    }
    $max = 20;
    if (isset($_GET["max"])) {
        $max = (int)$_GET["max"];
    }
    if ($max > 0) {
        if ($page > $max) {
            $page = $max;
        }
    }
}
require_once 'classes/Kinopoisk.php';
require_once 'classes/DB.php';
require_once 'functions/checkFilmsee.php';
$kino = new Kinopoisk();
$data = $kino->getFind($findstr, $page);
$ids = [];
foreach ($data["films"] as $film) {
    $ids[] = $film["filmId"];
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
        <h2 class="title">Поиск по: "<?= $data["keyword"] ?>"</h2>
        <div class="film__list">
            <?php
                foreach ($data["films"] as $film) {
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
                                <?= checkFilmsee($film["filmId"], $datadb, $_SESSION["userid"]) ?>
                            </div>
                        </div>
                    </div>
            <?php
                }
            ?>
        </div>
        <?php
            $pagesCount = (int)$data["pagesCount"];
            if ($pagesCount > 1) {
                $page = 1;
                if (isset($_GET["page"])) {
                    $page = (int)$_GET["page"];
                    if ($page < 1) {
                        $page = 1;
                    }
                    else if ($page > $pagesCount) {
                        $page = $pagesCount;
                    }
                }
                $findstr = "";
                if (isset($_GET["findstr"])) {
                    $findstr = $_GET["findstr"];
                }
                $urlprev = "#!";
                $classprev = "disabled";
                if ($page > 1) {
                    $urlprev = "find.php?findstr=" . $findstr .
                                "&page=" . ($page - 1) .
                                "&max=" . $pagesCount;
                    $classprev = "";
                }
                $urlnext = "#!";
                $classnext = "disabled";
                if ($page < $pagesCount) {
                    $urlnext = "find.php?findstr=" . $findstr .
                                "&page=" . ($page + 1) .
                                "&max=" . $pagesCount;
                    $classnext = "";
                }
            ?>
                <div class="pagination">
                    <form action="find.php" name="find" method="get" class="pagination_inputs">
                        <input type="text" name="findstr" hidden readonly autocomplete="off" value="<?= $findstr ?>">
                        <input type="text" name="page" placeholder="<?= $page ?>" autocomplete="off" value="">
                        <input type="text" name="max" hidden readonly autocomplete="off" value="<?= $data["pagesCount"] ?>">
                        <button type="success" hidden></button>
                        <span>из <?= $data["pagesCount"] ?></span>
                    </form>
                    <div class="pagination_arrow">
                        <a class="<?= $classprev ?>" href="<?= $urlprev ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 306 306" style="transform: rotate(180deg);"><polygon points="58.65,267.75 175.95,153 58.65,35.7 94.35,0 247.35,153 94.35,306"/></svg>
                        </a>
                        <a class="<?= $classnext ?>" href="<?= $urlnext ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 306 306"><polygon points="58.65,267.75 175.95,153 58.65,35.7 94.35,0 247.35,153 94.35,306"/></svg>
                        </a>
                    </div>
                </div>
        <?php
            }
        ?>
    </div>
    <?php include "load.php"; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="assets/js/js.js?_=<?=time()?>"></script>
</body>
</html>