<?php
require_once('helpers.php'); // подключаем модуль со вспомогательными функциями

$is_auth = rand(0, 1);
$user_name = 'Анна Тёшкина'; // укажите здесь ваше имя

$equipment_type = ["Доски и лыжи", "Крепления", "Ботинки", "Одежда", "Инструменты", "Разное"]; // тип снаряжения
$ad = [
	[
		'name' => '2014 Rossignol District Snowboard',
		'category' => 'Доски и лыжи',
		'price' => 10999,
		'url_img' => 'img/lot-1.jpg'
	],
	[
		'name' => 'DC Ply Mens 2016/2017 Snowboard',
		'category' => 'Доски и лыжи',
		'price' => 159999,
		'url_img' => 'img/lot-2.jpg'
	],
	[
		'name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
		'category' => 'Крепления',
		'price' => 8000,
		'url_img' => 'img/lot-3.jpg'
	],
	[
		'name' => 'Ботинки для сноуборда DC Mutiny Charocal',
		'category' => 'Ботинки',
		'price' => 10999,
		'url_img' => 'img/lot-4.jpg'
	],
	[
		'name' => 'Куртка для сноуборда DC Mutiny Charocal',
		'category' => 'Одежда',
		'price' => 7500,
		'url_img' => 'img/lot-5.jpg'
	],
	[
		'name' => 'Маска Oakley Canopy',
		'category' => 'Разное',
		'price' => 5400,
		'url_img' => 'img/lot-6.jpg'
	]
];

// функция форматирования числа
function editNumber($number) {
	$number = ceil($number);
	$number = number_format($number, 0, ',', ' ');
	//$number.= ' ₽';
	return $number;
}

// функция защиты от XSS-атак
function esc($str) {
	$text = htmlspecialchars($str);
	//$text = strip_tags($str);
	return $text;
}

$page_content = include_template('index.php', [
    'equipment_type' => $equipment_type,
    'ad' => $ad
]);

$layout_content = include_template('layout.php', [
	'content' => $page_content,
    'title' => 'Главная',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'equipment_type' => $equipment_type
]); 

print($layout_content); ?>