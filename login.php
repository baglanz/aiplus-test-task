<?php
session_start();
if (isset($_SESSION['admin'])) {
    header('Location: admin.php');
    exit;
}

require 'config.php';
require 'Database.php';
require 'Validator.php';

$config = require 'config.php';

$data = new Database($config['database']);

$username = $_POST['username'];
$password = $_POST['password'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $errors = [];

    if (!Validator::string($_POST['username'])) {
        $errors['username'] = 'Такого username не существует.';
    }

    if (!Validator::string($_POST['password'])) {
        $errors['password'] = 'Пожалуйста, введите правильный пароль.';
    }

    if (! empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}

$user = $data->query('select * from admin where username = :username', [
    'username' => $username,
])->find();

if ($user) {
    if (password_verify($password, $user['password'])) {
        $_SESSION['admin'] = [
            'password' => $user['password']
        ];

        header('location: /admin.php');
        exit();
    }
}

$errors['username'] = 'Такой аккаунт не найден';
$_SESSION['errors'] = $errors;
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
