# ЭБС Лань PHP SDK
---

[![Build Status](https://scrutinizer-ci.com/g/spb-lan/ebs-sdk/badges/build.png?b=master)](https://scrutinizer-ci.com/g/spb-lan/ebs-sdk/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/spb-lan/ebs-sdk/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/spb-lan/ebs-sdk/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/spb-lan/ebs-sdk/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/spb-lan/ebs-sdk/?branch=master)

[![Latest Stable Version](https://poser.pugx.org/lan/ebs-sdk/v/stable.svg)](https://packagist.org/packages/lan/ebs-sdk)
[![Total Downloads](https://poser.pugx.org/lan/ebs-sdk/downloads)](https://packagist.org/packages/lan/ebs-sdk)
[![Latest Unstable Version](https://poser.pugx.org/lan/ebs-sdk/v/unstable.svg)](https://packagist.org/packages/lan/ebs-sdk)

Открытый API ЭБС Лань - RESTful API сервер, предназначенный для взаимодействия с информационными системами клиентов - подписчиков [ЭБС Лань](https://e.lanbook.com/).
Сервер поддерживает версию 2.0 OpenAPI Specification (так же известную как Swagger spec) - промышленный стандарт описания REST API интерфейсов. Подробнее про спецификацию - [OpenAPI Specification](https://github.com/OAI/OpenAPI-Specification/blob/master/versions/2.0.md)
PHP SDK для Открытого API ЭБС Лань позволяет быстро развернуть на сервере подписчиков ЭБС Лань полноценную интеграцию с API сервиса.

## Содержание:
---
1. [Установка](#_11)
2. [Автологин](#_24)
3. [Доступ к метаданным](#___56)

# Установка
---
Для загрузки и установки SDK Вы можете воспользоваться одним из 3-х вариантов:
 - вариант 1: Скачать https://github.com/spb-lan/ebs-sdk/archive/master.zip (классы придется подключать вручную)
 - вариант 2: Склонировать из репозитория git clone https://github.com/spb-lan/ebs-sdk.git (классы придется подключать вручную)
 - вариант 3 (предпочтительный): Через composer. "lan/ebs-sdk": "1.0.*"

### Инициализация клиента Api
Для авторизации на сервере ЭБС необходим токен, который выдается каждой организации индивидуально при подключении к сервису. Для первичного ознакомления с функционалом Вы можете использовать тестовый токен.
~~~
$token = '569f1f950afe79012fb9b8edffacc6fb6d6dac99d7103c51570cad1'; // токен для тестового подписчика
$client = new Client($token);
~~~
# Автологин
---
ЭБС Лань поддерживает автоматическую регистрацию и авторизацию читателей подписчика по специальным образом формированной ссылке:
* **Автоматическая регистрация** пользователя производится в случае, если пользователя с указанным ID в ЭБС Лань не зарегистрировано. В этом случае система прозрачно для пользовалетя создаст для него новый аккаунт и авторизует в системе.
* **Автоматическая авторизация** срабатывает для пользователей, у которых уже есть аккаунт с указанным ID, зарегистрированным в системе ранее при помощи автоматической регистрации.

**ВАЖНО!** Обратите внимание, что автоматическая авторизация по ссылке возможна только для пользователей, которые были зарегистрированы тем же способом (при помощи автоматической регистрации). Пользователи, зарегистрированные при помощи инструментов администрирования через API или самостоятельно через форму регистрации на сайте должны входить при помощи логина и пароля, указанных при регистрации. Попытка авторизовать таких пользователей через автологин приведет к созданию нового аккаунта, не связанного с существующим.

### Шаг 1. Получение объекта SDK
~~~
$security = new Security($client);
~~~
### Шаг 2. Получение URL для автологина
~~~
$url = $security->getAutologinUrl($uid, $fio, $email, $redirect);
~~~
##### Параметры
* **$uid = '12345';** - Идентификатор пользователя в вашей системе (id или логин, или любой другой уникальный) - *обязательный*
* **$fio = ‘Иванов Иван Иванович’;** - ФИО пользователя - *необязательный*
* **$email = ‘ivanov@example.com’;** - email пользователя - *необязательный*
* **$redirect = ‘/book/27’;** - Желаемая страница, после успешной регистрации/авторизации - *необязательный*

### Шаг 3. Формирование ссылки
~~~
try {
    echo '<a class="lan-ebs-autologin" href="' . $url .  '">ЭБС Лань</a>';
} catch (\Exception $e) {
    echo '<span class="lan-ebs-autologin">Сгенерировать ссылку для автологина в ЭБС Лань не удалось (' . $e->getMessage() . ')</span>';
}
~~~

# Доступ к метаданным
---
Доступ к метаданным позволяет посредством API получать информацию о книгах и журналах, доступных подписчику ЭБС Лань в рамках приобретенной подписки.
### Получение коллекции книг

