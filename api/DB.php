<?php

namespace App;

class DB {
    public $link;

    public function __construct() {
        $this->link = new \mysqli("localhost", "root", "", "pract_blog");
    }

    public function escape_all(&...$params) {
        foreach ($params as &$param) {
            $param = $this->link->real_escape_string($param);
        }
    }

    public function check_new_login($login) {
        $this->escape_all($login);
        $user = $this->link->query("SELECT * FROM `users` WHERE `Login` = '$login'");
        return $user && $user->num_rows;
    }

    public function add_user($login, $password, $name) {    
    $password = password_hash($password, PASSWORD_BCRYPT);
    $this->escape_all($login, $password, $name);
    $this->link->query("INSERT INTO `users` (`Login`, `Password`, `Name`) VALUES ('$login', '$password', '$name')");
    }

    public function get_user_by_login($login) {
        $this->escape_all($login);
        $user = $this->link->query("SELECT * FROM `users` WHERE `Login` = '$login'");
        if ($user && $user->num_rows) {
            return $user->fetch_assoc();
        }
        return [];
    }

    public function add_post($date, $user_id, $name, $cover, $text, $tags) {
        $this->escape_all($date, $user_id, $name, $cover, $text, $tags);
        $this->link->query(
            "INSERT INTO `posts` (`Name`, `Date`, `User_id`, `Cover`, `Content`, `Views`, `Tags`) 
                     VALUES ('$name', '$date', $user_id,'$cover', '$text', 0, '$tags')"
        );
        return $this->link->errno === 0;
    }

    public function get_all_posts() {
        $posts = $this->link->query(
            "SELECT `u`.`Name` AS `User_name`, `p`.* FROM `posts` `p`
                    LEFT JOIN `users` `u` on `u`.`Id` = `p`.`User_id`
                     ORDER BY `p`.`Id` DESC");
        if ($posts && $posts->num_rows) {
            $posts = $posts->fetch_all(MYSQLI_ASSOC);
            return array_map(function ($post) {
                $post["Comments"] = $this->link->query(
                    "SELECT * FROM `comments` WHERE `Post_id` = {$post["Id"]}")->num_rows;
                return $post;
            }, $posts);
        }
        return [];
    }

    public function get_filter_post($tag) {
        $this->escape_all($tag);
        $posts = $this->link->query(
            "SELECT `u`. `Name` AS `User_name`, `p`.* FROM `posts` `p`
                    LEFT JOIN `users` `u` on `u`.`Id` = `p`.`User_id`
                    WHERE `p`.`Tags` LIKE '%,$tag,%' OR `p`.`Tags` LIKE '$tag,%' OR `p`.`Tags` LIKE '%,$tag' OR `p`.`Tags` = '$tag'
                    ORDER BY `p`.`Id` DESC");
        if ($posts && $posts->num_rows) {
            $posts = $posts->fetch_all(MYSQLI_ASSOC);
            return array_map(function ($post) {
                $post["Comments"] = $this->link->query(
                    "SELECT * FROM `comments` WHERE `Post_id` = {$post["Id"]}")->num_rows;
                return $post;
            }, $posts);
        }
        return [];
    }

    public function get_post_by_id($id) {
        $this->escape_all($id);
        $post = $this->link->query(
            "SELECT `u`. `Name` AS `User_name`, `p`.* FROM `posts` `p`
                    LEFT JOIN `users` `u` on `u` .`Id` = `p`.`User_id` WHERE `p`. `Id` = $id");
        if ($post && $post->num_rows) {
            return $post->fetch_assoc();
        }
        return [];
    }

    public function get_post_comments($post_id) {
        $this->escape_all($post_id);
        $comments = $this->link->query(
            "SELECT `c`.*, `u`. `Name` AS `User_name` FROM `comments` `c`
                    LEFT JOIN `users` `u` on `u`. `Id` = `c`. `User_id`
                    WHERE `c`. `Post_id` = $post_id ORDER BY  `c`.`Id` DESC");
            if ($comments && $comments->num_rows) {
                return $comments->fetch_all(MYSQLI_ASSOC);
            }
            return [];
    }

    public function add_comment($user_id, $post_id,  $comment, $date) {
        $this->escape_all($user_id, $post_id,  $comment, $date);
        $this->link->query("INSERT INTO `comments` (`User_id`, `Post_id`, `Date`, `Text`) VALUES ($user_id, $post_id, '$date', '$comment')");
        return $this->link->errno === 0;
    }

    public function post_views($post_id) {
        $this->escape_all($post_id);
        $this->link->query("UPDATE `posts` SET `Views` = `Views` + 1 WHERE `Id` = $post_id");
    }

    public function get_trending() {
        $posts = $this->link->query("SELECT * FROM `posts` ORDER BY `Views` DESC LIMIT 0,3");
        if ($posts && $posts->num_rows) {
            $posts = $posts->fetch_all(MYSQLI_ASSOC);
            return array_map(function ($post) {
                $post["Comments"] = $this->link->query(
                    "SELECT * FROM `comments` WHERE `Post_id` = {$post["Id"]}")->num_rows;
                return $post;
            }, $posts);
        }
        return [];
    }

    public function search_post($query) {
        $this->escape_all($query);
        $posts = $this->link->query(
            "SELECT `u`.`Name` AS `User_name`, `p`.* FROM `posts` `p`
                    LEFT JOIN `users` `u` on `u`.Id = `p`.`User_id`
                    WHERE `p`.`Name` LIKE '%$query%'
                     ORDER BY `p`.`Id` DESC");
        if ($posts && $posts->num_rows) {
            $posts = $posts->fetch_all(MYSQLI_ASSOC);
            return array_map(function ($post) {
                $post["Comments"] = $this->link->query(
                    "SELECT * FROM `comments` WHERE `Post_id` = {$post["Id"]}")->num_rows;
                return $post;
            }, $posts);
        }
        return [];
    }

    public function delete_post($id) {
        $this->escape_all($id);
        $this->link->query("DELETE FROM `posts` WHERE `Id` = $id");
        return [];
    }
}