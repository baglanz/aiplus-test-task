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
$isApproved = $_GET['action'] === 'approve' ? 1 : 0;

$query = $data->query("UPDATE reviews SET is_approved = :is_approved WHERE id = :id", [
    'id' => $id,
    'is_approved' => $isApproved,
]);

header('Location: /admin.php');
?>