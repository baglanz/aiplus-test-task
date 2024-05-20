<?php

session_start();
// require 'config.php';
require 'Database.php';
require 'Validator.php';

$config = require 'config.php';

$data = new Database($config['database']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $errors = [];

    if (!Validator::string($_POST['name'], 1, 20)) {
        $errors['name'] = 'Поле имя должно быть не менее 1 и не более 20 символов';
    }

    if (!Validator::email('bob@example.com')) {
        $errors['email'] = 'Пожалуйста, введите корректный адрес';
    }

    if (!Validator::string($_POST['message'], 5, 100)) {
        $errors['message'] = 'Текст сообщения должен быть не менее 5 и не более 100 символов';
    }

    if (isset($_FILES['image'])) {
        $errors = [];
        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_type = $_FILES['image']['type'];
        $tmp = explode('.', $_FILES['image']['name']);
        $file_ext = strtolower(end($tmp));
        $file_destination = "images/" . $file_name;
        $extensions = ["jpg", "gif", "png"];

        // check image extension
        if (! in_array($file_ext, $extensions)) {
            $errors['image'] = 'Допустимые расширения: ' . implode(', ', $extensions);
        }

        if ($file_size > 1024 * 1024) {
            $errors['image'] = 'Файл не должен превышать 1 мб';
        }

        if (empty($errors)) {
            move_uploaded_file($_FILES["image"]["tmp_name"], 'images/'.$_FILES["image"]["name"]);
            echo 'Успешно загружено';
        }
    }

    if (! empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }

    if (empty($errors)) {
        $query = $data->query("INSERT INTO reviews (name, email, message, image_path) values (:name, :email, :message, :image)", [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'message' => $_POST['message'],
            'image' => 'images/' . $file_name,
        ]);
    }
}

echo 'Отзыв сохранен';
header('location: /');
exit();
