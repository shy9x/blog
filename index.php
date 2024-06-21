<?php
    require_once "vendor/autoload.php";
    use App\DB;
    $db = new DB();
    if (isset($_GET["filter"])) {
        $tag = $_GET["filter"];
        $posts = $db->get_filter_post($tag);
    }else {
        $posts = $db->get_all_posts();
    }
    $posts = array_map(function ($post) {
        $striped = strip_tags($post["Content"]);
        $post["Preview"] = mb_substr($striped, 0, 150). (mb_strlen($striped) > 150 ? "..." : "");
        $post["Date"] = DateTime::createFromFormat("Y-m-d", $post["Date"])->format("d.m.Y");
        return $post;
    }, $posts);

    $trending = $db->get_trending();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Блог</title>
    <link rel="stylesheet" href="assets/css/common.css">
    <link rel="stylesheet" href="assets/css/index.css">
</head>

<body>
    <?php include "include/header.php"; ?>

    <main>
        <div class="inner_container">
        <?php if (isset($tag)): ?>
            <div class="filter">
                <p>Все публикации по тегу: "<?= $tag  ?>"</p>
            </div>
        <?php endif; ?>
            <div class="posts">
                <?php foreach ($posts as $post): ?>
                <div class="post">
                    <div class="post_info">
                        <p class="username"><?= $post["User_name"] ?></p>
                        <p class="date"><?= $post["Date"] ?></p>
                    </div>
                    <h2><?= $post["Name"] ?></h2>
                    <?php if ($post["Cover"]): ?>
                    <div class="cover">
                        <img src="<?= $post["Cover"] ?>" alt="">
                    </div>
                    <?php endif; ?>
                    <p class="preview"><?= $post["Preview"] ?></p>
                    <a href="post.php?id=<?= $post["Id"] ?>">Читать далее</a>
                    <div class="post_stats">
                        <div class="stat">
                            <img src="assets/images/view-simple-815-svgrepo-com.svg" alt="">
                            <p><?= $post["Views"] ?></p>
                        </div>
                        <div class="stat">
                            <img src="assets/images/comment-alt-lines-svgrepo-com.svg" alt="">
                            <p><?= $post["Comments"] ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="trending">
                <h2>ЧИТАЮТ СЕЙЧАС</h2>
                <?php foreach ($trending as $post): ?>
                <a href="post.php?id=<?= $post["Id"] ?>" class="post">
                    <h2><?= $post["Name"] ?></h2>
                    <div class="post_stats">
                        <div class="stat">
                            <img src="assets/images/view-simple-815-svgrepo-com.svg" alt="">
                            <p><?= $post["Views"] ?></p>
                        </div>
                        <div class="stat">
                            <img src="assets/images/comment-alt-lines-svgrepo-com.svg" alt="">
                            <p><?= $post["Comments"] ?></p>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <?php include "include/footer.php"; ?>

    <script src="assets/js/common.js"></script>
</body>

</html>