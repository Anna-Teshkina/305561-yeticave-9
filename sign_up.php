<?php
date_default_timezone_get("Europe/Moscow");

require_once('helpers.php'); // подключаем модуль со вспомогательными функциями
// require_once('data.php'); // подключаем модуль с переменными
require_once('functions.php'); // подключаем модуль с функциями
require_once('connect_db.php'); //выполним подключение к базе данных

$is_auth = rand(0, 1);
$user_name = 'Анна Тёшкина'; // укажите здесь ваше имя

// формируем запрос для получения списка категорий
// -------------------------------------------------------------------------------------
$equipment_type = get_category_list($con);

// сделаем так чтобы при отправке формы заполненные поля не очищались
$user['email'] = $_POST['user[email]'] ?? '';
$user['name'] = $_POST['user[name]'] ?? '';
$user['password'] = ''; // пароль не сохраняем
$user['message'] = $_POST['user[message]'] ?? '';

$dict = []; //словарь, если сценарий вызван не отправкой формы, то словарь пуст
$errors = []; // массив ошибок

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['user']; //получим данные из формы
    //var_dump($user);

    $dict = [
        'user[email]' => 'email',
        'user[name]' => 'имя',
        'user[password]' => 'пароль',
        'user[message]' => 'контактные данные'];

    foreach ($user as $key => $value) {
		if (empty($value)) {
            $errors[$key] = "Введите ".$dict["user[$key]"];
            if ($errors[$key] = 'message') {
                $errors[$key] = "Напишите как с вами связаться";
            }
        }
    }

    //var_dump($errors);
    // если массив ошибок пуст
    if (empty($errors)) {
        $email = mysqli_real_escape_string($con, $user['email']); //экранируем email введенный пользователем

        // проверим существование пользователя с email из формы
        // для этого выполним запрос на поиск записи в таблице пользователей по переданному email
        // и вернем кол-во обнаруженных записей
        $res = get_user_by_email($con, $email);

        // если запрос вернул больше нуля записей, значит такой пользователь уже существует
        if ($res > 0) {
            $errors['email'] = "Пользователь с этим email уже зарегистрирован";
        } else {
            $password = password_hash($user['password'], PASSWORD_DEFAULT); // пароль преобразуем в хеш
            $res = insert_user_to_base($con, $user, $password);
        }

        if ($res && empty($errors)) {
            header("Location: enter.php");
            exit();
        }
    }
}

$page_content = include_template('sign_up.php', [
    'equipment_type' => $equipment_type,
    'user' => $user,
    'errors' => $errors,
    'dict' => $dict
]);

$layout_content = include_template('layout.php', [
	'content' => $page_content,
    'title' => 'Регистрация пользователя',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'equipment_type' => $equipment_type
]); 

print($layout_content);