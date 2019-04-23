<?php 
// функция форматирования числа
function editNumber($number) {
	$number = ceil($number);
	$number = number_format($number, 0, ',', ' ');
	$number.= ' ₽';
	return $number;
}

function time_left($finish_time) {
	$time_left_unix = strtotime($finish_time) - time();
	$time_left = gmdate('H:i', $time_left_unix);
	//print("Осталось времени: $time_left <br>");

	return $time_left;
}

function time_class($finish_time) {
	$time_left_unix = strtotime($finish_time) - time();
	//print("Осталось времени: $time_left_unix <br>");
	if ($time_left_unix <= 3600) {
		return true;
	} else {
		return false;
	}
}