<?php 
// функция форматирования числа
function editNumber($number) {
	$number = ceil($number);
	$number = number_format($number, 0, ',', ' ');
	$number.= ' ₽';
	return $number;
}

// функция которая отображает сколько времени осталось до конца торгов
function time_left($finish_time) {
	$time_left_unix = strtotime($finish_time) - time();
	$time_left = gmdate('H:i', $time_left_unix);
	//print("Осталось времени: $time_left <br>");

	return $time_left;
}

// в случае, если до конца торгов осталось меньше часа
// функция возвращает true, в противном случае - false
function is_timer_finishing($finish_time) {
	$time_left_unix = strtotime($finish_time) - time();
	$flag = 0;
	if ($time_left_unix <= 3600) {
		$flag = 1;
	}
	return $flag;
}

// функция отправляет запрос на чтение в базу данных и 
// преобразует полученные данные в массив
function get_array($database, $query) {
	// отправляем запрос на чтение данных
    $result = mysqli_query($database, $query);
    // если запрос на чтение не успешен - возвращаем последнюю ошибку выполнения запроса
    if (!$result) {
        $error = mysqli_error($database);
        print("Ошибка MySQL: " . $error);
	}
	// в полученном ресурсе результата преобразуем полученные данные в массив
	$array = mysqli_fetch_all($result, MYSQLI_ASSOC);
	return $array;
}

//формируем запрос для получения списка новых лотов
function lot_list($database, $order_by, $limit) {
	$sql_lot = "SELECT lot.name as lot_name, price_start, img, date_end, category_id, bet.user_price, category.name as cat_name FROM lot
	JOIN bet
	ON lot.id = bet.lot_id
	JOIN category
	ON lot.category_id = category.id
	WHERE CURRENT_TIMESTAMP < lot.date_end 
	ORDER BY $order_by 
	LIMIT $limit;";

	$sql_lot = get_array($database, $sql_lot);
	return $sql_lot;
}