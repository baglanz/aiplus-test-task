<?php

require 'Database.php';

session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

$config = require 'config.php';

$db = new Database($config['database']);

$reviews = $db->query("SELECT * FROM `reviews`")->get();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        td {
            border: 1px solid black;
            padding-left: 10px;
            padding-right: 10px;
            text-align: center;
            width: auto;
        }
    </style>
</head>
<body>
<div class="px-8 py-4">
    <div class="lg:flex lg:items-center lg:justify-between">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">Admin
                panel</h2>
        </div>
        <div class="mt-5 flex lg:ml-4 lg:mt-0">
            <span class="ml-3 hidden sm:block">
                <form action="/session-destroy.php">
                    <input type="hidden" name="_method" value="DELETE"/>
                    <button type="submit"
                            class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Log out
                        <svg class="ml-2 mr-1.5 h-5 w-5 text-gray-800 dark:text-white" aria-hidden="true"
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 8h11m0 0-4-4m4 4-4 4m-5 3H3a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h3"></path>
                        </svg>
                  </button>
                </form>
            </span>>
        </div>
    </div>
</div>

<div class="flex flex-col">
    <div class="overflow-x-auto sm:mx-0.5 lg:mx-0.5">
        <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
            <div class="overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-white border-b">
                    <tr>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            #
                        </th>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Имя
                        </th>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Email
                        </th>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Сообщение
                        </th>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Статус
                        </th>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                        </th>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Действие
                        </th>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Изображение
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($reviews as $review) : ?>
                        <tr class="bg-gray-100 border-b">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $review['id'] ?></td>
                            <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                <?= $review['name'] ?>
                            </td>
                            <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                <?= $review['email'] ?>
                            </td>
                            <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                <?= $review['message'] ?>
                            </td>
                            <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                <?php echo ($review['is_approved']) ? 'Принят' : 'Отклонен' ?>
                            </td>
                            <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                <a href="/edit-review.php?id=<?= $review['id'] ?>">
                                    <button type="button"
                                            class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                        <svg class="-ml-0.5 mr-1.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20"
                                             fill="currentColor" aria-hidden="true">
                                            <path d="M2.695 14.763l-1.262 3.154a.5.5 0 00.65.65l3.155-1.262a4 4 0 001.343-.885L17.5 5.5a2.121 2.121 0 00-3-3L3.58 13.42a4 4 0 00-.885 1.343z"/>
                                        </svg>
                                        Редактировать
                                    </button>
                                </a>
                            </td>
                            <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                <a href="/toggle-review.php?id=<?= $review['id'] ?>&action=<?= $review['is_approved'] ? 'reject' : 'approve' ?>">
                                    <button
                                            class="<?= $review['is_approved'] ? 'bg-red-700 hover:bg-red-800' : 'bg-indigo-600 hover:bg-indigo-500' ?> inline-flex items-center rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm"
                                    >
                                        <?php if ($review['is_approved']) : ?>
                                            <svg class="-ml-0.5 mr-1.5 h-5 w-5 text-gray-800 dark:text-white"
                                                 aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                                 height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                      stroke-linejoin="round" stroke-width="2"
                                                      d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                            </svg>
                                        <?php else : ?>
                                            <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor"
                                                 aria-hidden="true">
                                                <path fill-rule="evenodd"
                                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                      clip-rule="evenodd"/>
                                            </svg>
                                        <?php endif; ?>
                                        <?= $review['is_approved'] ? 'Отклонить' : 'Принять' ?>
                                    </button>
                                </a>
                            </td>
                            <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                <img style="height: 80px" width="80px" src="<?= $review['image_path'] ?>" alt="Image">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>