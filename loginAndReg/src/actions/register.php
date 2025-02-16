<?php

require_once __DIR__ . '/../helpers.php';


// Выносим данных из $_POST в отдельные переменные
$avatarPath = null;
$name = $_POST['name'] ?? null;
$email = $_POST['email'] ?? null;
$password = $_POST['password'] ?? null;
$passwordConfirm = $_POST['password_confirmation'] ?? null;
$avatar = $_FILES['avatar'] ?? null;
// Выполняем валидацию полученных данных с формы
if (empty($name))
{
    setValidationError('name','Неверное имя');
}

if(!filter_var($email, FILTER_VALIDATE_EMAIL))
{
    setValidationError('email', 'Указана неправильная почта');
}

if (empty($password))
{
    setValidationError('password','Пароль пустой');
}

if ($password !== $passwordConfirm)
{
    setValidationError('password', 'Пароли не совпадают');
}

if (!empty($avatar))
{
    $types = ['image/png', 'image/jpeg'];

    if (!in_array($_FILES['avatar']['type'], $types))
    {
        setValidationError('avatar', 'Изображение профиля имеет неверный тип');

    }
    if (($avatar['size'] > 100000) >= 1)
    {
        setValidationError('avatar', 'Изображение должно быть меньше 1 мб');
    }
}
// Если список с ошибками валидации не пустой, то производим редирект обратно на форму


if (!empty($_SESSION['validation']))
{
    setOldValue('name',$name);
    setOldValue('email',$email);
    redirect('/register.php');
}

//  Загружаем аватарку, если она была отправлена в форме

if(!empty($avatar))
{
    $avatarPath = uploadFile($avatar, 'avatar');
}

$pdo = getPDO();

$query = "INSERT INTO users (name, email, avatar, password) 
VALUES (:name, :email, :avatar, :password)";

$params = [
    'name' => $name,
    'email' => $email,
    'avatar' => $avatarPath,
    'password' => password_hash($password, PASSWORD_DEFAULT)
];

$smt = $pdo->prepare($query);

try {
    $smt->execute($params);
}catch (PDOException $e){
    die($e->getMessage());
}
redirect('/');
