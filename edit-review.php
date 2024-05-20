<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

require 'Database.php';
$config = require 'config.php';

$data = new Database($config['database']);

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $query = $data->query("UPDATE reviews SET name = :name, email = :email, message = :message, is_edited = 1 WHERE id = :id", [
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'message' => $_POST['message'],
        'id' => $id
    ]);

    header('Location: admin.php');
    exit;
}

$reviews = $data->query("SELECT * FROM reviews WHERE id = :id", [
    'id' => $id
])->get();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Редактировать отзыв</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<h1>Редактировать отзыв</h1>
<form method="post">
    <?php foreach ($reviews as $review) : ?>
        <input type="text" name="name" value="<?= htmlspecialchars($review['name']); ?>" required>
        <input type="email" name="email" value="<?= htmlspecialchars($review['email']); ?>" required>
        <textarea name="message" required><?= htmlspecialchars($review['message']); ?></textarea>
    <?php endforeach; ?>
    <button type="submit">Сохранить изменения</button>
</form>
</body>
</html>
