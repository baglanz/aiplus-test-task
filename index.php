<?php
session_start();
require 'Database.php';

$errors = $_SESSION['errors'] ?? [];

unset($_SESSION['errors']);

$sort_list = [
    'id_asc' => '`id`',
    'id_desc' => '`id` DESC',
    'name_asc' => '`name`',
    'name_desc' => '`name` DESC',
    'email_asc' => '`email`',
    'email_desc' => '`email` DESC',
    'message_asc' => '`message`',
    'message_desc' => '`message` DESC',
    'date_asc' => '`created_at`',
    'date_desc' => '`created_at` DESC',
];

$sort = $_GET['sort'] ?? null;
$sort_sql = '`created_at` DESC';
if (array_key_exists($sort, $sort_list)) {
    $sort_sql = $sort_list[$sort];
}

function sort_link_bar($title, $a, $b)
{
    $sort = @$_GET['sort'];

    if ($sort == $a) {
        return '<a class="active" href="?sort=' . $b . '">' . $title . ' <i>↑</i></a>';
    } elseif ($sort == $b) {
        return '<a class="active" href="?sort=' . $a . '">' . $title . ' <i>↓</i></a>';
    } else {
        return '<a href="?sort=' . $a . '">' . $title . '</a>';
    }
}


$config = require 'config.php';

$db = new Database($config['database']);

$reviews = $db->query("SELECT * FROM `reviews` WHERE is_approved = 1 ORDER BY {$sort_sql}")->get();

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
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
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                Отзывы
            </h2>
        </div>
        <?php if (!isset($_SESSION['admin'])) : ?>
        <div class="mt-5 flex lg:ml-4 lg:mt-0">
            <span class="ml-3 hidden sm:block">
            <button type="submit"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <a href="/session-create.php">Log in</a>
                <svg class="ml-0,5 mr-0,5 h-5 w-5  text-gray-800 dark:text-white" aria-hidden="true"
                     xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 12H5m14 0-4 4m4-4-4-4"/>
                </svg>
            </button>
            </span>
        </div>
        <?php endif; ?>
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
                            <?php echo sort_link_bar('ID', 'id_asc', 'id_desc'); ?>
                        </th>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            <?php echo sort_link_bar('Имя', 'name_asc', 'name_desc'); ?>
                        </th>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            <?php echo sort_link_bar('Email', 'email_asc', 'email_desc'); ?>
                        </th>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            <?php echo sort_link_bar('Сообщение', 'message_asc', 'message_desc'); ?>
                        </th>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            <?php echo sort_link_bar('Время добавления', 'date_asc', 'date_desc'); ?>
                        </th>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Действия администратора
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($reviews as $review) : ?>
                    <tr class="bg-gray-100 border-b">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            <?= $review['id']; ?>
                        </td>
                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                            <?= $review['name']; ?>
                        </td>
                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                            <?= $review['email']; ?>
                        </td>
                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                            <?= $review['message']; ?>
                        </td>
                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                            <?= $review['created_at']; ?>
                        </td>
                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                            <?= $review['is_edited'] ? 'Изменен администратором' : '' ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="mx-auto max-w-7xl px-4 py-24 sm:px-6 sm:py-32 lg:px-40">
    <form action="/review-create.php" method="POST" enctype="multipart/form-data">
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <h2 class="text-base font-semibold leading-7 text-gray-900">Написать отзыв</h2>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-4">
                    <div class="sm:col-span-4">
                        <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Имя</label>
                        <div class="mt-2">
                            <input type="text"
                                   name="name"
                                   id="name"
                                   autocomplete="given-name"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                   value="<?= $_POST['name'] ?? '' ?>"
                                   placeholder="Ваше имя"
                                   required
                            >
                        </div>
                        <?php if (isset($errors['name'])) : ?>
                            <p class="text-red-500 text-xs mt-2"><?= $errors['name'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="sm:col-span-4">
                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
                        <div class="mt-2">
                            <input id="email"
                                   name="email"
                                   type="email"
                                   autocomplete="email"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                   placeholder="your@email.com"
                                   value="<?= $_POST['email'] ?? '' ?>"
                            >
                        </div>
                        <?php if (isset($errors['email'])) : ?>
                            <p class="text-red-500 text-xs mt-2"><?= $errors['email'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="sm:col-span-4">
                        <label for="about" class="block text-sm font-medium leading-6 text-gray-900">Сообщение</label>
                        <div class="mt-2">
                            <textarea
                                    id="message"
                                    name="message"
                                    rows="3"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            ><?= $_POST['message'] ?? '' ?></textarea>
                        </div>
                        <?php if (isset($errors['message'])) : ?>
                            <p class="text-red-500 text-xs mt-2"><?= $errors['message'] ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="sm:col-span-4">
                        <div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
                                </svg>
                                <div class="mt-4 flex text-sm leading-6 text-gray-600">
                                    <label for="image" class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                                        <span>Загрузите изображение</span>
                                        <input id="image" name="image" type="file" class="sr-only">
                                    </label>
                                    <p class="pl-1">или перетащите сюда</p>
                                </div>
                                <p class="text-xs leading-5 text-gray-600">JPG, GIF, PNG up to 1MB</p>
                            </div>
                        </div>
                        <?php if (isset($errors['image'])) : ?>
                            <p class="text-red-500 text-xs mt-2"><?= $errors['image'] ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="mt-2 flex items-center justify-end gap-x-6">
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Отправить</button>
            </div>
        </div>
    </form>
</div>
</body>
</html>
