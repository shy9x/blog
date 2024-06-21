<?php
    require_once "vendor/autoload.php";
    use App\DB;
    $db = new DB();
    session_start();
    $user_id = $_SESSION["user_id"] ?? 0;
    if (isset($_GET["id"])) {
        $post = $db->get_post_by_id($_GET["id"]);
        if (count($post)) {
            $db->post_views($post["Id"]);
            $post["Date"] = DateTime::createFromFormat("Y-m-d", $post["Date"])->format("d.m.Y");
            $post["Tags"] = explode(",", $post["Tags"]);
            $comments = $db->get_post_comments($post["Id"]);
            $comments = array_map(function ($comment){
                $comment["Date"] = DateTime::createFromFormat("Y-m-d", $comment["Date"])->format("d.m.Y");
                return $comment;
            }, $comments);
            $author = ($user_id === $post["User_id"]);
        }else {
            header("location: index.php");
            exit;
        }
    }
    else {
        header("location: index.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Блог</title>
    <link rel="stylesheet" href="assets/css/common.css">
    <link rel="stylesheet" href="assets/css/post.css">
</head>

<body>
<?php include "include/header.php"; ?>

    <main>
        <div class="inner_container">
            <div class="post">
                <div class="post_info">
                    <p class="username"><?= $post["User_name"] ?></p>
                    <p class="date"><?= $post["Date"] ?></p>
                </div>
                <?php if ($author): ?>
            <div class="button-delete">
                <form action = "scripts/delete_post.php" method="post">
                    <input type = "hidden" name="post_id" value="<?= $post["Id"] ?>">
                    <button id="delete">Удалить пост</button>
                </form>
            </div>
            <?php endif; ?>
                <h2><?= $post["Name"] ?></h2>
                <div class="cover">
                    <img src = "<?= $post["Cover"] ?>" alt = "">
                </div>
                <div class="content">
                    <?= $post["Content"] ?>
                </div>
                <div class="tags">
                    <p>
                        <strong>Теги:</strong>
                        <?php foreach ($post["Tags"] as $tag): ?>
                        <a href="index.php?filter=<?= $tag ?>"><?= $tag ?></a>
                        <?php endforeach; ?>
                    </p>
                </div>
                <div class="post_stats">
                    <div class="stat">
                        <img src="assets/images/view-simple-815-svgrepo-com.svg" alt="">
                        <p><?= $post["Views"] +1 ?></p>
                    </div>
                    <div class="stat">
                        <img src="assets/images/comment-alt-lines-svgrepo-com.svg" alt="">
                        <p><?= count($comments) ?></p>
                    </div>
                </div>
            </div>
            <div class="comments">
                <h2>Комментарии</h2>
            <?php if ($user_id): ?>
                <button id="show_form">Написать комментарий</button>
                <form id="comment_form" style="display: none" action="scripts/add_comment.php" method="post">
                    <input type="hidden" name="post_id" value="<?= $post["Id"] ?>">
                    <label for="comment">Введите ваш комментарий</label>
                    <textarea id="comment" name="comment" rows="10"></textarea>
                    <button>Добавить комментарий</button>
                </form>
            <?php endif; ?>
                <?php if (!count($comments)): ?>
                    <p>Здесь пока пусто...</p>
                <?php endif; ?>
                <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <div class="comment_info">
                        <p class="username"><?= $comment["User_name"] ?></p>
                        <p class="date"><?= $comment["Date"] ?></p>
                    </div>
                    <p><?= $comment["Text"] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <?php include "include/footer.php"; ?>

    <script src="assets/js/common.js"></script>
    <script src="assets/js/post.js"></script>
</body>

</html>