<?php
require_once  __DIR__ . '/src/helpers.php';

?>
<!doctype html>
<html lang="ru">
<?php include_once __DIR__ . '/components/head.php'; ?>

<body>

<form class="card" action="src/actions/login.php" method="post">
    <h2>Вход</h2>


    <label for="email">
        Имя
        <input
        type="text"
        id="email"
        name="email"
        placeholder="isd@gmail.com"

        >
    </label>
    <label for="password">
        Пароль
        <input
        type="password"
        id="password"
        name="password"
        placeholder="******"
        >
    </label>

    <button
        type="submit"
        id="submit"
        >Продолжить</button>
</form>
<p> У меня еще нет <a href="/register.php">аккаунта</a> </p>
</body>
</html>