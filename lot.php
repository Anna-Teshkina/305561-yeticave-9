<?php
date_default_timezone_get("Europe/Moscow");

require_once('helpers.php'); // подключаем модуль со вспомогательными функциями
// require_once('data.php'); // подключаем модуль с переменными
require_once('functions.php'); // подключаем модуль с функциями
require_once('connect_db.php'); //выполним подключение к базе данных

$is_auth = rand(0, 1);
$user_name = 'Анна Тёшкина'; // укажите здесь ваше имя

$id = mysqli_real_escape_string($con, $_GET['id']);

$sql_lot = "SELECT lot.id, lot.name as lot_name, lot.description, price_start, img, date_end, category_id, bet.user_price, category.name as cat_name FROM lot
    JOIN bet
    ON lot.id = bet.lot_id
    JOIN category
    ON lot.category_id = category.id
    WHERE lot.id = $id";

// если выполнение запроса на чтение данных успешно, то
if ($result = mysqli_query($con, $sql_lot)) {
    // формируем запрос для получения списка категорий
    // --------------------------------------------------------------------------------------
    $equipment_type = get_category_list($con);

    
    if (!mysqli_num_rows($result)) {
        http_response_code(404);
        $page_content = include_template('404.php', [
            'equipment_type' => $equipment_type
        ]);
    } else {
        // 2 -----------------------------------------------------------------------------------
        // формируем запрос для получения информации о текущем лоте
        // --------------------------------------------------------------------------------------
        $ad = get_current_lot($con, $id);

        // 3 -----------------------------------------------------------------------------------
        // формируем запрос для получения списка ставок для текущего лота
        // --------------------------------------------------------------------------------------
        $bets = get_current_bets($con, $id);
        $num_bets = get_col_bets($con, $id);

        $page_content = include_template('lot.php', [
            'equipment_type' => $equipment_type,
            'ad' => $ad,
            'bets' => $bets,
            'num_bets' => $num_bets
        ]);
    }
} 
// если запрос на чтение не успешен - 
// возвращаем последнюю ошибку выполнения запроса
else {

    show_error($content, mysqli_error($link));
}

$layout_content = include_template('layout.php', [
	'content' => $page_content,
    'title' => 'Страница лота',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'equipment_type' => $equipment_type
]); 

print($layout_content);