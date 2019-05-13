<?php
date_default_timezone_get("Europe/Moscow");

require_once('helpers.php'); // подключаем модуль со вспомогательными функциями
// require_once('data.php'); // подключаем модуль с переменными
require_once('functions.php'); // подключаем модуль с функциями
require_once('connect_db.php'); //выполним подключение к базе данных

$is_auth = rand(0, 1);
$user_name = 'Анна Тёшкина'; // укажите здесь ваше имя

// формируем запрос для получения списка категорий
// -------------------------------------------------------------------------------------
$equipment_type = get_category_list($con);

// сделаем так чтобы при отправке формы заполненные поля не очищались
$lot['name'] = $_POST['lot'] ?? '';
$lot['category'] = $_POST['lot[category]'] ?? '';
$lot['message'] = $_POST['lot[message]'] ?? '';
//$lot['img'] = $_POST['lot[img]'] ?? '';
$lot['rate'] = $_POST['lot[rate]'] ?? '';
$lot['step'] = $_POST['lot[step]'] ?? '';
$lot['date'] = $_POST['lot[date]'] ?? '';
$file = $_FILES['file'] ?? '';

// проверяем что сценарий был вызван отправкой формы
// если форма отправлена:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lot = $_POST['lot']; //получим данные из формы
    //var_dump($lot);
    //$required = ['lot[name]', 'lot[category]', 'lot[message]', 'lot[img]', 'lot[rate]', 'lot[step]', 'lot[date]'];

	$dict = [
        'lot[name]' => 'наименование лота',
        'lot[category]' => 'категорию',
        'lot[message]' => 'описание лота',
        //'lot[img]' => 'Изображение лота',
        'lot[rate]' => 'начальную цену',
        'lot[step]' => 'шаг ставки',
        'lot[date]' => 'дату завершения торгов'];

    $errors = []; // массив ошибок
    
    foreach ($lot as $key => $value) {
        //var_dump($key);
		if (empty($value)) {
            if ($errors[$key] = 'category') {
                $errors[$key] = "Выберите ".$dict["lot[category]"];
            }
            if ($errors[$key] = 'message') {
                $errors[$key] = "Напишите ".$dict["lot[message]"];
            }
            $errors[$key] = "Введите ".$dict["lot[$key]"];
            //echo "$key |";
        }
    }

    $lot['step'] = (integer) $lot['step']; // преобразуем введенный шаг ставки к целому числу
    if (!empty($lot['step']) && ((!is_int($lot['step'])) || ($lot['step'] <= 0))) {
        $errors['step'] = "Содержимое поля «шаг ставки» должно быть целым числом больше ноля";
    }

    if (!empty($lot['rate']) && ((!is_numeric($lot['rate'])) || ($lot['rate'] <= 0))) {
        $errors['rate'] = "Содержимое поля «начальная цена» должно быть числом больше нуля";
    }

    //$lot['date'] = "2019-05-11";
    // var_dump($lot['date']);
    // var_dump(is_date_valid($lot['date']));
    //var_dump(strtotime($lot['date']) - time());

    if (!empty($lot['date']) && (!is_date_valid($lot['date']))) {
        $errors['date'] = "Введите данные в верном формате: «ГГГГ-ММ-ДД»";
    }

    if (!empty($lot['date']) && (strtotime($lot['date']) - strtotime('tomorrow') < 0)) {
        $errors['date'] = "Указанная дата должна быть больше текущей, хотя бы на один день";
    }

    //var_dump($_FILES['file'] );
    if ($file['name'] != '') {
        //echo "gg <br>";
        $tmp_name = $_FILES['file']['tmp_name'];
        $path = $_FILES['file']['name'];
        $file_type = mime_content_type($tmp_name);

        // var_dump($tmp_name. "<br>");
        // var_dump($path . "<br>");
        // var_dump($file_type);
        // var_dump($file_type == "image/png");
        // var_dump($file_type == "image/jpeg");
        
		if (($file_type != "image/png") && ($file_type != "image/jpeg")) {
            //echo 'ERROR';
			$errors['file'] = 'Загрузите картинку в формате PNG, JPEG';
		} else {
            // echo "YEP";
			move_uploaded_file($tmp_name, 'uploads/' . $path);
            
           foreach ($equipment_type as $category):
                if ($category['name'] == $lot['category']) {
                    $lot['category'] = (integer) $category['id'];
                }
            endforeach;

            //var_dump($lot['category']);

            $lot['path'] = '/uploads/'. $path;
            //$lot['category'] = '15';
            // формируем SQL запрос на добавление нового лота
            $sql_lot = 'INSERT INTO lot (date_start, name, description, img, price_start, date_end, bet_step, author_id, category_id) VALUES (NOW(), ?, ?, ?, ?, ?, ?, 1, ?)';
            $stmt_lot = db_get_prepare_stmt($con, $sql_lot, [$lot['name'], $lot['message'], $lot['path'], $lot['rate'], $lot['date'], $lot['step'], $lot['category']]);
            $res_lot = mysqli_stmt_execute($stmt_lot);

            $lot_id = mysqli_insert_id($con); //id добавленного лота

            // формируем SQL запрос на добавление данных в массив ставок
            $sql_bet = 'INSERT INTO bet (date, user_id, user_price, lot_id) VALUES (NOW(), 1, ?, ?)';
            $stmt_bet = db_get_prepare_stmt($con, $sql_bet, [$lot['rate'], $lot_id]);
            $res_bet = mysqli_stmt_execute($stmt_bet);
            
            //var_dump($stmt);
            if ($res_lot && $res_bet) {
                header("Location: lot.php?id=" . $lot_id);
            }
		}
	} else {
		$errors['file'] = 'Вы не загрузили файл';
	}

    if (count($errors)) {
		$page_content = include_template('add.php', [
            'equipment_type' => $equipment_type,
            'lot' => $lot,
            'errors' => $errors,
            'dict' => $dict
        ]);
	}
} else {
    // если сценарий вызван не отправкой формы,
    // показываем пустую форму
	$page_content = include_template('add.php', [
        'equipment_type' => $equipment_type,
        'lot' => $lot
    ]);
}

$layout_content = include_template('layout.php', [
	'content' => $page_content,
    'title' => 'Добавление лота',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'equipment_type' => $equipment_type
]); 

print($layout_content);