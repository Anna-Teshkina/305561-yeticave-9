<?php
date_default_timezone_get("Europe/Moscow");

require_once('helpers.php'); // подключаем модуль со вспомогательными функциями
// require_once('data.php'); // подключаем модуль с переменными
require_once('functions.php'); // подключаем модуль с функциями
require_once('connect_db.php'); //выполним подключение к базе данных

$is_auth = rand(0, 1);
$user_name = 'Анна Тёшкина'; // укажите здесь ваше имя
$equipment_type = get_category_list($con); // формируем запрос для получения списка категорий

// сделаем проверку на существование ключа id в массиве $_GET
if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
    $id = mysqli_real_escape_string($con, $_GET['id']); //получим значение ключа в массиве $_GET

    //если лота с заданным ключом не существует
    if (empty(get_lot_by_id($con, $id))) {
        http_response_code(404);
        $page_content = include_template('404.php', [
            'equipment_type' => $equipment_type
        ]);
    } else {
        //если лот с заданным ключом существует
        $ad = get_lot_by_id($con, $id); // формируем запрос для получения информации о текущем лоте
        $bets = get_bets_by_id($con, $id); // формируем запрос для получения списка ставок для текущего лота

        $page_content = include_template('lot.php', [
            'equipment_type' => $equipment_type,
            'ad' => $ad,
            'bets' => $bets,
        ]);
    }
} else {
    http_response_code(404);
    $page_content = include_template('404.php', [
        'equipment_type' => $equipment_type
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