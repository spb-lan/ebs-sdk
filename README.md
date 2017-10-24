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
$token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
$client = new Client($token); // инициализация клиента
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
$token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
$client = new Client($token); // инициализация клиента

$limit = 5; // Ограничение на выборку данных (максимально 1000)
$offset = 0; // Смещение выборки данных 

$fields = [User::FIELD_LOGIN, User::FIELD_EMAIL, User::FIELD_FIO];

/**
 * Доступные поля:
 *      User::FIELD_LOGIN = 'login' - Логин пользователя
 *      User::FIELD_FIO = 'fio' - ФИО пользователя
 *      User::FIELD_EMAIL = 'email' - Email пользователя
 *      User::FIELD_REGISTERED = 'registeredAt' - Дата и время регистрации
 */
 
$userCollection = new UserCollection($client, $fields, $limit, $offset); // коллекция моделей пользователей

/** @var User $user */
foreach ($userCollection as $user) {
      echo $user->name;
}
```

### Получение пользователя

```php
$token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
$client = new Client($token); // инициализация клиента

$fields = [User::FIELD_LOGIN, User::FIELD_EMAIL, User::FIELD_FIO];

/**
 * Доступные поля:
 *      User::FIELD_LOGIN = 'login' - Логин пользователя
 *      User::FIELD_FIO = 'fio' - ФИО пользователя
 *      User::FIELD_EMAIL = 'email' - Email пользователя
 *      User::FIELD_REGISTERED = 'registeredAt' - Дата и время регистрации
 */
 
$user = new User($client, $fields);
$userInfo = $user->get($testUserPk);
```

### Создание пользователя

```php
$token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
$client = new Client($token); // инициализация клиента

$user = new User($client);
$user->post([
    'login' => 'new_user_login',
    'password' => 'new_user_password',
    'fio' => 'new_user_fio'
]);
```

### Изменение пароля

```php
$token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
$client = new Client($token); // инициализация клиента

$user = new User($client);
$user->setId($testUserPk);
$user->put([
    'fio' => 'user_new_fio',
    'password' => 'user_new_password',
]);
```

### Открепление пользователя

```php
$token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
$client = new Client($token); // инициализация клиента

$user = new User($client);
$user->setId($testUserPk);
$user->delete();
```

# Доступ к метаданным
---
Доступ к метаданным позволяет посредством API получать информацию о книгах и журналах, доступных подписчику ЭБС Лань в рамках приобретенной подписки.

### Получение коллекции книг

```php
$token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
$client = new Client($token); // инициализация клиента

$limit = 5; // Ограничение на выборку данных (максимально 1000)
$offset = 0; // Смещение выборки данных 

$fields = [Book::FIELD_NAME, Book::FIELD_AUTHORS, Book::FIELD_ISBN, Book::FIELD_YEAR, Book::FIELD_PUBLISHER]; // поля для выборки

/**
 * Доступные поля:
 *      Book::FIELD_NAME = 'name' - Наименование книги
 *      Book::FIELD_DESCRIPTION = 'description' - Описание книги
 *      Book::FIELD_ISBN = 'isbn' - ISBN книги
 *      Book::FIELD_YEAR = 'year' - Год издания книги
 *      Book::FIELD_EDITION = 'edition' - Издание
 *      Book::FIELD_PAGES = 'pages' - Объем книги
 *      Book::FIELD_SPECIAL_MARKS = 'specialMarks' - Специальные отметки
 *      Book::FIELD_CLASSIFICATION = 'classification' - Гриф
 *      Book::FIELD_AUTHORS = 'authors' - Авторы
 *      Book::FIELD_AUTHOR_ADDITIONS = 'authorAdditions' - Дополнительные авторы
 *      Book::FIELD_BIBLIOGRAPHIC_RECORD = 'bibliographicRecord' - Библиографическая запись
 *      Book::FIELD_CONTENT_QUALITY = 'contentQuality' - Качество текста книг (процент)
 *      Book::FIELD_PUBLISHER = 'publisher' - Издательство
 *      Book::FIELD_URL = 'url' - Ссылка на карточку книги
 *      Book::FIELD_THUMB = 'thumb' - Ссылка на обложку книги
 */

$bookCollection = new BookCollection($client, $fields, $limit, $offset); // коллекция моделей книг

/** @var Book $book */
foreach ($bookCollection as $book) {
      echo $book->name;
}
```

### Получение метаданных книги

```php
$token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
$client = new Client($token); // инициализация клиента

$fields = [Book::FIELD_NAME, Book::FIELD_AUTHORS, Book::FIELD_ISBN, Book::FIELD_YEAR, Book::FIELD_PUBLISHER]; // поля для выборки

/**
 * Доступные поля:
 *      Book::FIELD_NAME = 'name' - Наименование книги
 *      Book::FIELD_DESCRIPTION = 'description' - Описание книги
 *      Book::FIELD_ISBN = 'isbn' - ISBN книги
 *      Book::FIELD_YEAR = 'year' - Год издания книги
 *      Book::FIELD_EDITION = 'edition' - Издание
 *      Book::FIELD_PAGES = 'pages' - Объем книги
 *      Book::FIELD_SPECIAL_MARKS = 'specialMarks' - Специальные отметки
 *      Book::FIELD_CLASSIFICATION = 'classification' - Гриф
 *      Book::FIELD_AUTHORS = 'authors' - Авторы
 *      Book::FIELD_AUTHOR_ADDITIONS = 'authorAdditions' - Дополнительные авторы
 *      Book::FIELD_BIBLIOGRAPHIC_RECORD = 'bibliographicRecord' - Библиографическая запись
 *      Book::FIELD_CONTENT_QUALITY = 'contentQuality' - Качество текста книг (процент)
 *      Book::FIELD_PUBLISHER = 'publisher' - Издательство
 *      Book::FIELD_URL = 'url' - Ссылка на карточку книги
 *      Book::FIELD_THUMB = 'thumb' - Ссылка на обложку книги
 */

$book = new Book($client, $fields);
$metaDataBook = $book->get($bookId);
```

### Получение коллекции журналов

```php
$token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
$client = new Client($token); // инициализация клиента

$limit = 5; // Ограничение на выборку данных (максимально 1000)
$offset = 0; // Смещение выборки данных 

$fields = [Journal::FIELD_NAME, Journal::FIELD_ISSN, Journal::FIELD_PUBLISHER]; // поля для выборки

/**
 * Доступные поля:
 *      Journal::FIELD_NAME = 'name' - Наименование журнала
 *      Journal::FIELD_DESCRIPTION = 'description' - Описание журнала
 *      Journal::FIELD_ISSN = 'issn' - ISSN журнала
 *      Journal::FIELD_EISSN = 'eissn' - EISSN журнала
 *      Journal::FIELD_VAC = 'vac' - Входит в перечень ВАК
 *      Journal::FIELD_YEAR = 'year' - Год основания
 *      Journal::FIELD_ISSUES_PER_YEAR = 'issuesPerYear' - Выпусков в год
 *      Journal::FIELD_EDITORS = 'editors' - Редакторы
 *      Journal::FIELD_PUBLISHER = 'publisher' -  Издательство
 *      Journal::FIELD_URL = 'url' - Ссылка на карточку журнала
 */

$journalCollection = new JournalCollection($client, $fields, $limit, $offset); // коллекция моделей журналов

/** @var Journal $journal */
foreach ($journalCollection as $journal) {
      echo $journal->name;
}
```

### Получение метаданных журнала

```php
$token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
$client = new Client($token); // инициализация клиента

$fields = [Journal::FIELD_NAME, Journal::FIELD_ISSN, Journal::FIELD_PUBLISHER]; // поля для выборки

/**
 * Доступные поля:
 *      Journal::FIELD_NAME = 'name' - Наименование журнала
 *      Journal::FIELD_DESCRIPTION = 'description' - Описание журнала
 *      Journal::FIELD_ISSN = 'issn' - ISSN журнала
 *      Journal::FIELD_EISSN = 'eissn' - EISSN журнала
 *      Journal::FIELD_VAC = 'vac' - Входит в перечень ВАК
 *      Journal::FIELD_YEAR = 'year' - Год основания
 *      Journal::FIELD_ISSUES_PER_YEAR = 'issuesPerYear' - Выпусков в год
 *      Journal::FIELD_EDITORS = 'editors' - Редакторы
 *      Journal::FIELD_PUBLISHER = 'publisher' -  Издательство
 *      Journal::FIELD_URL = 'url' - Ссылка на карточку журнала
 */

$journal = new Journal($client, $fields);
$metaDataJournal = $journal->get($journalId);
```

### Получение коллекции выпусков журнала

```php
$token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
$client = new Client($token); // инициализация клиента

$limit = 5; // Ограничение на выборку данных (максимально 1000)
$offset = 0; // Смещение выборки данных 

$fields = [Issue::FIELD_NAME, Issue::FIELD_YEAR]; // поля для выборки

/**
 * Доступные поля:
 *      Issue::FIELD_NAME = 'name' - Номер выпуска
 *      Issue::FIELD_YEAR = 'year' - Год выпуска
 *      Issue::FIELD_URL = 'url' - Ссылка на карточку выпуска
 *      Issue::FIELD_THUMB = 'thumb' - Ссылка на обложку выпуска
 */

$issueCollection = new IssueCollection($client, $fields, $limit, $offset); // коллекция моделей выпусков

/** @var Issue $issue */
foreach ($issueCollection as $issue) {
      echo $issue->name;
}
```

### Получение метаданных выпуска журнала

```php
$token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
$client = new Client($token); // инициализация клиента

$fields = [Issue::FIELD_NAME, Issue::FIELD_YEAR]; // поля для выборки

/**
 * Доступные поля:
 *      Issue::FIELD_NAME = 'name' - Номер выпуска
 *      Issue::FIELD_YEAR = 'year' - Год выпуска
 *      Issue::FIELD_URL = 'url' - Ссылка на карточку выпуска
 *      Issue::FIELD_THUMB = 'thumb' - Ссылка на обложку выпуска
 */

$issue = new Issue($client, $fields);
$metaDataIssue = $issue->get($issueId);
```

### Получение коллекции статей выпуска

```php
$token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
$client = new Client($token); // инициализация клиента

$limit = 5; // Ограничение на выборку данных (максимально 1000)
$offset = 0; // Смещение выборки данных 

$fields = [Article::FIELD_NAME, Article::FIELD_AUTHORS]; // поля для выборки

/**
 * Доступные поля:
 *      Article::FIELD_NAME = 'name' - Наименование статьи
 *      Article::FIELD_AUTHORS = 'authors' - Авторы статьи
 *      Article::FIELD_DESCRIPTION = 'description' - Аннотация статьи
 *      Article::FIELD_KEYWORDS = 'keywords' - Ключевые слова статьи
 *      Article::START_PAGE = 'startPage' - Страница начала статьи
 *      Article::FINISH_PAGE = 'finish_page' - Страница окончания статьи
 *      Article::FFIELD_BIBLIOGRAPHIC_RECORD = 'bibliographicRecord' - Библиографическая запись
 */

$articleCollection = new ArticleCollection($client, $fields, $limit, $offset); // коллекция моделей статей

/** @var Article $article */
foreach ($articleCollection as $article) {
      $article->name;
}
```

### Получение метаданных статьи

```php
$token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
$client = new Client($token); // инициализация клиента

$fields = [Article::FIELD_NAME, Article::FIELD_AUTHORS]; // поля для выборки

/**
 * Доступные поля:
 *      Article::FIELD_NAME = 'name' - Наименование статьи
 *      Article::FIELD_AUTHORS = 'authors' - Авторы статьи
 *      Article::FIELD_DESCRIPTION = 'description' - Аннотация статьи
 *      Article::FIELD_KEYWORDS = 'keywords' - Ключевые слова статьи
 *      Article::START_PAGE = 'startPage' - Страница начала статьи
 *      Article::FINISH_PAGE = 'finish_page' - Страница окончания статьи
 *      Article::FFIELD_BIBLIOGRAPHIC_RECORD = 'bibliographicRecord' - Библиографическая запись
 */

$article = new Article($client, $fields);
$metaDataArticle = $article->get($articleId);
```

# Отчетность
---

### Статистика посещений

```php
$token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
$client = new Client($token); // инициализация клиента

$groupBy = Report::GROUP_BY_MONTH; // Группировка

/**
 * Доступные поля:
 *      Report::GROUP_BY_DAY = 'day' - По дням
 *      Report::GROUP_BY_MONTH = 'month' - По месяцам
 *      Report::GROUP_BY_YEAR = 'year' - По годам
 */

$report = new Report($client);
$userVisitStatistics = $report->getUsersVisitsStatistics($groupBy, '2017-07-01', '2017-08-28');
```

### Статистика чтения книг

```php
$token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
$client = new Client($token); // инициализация клиента

$groupBy = Report::GROUP_BY_MONTH; // Группировка

/**
 * Доступные поля:
 *      Report::GROUP_BY_DAY = 'day' - По дням
 *      Report::GROUP_BY_MONTH = 'month' - По месяцам
 *      Report::GROUP_BY_YEAR = 'year' - По годам
 */

$report = new Report($client);
$bookViewsStatistics = $report->getBooksViewsStatistics($groupBy, '2017-07-01', '2017-08-28');
```

### Статистика чтения журналов

```php
$token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
$client = new Client($token); // инициализация клиента

$groupBy = Report::GROUP_BY_MONTH; // Группировка

/**
 * Доступные поля:
 *      Report::GROUP_BY_DAY = 'day' - По дням
 *      Report::GROUP_BY_MONTH = 'month' - По месяцам
 *      Report::GROUP_BY_YEAR = 'year' - По годам
 */

$report = new Report($client);
$journalViewsStatistics = $report->getJournalsViewsStatistics($groupBy, '2017-07-01', '2017-08-28');
```

### Отчет о доступных книгах (по коллекциям)

```php
$token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
$client = new Client($token); // инициализация клиента

$report = new Report($client);
$availablePacketsStatistics = $report->getAvailablePackets();
```

### Отчет о доступных книгах - доступные книги в коллекции

```php
$token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
$client = new Client($token); // инициализация клиента

$packetId = 814392; // Идентификатор пакета

$report = new Report($client);
$availableBooksStatistics = $report->getAvailableBooks($packetId);
```

### Отчет о доступных журналах

```php
$token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
$client = new Client($token); // инициализация клиента

$report = new Report($client);
$availableJournalsStatistics = $report->getAvailableJournals();
```

# Формализованные отчеты
---

### Библиотечный фонд

```php
$token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
$client = new Client($token); // инициализация клиента

$report = new ReportForm($client);
$bibFond = $report->getBibFond();
```

### Электронные книги по направлениям подготовки

```php
$token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
$client = new Client($token); // инициализация клиента

$report = new ReportForm($client);
$ebooks = $report->getEBooks();
```

### Специальное ПО

```php
$token = '7c0c2193d27108a509abd8ea84a8750c82b3a520'; // токен для тестового подписчика
$client = new Client($token); // инициализация клиента

$report = new ReportForm($client);
$specPo = $report->getSpecPo();
```