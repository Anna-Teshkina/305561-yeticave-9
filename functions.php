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
    //print("now_unix: ".date('d.m.y в H:i', $now_unix)."<br>");
    //print("date_left_unix: $date_left_unix <br>");
    if ($date_left_unix < 3600) {
      $date = date('i ', $date_left_unix).get_noun_plural_form(date('i', $date_left_unix),'минуту','минуты','минут').' назад';
    } else {
      $date = date('d.m.y в H:i', $date_unix);
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
  * преобразует полученные данные
  *
  * @param bool $database подключение к базе данных
  * @param string $query запрос
  * @param bool $flag (1 - преобразует полученные данные в массив, 0 - преобразует полученные данные в строку)
  * @return array/string $result
  */
  function get_result($database, $query, $flag) {
    /** отправляем запрос на чтение данных */
    $request = mysqli_query($database, $query);
    
    /** если запрос на чтение не успешен - возвращаем последнюю ошибку выполнения запроса */
    if (!$request) {
      $error = mysqli_error($database);
      print("Ошибка MySQL: " . $error);
    }
    
    if ($flag) {
      /** в полученном ресурсе результата преобразуем полученные данные в массив */
      $result = mysqli_fetch_all($request, MYSQLI_ASSOC);
    } else {
      /** в полученном ресурсе результата преобразуем полученные данные в строку */
      $result = mysqli_fetch_assoc($request);
    }
    return $result;
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

    $sql_lot = get_result($database, $sql_lot,1);
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
    $sql_category = get_result($database, $sql_category,1);
    return $sql_category;
  }

/**
  * формируем запрос для получения информации о текущем лоте
  *
  * @param bool $database подключение к базе данных
  * @return string $current_lot текущий лот
  */
  function get_current_lot($database, $id) {
    $sql_lot = "SELECT lot.id, lot.name as lot_name, lot.description, price_start, img, date_end, category_id, bet.user_price, category.name as cat_name FROM lot
    JOIN bet
    ON lot.id = bet.lot_id
    JOIN category
    ON lot.category_id = category.id
    WHERE lot.id = $id";

    $current_lot = get_result($database, $sql_lot,0);
    return $current_lot;
  }

/**
  * формируем запрос для получения информации о текущем лоте
  *
  * @param bool $database подключение к базе данных
  * @return array $bets масси ставок для текущего лота
  */
  function get_current_bets($database, $id) {
    $sql_bet = "SELECT bet.*, user.name as user_name FROM bet
      JOIN user
      ON user.id = bet.user_id
      WHERE lot_id = $id";

    $bets = get_result($database, $sql_bet,1);
    return $bets;
  }