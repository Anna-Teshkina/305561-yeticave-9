<?php 
/**
  * функция форматирования числа
  *
  * @param int $number исходное число
  * @return string $number строка, которая состоит из отформатированного исходного числа с добавлением знака рубля
  */
function get_editNumber($number) {
	$number = ceil($number);
	$number = number_format($number, 0, ',', ' ');
	$number.= ' ₽';
	return $number;
}

/**
  * функция форматирования даты
  *
  * @param timestamp $date
  * @return timestamp $date дата в корректном формате
  */
  function get_editDate($date) {
	$date_unix = strtotime($date);
	//print("date_unix: $date_unix <br>");
	$date_left_unix = time() - strtotime($date);
	//$now_unix = time();
	//$now = gmdate('d.m.y H:i', $now_unix);
	//print("date_left_unix: $date_left_unix <br>");
	//print("now: $now <br>");
	if ($date_left_unix < 3600) {
		$date = gmdate('i ', $date_left_unix).get_noun_plural_form(gmdate('i', $date_left_unix),'минуту','минуты','минут').' назад';
	} else {
		$date = gmdate('d.m.y в H:i', $date_unix);
	}

	return $date;
}

/**
  * функция которая отображает сколько времени осталось до конца торгов
  *
  * @param timestamp $finish_time время окончания торгов
  * @return timestamp $time_left сколько времени осталось до конца торгов
  */
function get_time_left($finish_time) {
	$time_left_unix = strtotime($finish_time) - time();
	$time_left = gmdate('H:i', $time_left_unix);
	//print("Осталось времени: $time_left <br>");

	return $time_left;
}

/**
  * в случае, если до конца торгов осталось меньше часа
  * функция возвращает true, в противном случае - false
  *
  * @param timestamp $finish_time время окончания торгов
  * @return bool $flag
  */
function is_timer_finishing($finish_time) {
	$time_left_unix = strtotime($finish_time) - time();
	$flag = 0;
	if ($time_left_unix <= 3600) {
		$flag = 1;
	}
	return $flag;
}

/**
  * функция отправляет запрос на чтение в базу данных и 
  * преобразует полученные данные в массив
  *
  * @param bool $database подключение к базе данных
  * @param string $query запрос
  * @return array  $array
  */
function get_array($database, $query) {
	/** отправляем запрос на чтение данных */
    $result = mysqli_query($database, $query);
    /** если запрос на чтение не успешен - возвращаем последнюю ошибку выполнения запроса */
    if (!$result) {
        $error = mysqli_error($database);
        print("Ошибка MySQL: " . $error);
	}
	/** в полученном ресурсе результата преобразуем полученные данные в массив */
	$array = mysqli_fetch_all($result, MYSQLI_ASSOC);
	return $array;
}

/**
  * формируем запрос для получения списка новых лотов
  *
  * @param bool $database подключение к базе данных
  * @param string $order_by сортировка полученных записекй
  * @param string $limit ограничение на кол-во выводимых записей
  * @return array $sql_lot массив новых лотов
  */
function get_lot_list($database, $order_by = "lot.date_start DESC", $limit = 20) {
	$sql_lot = "SELECT lot.id, lot.name as lot_name, price_start, img, date_end, category_id, bet.user_price, category.name as cat_name FROM lot
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

/**
  * формируем запрос для получения списка новых лотов
  *
  * @param bool $database подключение к базе данных
  * @return array $sql_category массив новых лотов
  */
  function get_category_list($database) {
	$sql_category = "SELECT * FROM category";
	$sql_category = get_array($database, $sql_category);
	return $sql_category;
}

/**
  * формируем запрос для получения информации о текущем лоте
  *
  * @param bool $database подключение к базе данных
  * @return array $current_lot текущий лот
  */
  function get_current_lot($database) {
	$id = mysqli_real_escape_string($database, $_GET['id']);

	$sql_lot = "SELECT lot.id, lot.name as lot_name, lot.description, price_start, img, date_end, category_id, bet.user_price, category.name as cat_name FROM lot
	JOIN bet
	ON lot.id = bet.lot_id
	JOIN category
	ON lot.category_id = category.id
	WHERE lot.id = $id";

	$current_lot = get_array($database, $sql_lot);
	$current_lot = $current_lot[0];
	return $current_lot;
}

/**
  * формируем запрос для получения информации о текущем лоте
  *
  * @param bool $database подключение к базе данных
  * @return array $bets масси ставок для текущего лота
  */
  function get_current_bets($database) {
	$id = mysqli_real_escape_string($database, $_GET['id']);

	$sql_bet = "SELECT bet.*, user.name as user_name FROM bet
    JOIN user
    ON user.id = bet.user_id
    WHERE lot_id = $id";

	$bets = get_array($database, $sql_bet);
	return $bets;
  }