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
$lot['rate'] = $_POST['lot[rate]'] ?? '';
$lot['step'] = $_POST['lot[step]'] ?? '';
$lot['date'] = $_POST['lot[date]'] ?? '';
$file = $_FILES['file'] ?? '';

$dict = []; //словарь, если сценарий вызван не отправкой формы, то словарь пуст
$errors = []; // массив ошибок

// проверяем что сценарий был вызван отправкой формы
// если форма отправлена:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lot = $_POST['lot']; //получим данные из формы

    $dict = [
        'lot[name]' => 'наименование лота',
        'lot[category]' => 'категорию',
        'lot[message]' => 'описание лота',
        'lot[rate]' => 'начальную цену',
        'lot[step]' => 'шаг ставки',
        'lot[date]' => 'дату завершения торгов'];
    
    foreach ($lot as $key => $value) {
		if (empty($value)) {
            if ($errors[$key] = 'category') {
                $errors[$key] = "Выберите ".$dict["lot[category]"];
            }
            if ($errors[$key] = 'message') {
                $errors[$key] = "Напишите ".$dict["lot[message]"];
            }
            $errors[$key] = "Введите ".$dict["lot[$key]"];
        }
    }

    $lot['step'] = (integer) $lot['step']; // преобразуем введенный шаг ставки к целому числу
    if (!empty($lot['step']) && ((!is_int($lot['step'])) || ($lot['step'] <= 0))) {
        $errors['step'] = "Содержимое поля «шаг ставки» должно быть целым числом больше ноля";
    }

    if (!empty($lot['rate']) && ((!is_numeric($lot['rate'])) || ($lot['rate'] <= 0))) {
        $errors['rate'] = "Содержимое поля «начальная цена» должно быть числом больше нуля";
    }

    if (!empty($lot['date']) && (!is_date_valid($lot['date']))) {
        $errors['date'] = "Введите данные в верном формате: «ГГГГ-ММ-ДД»";
    }

    if (!empty($lot['date']) && (strtotime($lot['date']) - strtotime('tomorrow') < 0)) {
        $errors['date'] = "Указанная дата должна быть больше текущей, хотя бы на один день";
    }

    //var_dump($_FILES['file'] );
    if ($file['name'] != '') {
        $tmp_name = $_FILES['file']['tmp_name'];
        $path = $_FILES['file']['name'];
        $file_type = mime_content_type($tmp_name);
        
		if (($file_type != "image/png") && ($file_type != "image/jpeg")) {
			$errors['file'] = 'Загрузите картинку в формате PNG, JPEG';
		}
	} else {
		$errors['file'] = 'Вы не загрузили файл';
	}
    //var_dump($lot);
    if (!count($errors)) {
		move_uploaded_file($tmp_name, 'uploads/' . $path);

        $lot['category'] = (integer) $lot['category'];
        $lot['path'] = '/uploads/'. $path;

        // формируем SQL запрос на добавление нового лота и возвращаем id добавленного лота
        $lot_id = insert_lot_to_base($con, $lot);

        // формируем SQL запрос на добавление данных в массив ставок
        // $res_bet = insert_bet_to_base($con, $lot, $lot_id);

        header("Location: lot.php?id=" . $lot_id);
    }
}

$page_content = include_template('add.php', [
    'equipment_type' => $equipment_type,
    'lot' => $lot,
    'errors' => $errors,
    'dict' => $dict
]);

$layout_content = include_template('layout.php', [
	'content' => $page_content,
    'title' => 'Добавление лота',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'equipment_type' => $equipment_type
]); 

print($layout_content);