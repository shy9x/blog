<?php
    require_once "../vendor/autoload.php";
    use App\DB;
    session_start();
    if (isset($_SESSION["user_id"])) {
        $user_id = $_SESSION["user_id"];
        $name = $_POST["name"];
        $cover = $_POST["cover"];
        $text = $_POST["text"];
        $tags = explode(",", $_POST["tags"]);
        $tags = array_map(function ($tag) {
            return trim($tag);
        }, $tags);
        $tags = array_unique($tags);
        $tags = implode(",", $tags);
        $date = (new DateTime())->format("Y-m-d");
        $db = new DB();
        if ($db->add_post($date, $user_id, $name, $cover, $text, $tags)) {
            $_SESSION["message"] = "Пост добавлен";
        }
        else {
            $_SESSION["message"] = "Пост не добавлен";
        }
    }