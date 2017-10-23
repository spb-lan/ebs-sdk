# ЭБС Лань PHP SDK

[![Build Status](https://scrutinizer-ci.com/g/spb-lan/ebs-sdk/badges/build.png?b=master)](https://scrutinizer-ci.com/g/spb-lan/ebs-sdk/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/spb-lan/ebs-sdk/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/spb-lan/ebs-sdk/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/spb-lan/ebs-sdk/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/spb-lan/ebs-sdk/?branch=master)

[![Latest Stable Version](https://poser.pugx.org/lan/ebs-sdk/v/stable.svg)](https://packagist.org/packages/lan/ebs-sdk)
[![Total Downloads](https://poser.pugx.org/lan/ebs-sdk/downloads)](https://packagist.org/packages/lan/ebs-sdk)
[![Latest Unstable Version](https://poser.pugx.org/lan/ebs-sdk/v/unstable.svg)](https://packagist.org/packages/lan/ebs-sdk)

Открытый API ЭБС Лань - RESTful API сервер, предназначенный для взаимодействия с информационными системами клиентов - подписчиков [ЭБС Лань](https://e.lanbook.com/).

## Содержание:
---
1. [Установка](#_11)
2. [Автологин](#_24)
3. [Доступ к метаданным](#___56)

# Установка
---
Для загрузки и установки SDK Вы можете воспользоваться одним из 3-х вариантов:
 - вариант 1 (предпочтительный): Через composer. "lan/ebs-sdk": "1.0.*"
 - вариант 2: Скачать https://github.com/spb-lan/ebs-sdk/archive/master.zip (классы придется подключать вручную)
 - вариант 3: Склонировать из репозитория git clone https://github.com/spb-lan/ebs-sdk.git (классы придется подключать вручную)

### Инициализация клиента Api

Для авторизации на сервере ЭБС необходим токен, который выдается каждой организации индивидуально при подключении к сервису. Для первичного ознакомления с функционалом Вы можете использовать тестовый токен.

```php
$token = '569f1f950afe79012fb9b8edffacc6fb6d6dac99d7103c51570cad1'; // токен для тестового подписчика
$client = new Client($token);
```

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

##### Параметры
* **$uid = '12345';** - Идентификатор пользователя в вашей системе (id или логин, или любой другой уникальный) - *обязательный*
* **$fio = ‘Иванов Иван Иванович’;** - ФИО пользователя - *необязательный*
* **$email = ‘ivanov@example.com’;** - email пользователя - *необязательный*
* **$redirect = ‘/book/27’;** - Желаемая страница, после успешной регистрации/авторизации - *необязательный*

```php
try {
    echo '<a class="lan-ebs-autologin" href="' . $security->getAutologinUrl($uid, $fio, $email, $redirect) .  '">ЭБС Лань</a>';
} catch (\Exception $e) {
    echo '<span class="lan-ebs-autologin">Сгенерировать ссылку для автологина в ЭБС Лань не удалось (' . $e->getMessage() . ')</span>';
}
```

# Управление пользователями

### Получение списка пользователей

```php
$collection = new UserCollection($this->client, [], $limit);
```

### Получение пользователя

```php
$user = new User($this->client, [User::FIELD_LOGIN, User::FIELD_EMAIL, User::FIELD_FIO]);
$userInfp = $user->get($testUserPk);
```

### Создание пользователя

```php
$user = new User($this->client);
$user->post([
    'login' => 'new_user_login',
    'password' => 'new_user_password',
    'fio' => 'new_user_fio'
]);
```

### Изменение пароля

```php
$user = new User($this->client);
$user->setId($testUserPk);
$user->put([
    'fio' => 'user_new_fio',
    'password' => 'user_new_password',
]);
```

### Открепление пользователя

```php
$user = new User($this->client);
$user->setId($testUserPk);
$user->delete();
```

# Доступ к метаданным
---
Доступ к метаданным позволяет посредством API получать информацию о книгах и журналах, доступных подписчику ЭБС Лань в рамках приобретенной подписки.

### Получение коллекции книг

```php
$bookCollection = new BookCollection($this->client, [], $limit);
```

### Получение метаданных книги

```php
$book = new Book($this->client);
$metaDataBook = $book->get($bookId)
```

### Получение коллекции журналов

```php
$journalCollection = new JournalCollection($this->client, [], $limit);
```

### Получение метаданных журнала

```php
$journal = new Journal($this->client);
$metaDataJournal = $journal->get($journalId)
```

### Получение коллекции выпусков журнала

```php
$issueCollection = new IssueCollection($journalId, $this->client, [], $limit);
```

### Получение метаданных выпуска журнала

```php
$issue = new Issue($this->client);
$metaDataIssue = $issue->get($issueId)
```

### Получение коллекции статей выпуска

```php
$articleCollection = new ArticleCollection($journalId, $this->client, [], $limit);
```

### Получение метаданных статьи

```php
$article = new Article($this->client);
$metaDataArticle = $article->get($articleId)
```

# Отчетность
---

### Статистика посещений

### Статистика чтения книг

### Статистика чтения журналов

### Отчет о доступных книгах (по коллекциям)

### Отчет о доступных книгах - доступные книги в коллекции

### Отчет о доступных журналах

# Формализованные отчеты
---

### Библиотечный фонд

### Электронные книги по направлениям подготовки

### Специальное ПО
