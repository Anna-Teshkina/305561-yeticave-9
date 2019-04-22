<?php 
// функция форматирования числа
function editNumber($number) {
	$number = ceil($number);
	$number = number_format($number, 0, ',', ' ');
	$number.= ' ₽';
	return $number;
}