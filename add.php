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
if (!empty($_POST['lot[name]'])) { $lot['name'] = $_POST['lot[name]']; } else { $lot['name'] = ''; }
if (!empty($_POST['lot[category]'])) { $lot['category'] = $_POST['lot[category]']; } else { $lot['category'] = ''; }
if (!empty($_POST['lot[message]'])) { $lot['message'] = $_POST['lot[message]']; } else { $lot['message'] = ''; }
if (!empty($_POST['lot[rate]'])) { $lot['rate'] = $_POST['lot[rate]']; } else { $lot['rate'] = ''; }
if (!empty($_POST['lot[step]'])) { $lot['step'] = $_POST['lot[step]']; } else { $lot['step'] = ''; }
if (!empty($_POST['lot[date]'])) { $lot['date'] = $_POST['lot[date]']; } else { $lot['date'] = ''; }
if (!empty($_FILES['file'])) { $file = $_FILES['file']; } else { $file = ''; }

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

    $errors = []; // массив ошибок
    
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

    if (count($errors)) {
		$page_content = include_template('add.php', [
            'equipment_type' => $equipment_type,
            'lot' => $lot,
            'errors' => $errors,
            'dict' => $dict
        ]);
	} else {
        move_uploaded_file($tmp_name, 'uploads/' . $path);
        
        //приведем значение id категории к числу
        foreach ($equipment_type as $category):
            if ($category['name'] == $lot['category']) {
                $lot['category'] = (integer) $category['id'];
            }
        endforeach;

        $lot['path'] = '/uploads/'. $path;
        // формируем SQL запрос на добавление нового лота
        $res_lot = insert_lot_to_base($con, $lot);
        $lot_id = mysqli_insert_id($con); //id добавленного лота

        // формируем SQL запрос на добавление данных в массив ставок
        $res_bet = insert_bet_to_base($con, $lot, $lot_id);
        
        //var_dump($stmt);
        if ($res_lot && $res_bet) {
            header("Location: lot.php?id=" . $lot_id);
        }
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