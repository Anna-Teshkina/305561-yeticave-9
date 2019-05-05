<?php
date_default_timezone_get("Europe/Moscow");

require_once('helpers.php'); // подключаем модуль со вспомогательными функциями
// require_once('data.php'); // подключаем модуль с переменными
require_once('functions.php'); // подключаем модуль с функциями

//выполним подключение к базе данных
$con = mysqli_connect("localhost", "root", "","yeticave");
if (!$con) {
    print("Ошибка подключения: " . mysqli_connect_error());
    exit();
} else {
    //print("Соединение установлено");

    // устанавливаем кодировку utf8
    mysqli_set_charset($con, "utf8");

    $is_auth = rand(0, 1);
    $user_name = 'Анна Тёшкина'; // укажите здесь ваше имя

    // выполнение запросов::

    // 1 -----------------------------------------------------------------------------------
    // формируем запрос для получения списка новых лотов
    // --------------------------------------------------------------------------------------
    $ad = lot_list($con, 'lot.date_start DESC', 20);
    
    // 2 -----------------------------------------------------------------------------------
    // формируем запрос для получения списка категорий
    // --------------------------------------------------------------------------------------
    $sql_category = "SELECT * FROM category";
    $equipment_type = get_array($con, $sql_category);
}

$page_content = include_template('index.php', [
    'equipment_type' => $equipment_type,
    'ad' => $ad
]);

$layout_content = include_template('layout.php', [
	'content' => $page_content,
    'title' => 'Главная',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'equipment_type' => $equipment_type
]); 

print($layout_content);