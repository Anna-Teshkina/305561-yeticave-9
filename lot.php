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
    // формируем запрос для получения списка категорий
    // --------------------------------------------------------------------------------------
    $equipment_type = get_category_list($con);

    // 2 -----------------------------------------------------------------------------------
    // формируем запрос для получения информации о текущем лоте
    // --------------------------------------------------------------------------------------
    $ad = get_current_lot($con);
    
    // 3 -----------------------------------------------------------------------------------
    // формируем запрос для получения списка ставок для текущего лота
    // --------------------------------------------------------------------------------------
    $bets = get_current_bets($con);
}

if (empty($ad)) {
    $page_content = include_template('404.php', [
        'equipment_type' => $equipment_type
    ]);
} else {
    $ad = $ad[0];
    $page_content = include_template('lot.php', [
        'equipment_type' => $equipment_type,
        'ad' => $ad,
        'bets' => $bets
    ]);
}

$layout_content = include_template('layout.php', [
	'content' => $page_content,
    'title' => 'Страница лота',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'equipment_type' => $equipment_type
]); 

print($layout_content);