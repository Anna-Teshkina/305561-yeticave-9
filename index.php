<?php
date_default_timezone_get("Europe/Moscow");

require_once('helpers.php'); // подключаем модуль со вспомогательными функциями
// require_once('data.php'); // подключаем модуль с переменными
require_once('functions.php'); // подключаем модуль с функциями
require_once('connect_db.php'); //выполним подключение к базе данных

// $is_auth = rand(0, 1);
// $user_name = 'Анна Тёшкина'; // укажите здесь ваше имя

// выполнение запросов::

// 1 -----------------------------------------------------------------------------------
// формируем запрос для получения списка новых лотов
// -------------------------------------------------------------------------------------
$ad = get_lot_list($con);

// 2 -----------------------------------------------------------------------------------
// формируем запрос для получения списка категорий
// -------------------------------------------------------------------------------------
$equipment_type = get_category_list($con);

$page_content = include_template('index.php', [
    'equipment_type' => $equipment_type,
    'ad' => $ad
]);

$layout_content = include_template('layout.php', [
	'content' => $page_content,
    'title' => 'Главная',
    // 'is_auth' => $is_auth,
    // 'user_name' => $user_name,
    'equipment_type' => $equipment_type
]); 

print($layout_content);