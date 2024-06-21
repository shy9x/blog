<?php
    require_once "../vendor/autoload.php";
    use App\DB;
    session_start();
    $user_id = $_SESSION["user_id"] ?? 0;
    if ($user_id) {
        $comment = $_POST["comment"];
        $post_id = $_POST["post_id"];
        $db = new DB();
        $date = (new DateTime())->format("Y-m-d");
        if ($db->add_comment($user_id, $post_id, $comment, $date)) {
            $_SESSION["message"] = "Комментарий успешно добавлен";
        }else {
            $_SESSION["message"] = "При добавлении комментария произошла ошибка";
        }
    }
    header("Location: " . $_SERVER["HTTP_REFERER"]);