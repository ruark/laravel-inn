# Laravel INN Validation

Пакет для валидации ИНН (идентификационный номер налогоплательщика).

## Установка

```bash
composer require ruark/laravel-inn
```
## Service Provider

### Laravel (Опционально для Laravel 6.0+)

После установки или обновления пакета, необходимо зарегистрировать сервис-провайдер `InnServiceProvider`. Откройте конфигурационный файл `config/app.php` и добавьте в массив `providers` элемент:

```php
Ruark\LaravelInn\InnServiceProvider::class,
```

Laravel >= 6.0 регистрирует сервис-провайдеры автоматически.

### Lumen

Bootstrap

```php
$app->register(Ruark\LaravelInn\InnServiceProvider::class);
```

## Использование

### Валидация ИНН

Валидация ИНН проверяет строку на:

* Допустимые символы.
* Длину ИНН — 10 знаков для юридического лица, 12 знаков для физического лица и ИП.
* Проверка контрольного числа ИНН согласно алгоритму.

Для валидации используйте следующие правила:

| Правило | Комментарий                                                                                            |
|---------|--------------------------------------------------------------------------------------------------------|
| inn     | Валидация пройдет в случае, если во входящей строке содержится корректный ИНН.                         |
| inn:l   | Валидация пройдет в случае, если во входящей строке содержится корректный ИНН юридического лица.       |
| inn:i   | Валидация пройдет в случае, если во входящей строке содержится корректный ИНН физического лица или ИП. |

#### Пример

```php
$rules = [
  'inn' => 'required|inn'
];
$validator = Validator::make($request->all(), $rules);
```

#### Изменение текста ошибки 

Для изменения текста ошибки, добавьте в языковой файл `lang/**/validation.php` элемент со своим значением:

```php
'inn' => 'The :attribute has an invalid INN.',
```

## Тесты

`vendor/ruark/laravel-inn/tests/InnValidatorTest.php`

## Лицензия (MIT)

The MIT License (MIT). Please see [License File](LICENSE) for more information.

[link-author]: https://github.com/ruark
