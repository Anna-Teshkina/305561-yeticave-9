<?php
date_default_timezone_get("Europe/Moscow");

require_once('helpers.php'); // подключаем модуль со вспомогательными функциями
// require_once('data.php'); // подключаем модуль с переменными
require_once('functions.php'); // подключаем модуль с функциями
require_once('connect_db.php'); //выполним подключение к базе данных

$is_auth = 0; // флаг: true - пользователь зарегистрирован, false - не зарегистрирован
// $is_auth = rand(0, 1);
// $user_name = 'Анна Тёшкина'; // укажите здесь ваше имя

// формируем запрос для получения списка категорий
// -------------------------------------------------------------------------------------
$equipment_type = get_category_list($con);

// сделаем так чтобы при отправке формы заполненные поля не очищались
$login['email'] = $_POST['user[email]'] ?? '';
$login['password'] = ''; // пароль не сохраняем

$dict = []; //словарь, если сценарий вызван не отправкой формы, то словарь пуст
$errors = []; // массив ошибок

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login']; //получим данные из формы

    $dict = [
        'login[email]' => 'email',
        'login[password]' => 'пароль'];
    
    // проверим что все поля заполнены
    foreach ($login as $key => $value) {
		if (empty($value)) {
            $errors[$key] = "Введите ".$dict["login[$key]"];
        }
    }

    // если массив ошибок пуст
    if (empty($errors)) {
        $email = mysqli_real_escape_string($con, $login['email']); //экранируем email введенный пользователем
        // проверим существование пользователя с email из формы
        // для этого выполним запрос на поиск записи в таблице пользователей по переданному email
        $user = get_user_by_email($con, $email);
        if ($user) {
            // проверяем, что сохраненный хеш пароля и введенный пароль из формы совпадают
            // если совпадение есть, значит пользователь указал верный пароль
            // тогда мы можем открыть для него сессию и записать в неё все данные о пользователе

            if (password_verify($login['password'], $user['password'])) {
                $_SESSION['user']['name'] = $user['name'];
                header("Location: /");
		        exit();
            } else {
                $errors['password'] = 'Вы ввели неверный пароль';
            }
        } else {
            $errors['email'] = 'Такой пользователь не найден';
        }
    }
} else {
    if (isset($_SESSION['user'])) {
        $page_content = include_template('index.php', [
            'user_name' => $_SESSION['user']['name']
        ]);
    }
    else {
        $page_content = include_template('login.php', []);
    }
}

$page_content = include_template('login.php', [
    'equipment_type' => $equipment_type,
    'login' => $login,
    'errors' => $errors,
    'dict' => $dict
]);

$layout_content = include_template('layout.php', [
	'content' => $page_content,
    'title' => 'Вход на сайт',
    //'is_auth' => $is_auth,
    //'user_name' => $user_name,
    'equipment_type' => $equipment_type
]); 

print($layout_content);