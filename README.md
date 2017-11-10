.checkout
=========

A Symfony project created on November 7, 2017, 6:26 pm.

Установка:
Клонировать проект
```
git clone https://github.com/byvlad/Vantino
composer update
```
Указать данные для подключения БД
```
app/config/parameters.yml
```

Открыть терминал и вставить команды
```
php bin/console doctrine:database:create 
php bin/console doctrine:schema:update --force
```
Загрузить тестовые данные
```
php bin/console doctrine:fixtures:load  
```
Запустить проект
```
php bin/console server:run 
```
Данные для входа
```
Username: yavlados
Password: somepassword
```