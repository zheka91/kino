<?php
function checkFilmsee($id, $filmsee, $user) {
    if (!isset($user)) {
        return "";
    }
    $arr = [
        0 => "Не смотрел(а)",
        1 => "Хочу посмотреть",
        2 => "Посмотренно",
    ];
    $str = "<select class='selectstatus' attr-id='{$id}'>";
    if (array_key_exists($id, $filmsee)) {
        foreach ($arr as $key => $value) {
            if ($key == $filmsee[$id]) {
                $str .= "<option value='{$key}' selected>{$value}</option>";
            }
            else {
                $str .= "<option value='{$key}'>{$value}</option>";
            }
        }
    }
    else {
        foreach ($arr as $key => $value) {
            $str .= "<option value='{$key}'>{$value}</option>";
        }
    }
    $str .= "</select>";
    return $str;
}
