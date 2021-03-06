    mailout/tests/README.md

Модульные тесты для mailout
===========================

Этот каталог содержит модульные тесты для основных классов `mailout`, 
тестовую базу данных и данные для проверки. 

Тесты написаны на основе [phpunit][1].

Файл `phpunit.xml` содержит настройки для `phpunit`.

На момент написания данного README `mailout` проходит все модульные тесты.

Файлы модульных тестов
----------------------

Классы `RecipientsDBTest` и `RecipientsStatsTest` наследуют 
от `DatabaseTestCase`, дополнительного класса, который определяет обёртки 
для подключения к базе данных и для получения данных из YAML описания.
Подробности описаны в комментариях к `DatabaseTestCase.php`.

Файлы тестовых данных
---------------------

Все тестовые данные находятся в подкаталоге `fixtures/`. 
Они описаны в формате [YAML][2]. 

Следует помнить, что идеология проверки состояния базы данных в `phpunit`
заключается в *сравнении текущего состояния заданной таблицы с её ожидаемым 
состоянием*.

Файл тестовой базы данных
-------------------------

Тестовая база данных находится в файле `recipients.test.db`. Она должна быть
*идентична* по структуре основной базе данных. Даже другой порядок следования 
полей вызовет провал модульных тестов. Самый надёжный способ генерирования 
тестовой БД --- сделать копию основной базы данных, заменив название на 
`recipients.test.db`. Первый запуск модульных тестов сотрёт старые данные 
и заполнит БД тестовыми.


[1]: http://www.phpunit.de/manual/current/en/writing-tests-for-phpunit.html
[2]: http://www.yaml.org/


