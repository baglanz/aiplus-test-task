<?php
session_start();

$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];

unset($_SESSION['errors']);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Log in</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

<div class="mx-auto max-w-7xl px-4 py-24 sm:px-6 sm:py-32 lg:px-40">
    <form action="/login.php" method="POST">
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <h2 class="text-base font-semibold leading-7 text-gray-900">Авторизация</h2>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-4">
                    <div class="sm:col-span-4">
                        <label for="username" class="block text-sm font-medium leading-6 text-gray-900">Username</label>
                        <div class="mt-2">
                            <input type="text"
                                   name="username"
                                   id="username"
                                   autocomplete="given-name"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                   value="<?= $_POST['username'] ?? '' ?>"
                                   placeholder="Ваш username"
                                   required
                            >
                        </div>
                        <?php if (isset($errors['username'])) : ?>
                            <p class="text-red-500 text-xs mt-2"><?= $errors['username'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="sm:col-span-4">
                        <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                        <div class="mt-2">
                            <input id="password"
                                   name="password"
                                   type="password"
                                   autocomplete="password"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                   placeholder="Ваш пароль"
                            >
                        </div>
                        <?php if (isset($errors['password'])) : ?>
                            <p class="text-red-500 text-xs mt-2"><?= $errors['password'] ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="mt-2 flex items-center justify-end gap-x-6">
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Войти</button>
            </div>
        </div>
    </form>
</div>

</body>
</html>