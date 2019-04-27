INSERT INTO category
(name, img_key) VALUES 
    ('Доски и лыжи','boards'),
    ('Крепления','attachment'),
    ('Ботинки','boots'),
    ('Одежда','clothing'),
    ('Инструменты ','tools'),
    ('Разное','other');

INSERT INTO user
(registration_date, email, name, password, avatar, contacts) VALUES 
    ('26.04.2019','kuserrylov99@mail.ru','Иван Крылов','ivan_99','img/avatar-01.jpg','ул. Стражей Революции 9-45'), 
    ('26.04.2019','elenka1@yandex.ru','Лена Рылеева','elenka','img/avatar-02.jpg','ул. Березовая 7, корп.4, кв. 43');

INSERT INTO lot
(name, category_id, price_start, img, date_start, date_end, author_id) VALUES
    ('2014 Rossignol District Snowboard',1,10999,'img/lot-1.jpg','2019-01-01','2019-09-01', 1),
    ('DC Ply Mens 2016/2017 Snowboard',1,159999,'img/lot-2.jpg','2019-02-01','2019-04-20', 1),
    ('Крепления Union Contact Pro 2015 года размер L/XL',2,8000,'img/lot-3.jpg','2019-03-12','2019-04-13', 1),
    ('Ботинки для сноуборда DC Mutiny Charocal',3,10999,'img/lot-4.jpg','2019-04-15','2019-10-05', 2),
    ('Куртка для сноуборда DC Mutiny Charocal',4,7500,'img/lot-5.jpg','2019-02-25','2019-06-02', 2),
    ('Маска Oakley Canopy',6,5400,'img/lot-6.jpg','2019-02-25','2019-06-02', 2);

INSERT INTO bet
(user_id, user_price, lot_id ) VALUES (1,9000,3), (1,12000,4);

// Запрос-1::получить все категории
SELECT * FROM category;

// Запрос-2::получить самые новые, открытые лоты. Каждый лот должен включать название, 
//стартовую цену, ссылку на изображение, цену, название категории
SELECT name, price_start, img, category_id, bet.user_price FROM lot
JOIN bet
ON lot.id = bet.lot_id
WHERE CURRENT_TIMESTAMP < lot.date_end 
ORDER BY lot.date_start DESC 
LIMIT 20;

// Запрос-3::показать лот по его id. Получите также название категории, к которой принадлежит лот;
SELECT lot.*, category.name FROM lot
JOIN category ON lot.category_id = category.id
WHERE lot.id='1';

// Запрос-4::обновить название лота по его идентификатору;
UPDATE lot SET name='Маска Mutiny Charocal'
WHERE id='6';

// Запрос-5::получить список самых свежих ставок для лота по его идентификатору.
SELECT * FROM bet
JOIN lot
ON bet.lot_id = lot.id
WHERE lot.id = '3'
ORDER BY bet.date DESC 
LIMIT 20;


