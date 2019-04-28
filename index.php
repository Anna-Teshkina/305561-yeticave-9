<?php
date_default_timezone_get("Europe/Moscow");

require_once('helpers.php'); // подключаем модуль со вспомогательными функциями
// require_once('data.php'); // подключаем модуль с переменными
require_once('functions.php'); // подключаем модуль с функциями

//выполним подключение к базе данных
$con = mysqli_connect("localhost", "root", "","yeticave");
if ($con == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
} else {
    // print("Соединение установлено");

    // устанавливаем кодировку utf8
    mysqli_set_charset($con, "utf8");

    $is_auth = rand(0, 1);
    $user_name = 'Анна Тёшкина'; // укажите здесь ваше имя

    // выполнение запросов::

    // 1 -----------------------------------------------------------------------------------
    // формируем запрос для получения списка новых лотов
    // --------------------------------------------------------------------------------------
    $sql_lot = "SELECT lot_name, price_start, img, category_id, bet.user_price, category.category_name FROM lot
    JOIN bet
    ON lot.id = bet.lot_id
    JOIN category
    ON lot.category_id = category.id
    WHERE CURRENT_TIMESTAMP < lot.date_end 
    ORDER BY lot.date_start DESC 
    LIMIT 20;";
    $ad = get_array($con, $sql_lot);


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