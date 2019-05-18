<?php
//выполним подключение к базе данных
$con = mysqli_connect("localhost", "root", "","yeticave");

if (!$con) {
    print("Ошибка подключения: " . mysqli_connect_error());
    exit();
}

//print("Соединение установлено");

// устанавливаем кодировку utf8
mysqli_set_charset($con, "utf8");
session_start();