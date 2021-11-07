<?php
session_start();
header("Content-Type: application/json; charset=utf-8");
if (!isset($_SESSION["userid"])) {
    echo json_encode([
        "error" => "Ошибка. Вы не авторизованы.",
    ]);
    exit();
}

$action = (isset($_POST["action"]) ? $_POST["action"] : (isset($_GET["action"]) ? $_GET["action"] : null));

require_once '../classes/Kinopoisk.php';
require_once '../classes/DB.php';

if ($action == "change") {
    if (!isset($_POST["id"]) || !isset($_POST["val"])) {
        echo json_encode([
            "error" => "Ошибка. Не удалось добавить метку. Попробуйте повторить.",
        ]);
        exit();
    }

    $id = (int)$_POST["id"];
    $val = (int)$_POST["val"];

    if (!$id) {
        echo json_encode([
            "error" => "Ошибка. Не удалось добавить метку. Попробуйте повторить.",
        ]);
        exit();
    }
    if ($val < 0 || $val > 2) {
        echo json_encode([
            "error" => "Ошибка. Не удалось добавить метку. Попробуйте повторить.",
        ]);
        exit();
    }

    $db = new DB();

    if (!$val) {
        if ($res = $db->deleteFilmsee($_SESSION["userid"], $id)) {
            echo json_encode([
                "ok" => "ok",
            ]);
        }
        else {
            echo json_encode([
                "error" => "Ошибка. Не удалось удалить метку. Попробуйте повторить.",
            ]);
        }
    }
    else {
        $kino = new Kinopoisk();
        $data = $kino->getFilm($id);

        if (!count($data)) {
            echo json_encode([
                "error" => "Ошибка. Не удалось добавить метку. Попробуйте повторить.",
            ]);
            exit();
        }

        $data["nameEn"] = (strlen($data["nameEn"]) > 0 ? $data["nameEn"] : $data["nameOriginal"]);

        if ($res = $db->checkFilmseeId($id, $_SESSION["userid"])) {
            if (count($res) > 0) {
                if ($res[0]["cnt"] > 0) {
                    if ($res = $db->updateFilmsee($_SESSION["userid"], $val, $data)) {
                        echo json_encode([
                            "ok" => "ok",
                        ]);
                    }
                    else {
                        echo json_encode([
                            "error" => "Ошибка. Не удалось добавить метку. Попробуйте повторить.",
                        ]);
                    }
                }
                else {
                    if ($res = $db->addFilmsee($_SESSION["userid"], $val, $data)) {
                        echo json_encode([
                            "ok" => "ok",
                        ]);
                    }
                    else {
                        echo json_encode([
                            "error" => "Ошибка. Не удалось добавить метку. Попробуйте повторить.",
                        ]);
                    }
                }
            }
            else {
                echo json_encode([
                    "error" => "Ошибка. Не удалось добавить метку. Попробуйте повторить.",
                ]);
            }
        }
        else {
            echo json_encode([
                "error" => "Ошибка. Не удалось добавить метку. Попробуйте повторить.",
            ]);
        }
    }
}
else {
    echo json_encode([
        "error" => "Неизвестное действие",
    ]);
}
