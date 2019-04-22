<?php 
// функция форматирования числа
function editNumber($number) {
	$number = ceil($number);
	$number = number_format($number, 0, ',', ' ');
	$number.= ' ₽';
	return $number;
}

function time_left() {
	$time_left_unix = strtotime("tomorrow midnight") - time();
	$time_left = gmdate('H:i', $time_left_unix);
	//print("Осталось времени: $time_left <br>");

	return $time_left;
}