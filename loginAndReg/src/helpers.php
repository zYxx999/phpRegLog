<?php
session_start();

require_once __DIR__.'/config.php';
function redirect(string $path)
{
 header("Location: {$path}");
 die;
}

function setValidationError(string $filedName, string $message):void

{
    $_SESSION['validatoin_error'][$filedName] = $message;
    
}

function hasValidationError(string $filedName):bool
{
    return isset($_SESSION['validatoin_error'][$filedName]);
}

function validationErrorAttr(string $filedName) : string
{
    return isset($_SESSION['validatoin'][$filedName]) ? 'aria-invalid="true"' : '';
}

function validationErrorMsg(string $filedName) :string

{
    $message = $_SESSION['validatoin_error'][$filedName] ?? '';
    unset($_SESSION['validatoin_error'][$filedName]);
    return $message;
}
function setOldValue(string $key, mixed $value):void
{
    $_SESSION[$key] = $value;
}

function old(string $key)
{
   $value = $_SESSION[$key][$key] ?? '';
   unset($_SESSION[$key]);
   return $value;
}

function uploadFile(array $file, string $prefix = ''): string

{
    $uploadPath = __DIR__ . "/../uploads/";

    if (!is_dir($uploadPath)) {
        mkdir($uploadPath);
    }

    $ext = pathinfo($file ['name'], PATHINFO_EXTENSION);
    $fileName = $prefix.'_'.time().'.'.$ext;

    if(!move_uploaded_file($file['tmp_name'], $uploadPath.$fileName)) {
        die('Ошибка при загрузке файла на сервер');
    }

    return "/uploads/$fileName";
}

function setMessage(string $key, string $message):void
{
    $_SESSION['message'][$key] = $message;
}
function hasMessage(string $key):bool
{
 return  isset($_SESSION['message'][$key]);
}
function getMessage(string $key): string
{
    $message = $_SESSION['message'][$key] ?? '';
    unset($_SESSION['message'][$key]);
    return $message;
}
function getPDO(): PDO
{
    try{
        return new \PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';charset=utf8;dbname=' . DB_NAME, DB_USERNAME, DB_PASSWORD);
    } catch (PDOException $e) {
        die("Connection error: {$e->getMessage()}");
    }
}
function findUser(string $email): array|bool
{
    $pdo = getPDO();

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
}
function currentUser(): array|false
{
    $pdo = getPDO();

    if (!isset($_SESSION['user'])) {
        return false;
    }

    $userId = $_SESSION['user']['id'] ?? null;

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $userId]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
}
function logout():void
{
    unset($_SESSION['user']['id']);
    redirect('/');
}
function checkAuth():void

{
    if(!isset($_SESSION['user']['id'])) {
        redirect('/');
    }
}
function checkGuest(): void
{
    if (isset($_SESSION['user']['id'])) {
        redirect('/home.php');
    }
}