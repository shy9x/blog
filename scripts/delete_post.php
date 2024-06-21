<?php
require_once "../vendor/autoload.php";
use App\DB;
session_start();
$user_id = $_SESSION["user_id"] ?? 0;

if (isset($_SESSION["user_id"], $_POST["post_id"])) {
    $db = new DB();
    $db->delete_post($_POST["post_id"]);
    $_SESSION["message"] = "Пост удален";
}

header("Location: " . $_SERVER["HTTP_REFERER"]);