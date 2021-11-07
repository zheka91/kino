<?php
session_start();
header("Content-Type: application/json; charset=utf-8");

$action = (isset($_POST["action"]) ? $_POST["action"] : (isset($_GET["action"]) ? $_GET["action"] : null));

require_once '../classes/DB.php';

if ($action == "reg") {
    if (!isset($_POST["login"]) || !isset($_POST["pass"])) {
        echo json_encode([
            "error" => "Введите логин и пароль",
        ]);
        exit();
    }

    $login = $_POST["login"];
    $pass = $_POST["pass"];

    $db = new DB();

    if ($res = $db->checkLogin($login)) {
        if (count($res) > 0) {
            if (!(int)$res[0]["cnt"]) {
                if ($db->addUser($login, $pass)) {
                    echo json_encode([
                        "ok" => true,
                    ]);
                }
                else {
                    echo json_encode([
                        "error" => "Ошибка регистрации. Попробуйте повторить.",
                    ]);
                }
            }
            else {
                echo json_encode([
                    "error" => "Введенный вами логин уже занят.",
                ]);
            }
        }
        else {
            echo json_encode([
                "error" => "Ошибка регистрации. Попробуйте повторить.",
            ]);
        }
    }
    else {
        echo json_encode([
            "error" => "Ошибка регистрации. Попробуйте повторить.",
        ]);
    }
}
else if ($action == "enter") {
    if (!isset($_POST["login"]) || !isset($_POST["pass"])) {
        echo json_encode([
            "error" => "Введите логин и пароль",
        ]);
        exit();
    }

    $login = $_POST["login"];
    $pass = $_POST["pass"];

    $db = new DB();

    if ($res = $db->checkUser($login, $pass)) {
        if (count($res) > 0) {
            $_SESSION["userid"] = $res[0]["id"];
            $_SESSION["userlogin"] = $res[0]["login"];
            $db->addAuthUser($_SESSION["userid"], $_SERVER);
            echo json_encode([
                "ok" => true,
            ]);
        }
        else {
            echo json_encode([
                "error" => "Неверный логин или пароль",
            ]);
        }
    }
    else {
        echo json_encode([
            "error" => "Ошибка входа. Попробуйте повторить.",
        ]);
    }
}
else if ($action == "logout") {
    unset($_SESSION['userid']);
    unset($_SESSION['userlogin']);
	header('Location: ../index.php');
	exit();
}
else {
    echo json_encode([
        "error" => "Неизвестное действие",
    ]);
}
