# Installation

Добавить в kernel

    Evrinoma\ContactBundle\EvrinomaContactBundle::class => ['all' => true],
    Evrinoma\AddressBundle\EvrinomaAddressBundle::class => ['all' => true],

Добавить в routes

    contact:
        resource: "@EvrinomaContactBundle/Resources/config/routes.yml"
    address:
        resource: "@EvrinomaAddressBundle/Resources/config/routes.yml"

Добавить в composer

    composer config repositories.dto vcs https://github.com/evrinoma/DtoBundle.git
    composer config repositories.dto-common vcs https://github.com/evrinoma/DtoCommonBundle.git
    composer config repositories.utils vcs https://github.com/evrinoma/UtilsBundle.git

    composer config repositories.address vcs https://github.com/evrinoma/AddressBundle.git

# Configuration

преопределение штатного класса сущности

    contact:
        db_driver: orm модель данных
        factory: App\Contact\Factory\ContactFactory фабрика для создания объектов,
                 недостающие значения можно разрешить только на уровне Mediator
        entity: App\Contact\Entity\Contact сущность
        constraints: Вкл/выкл проверки полей сущности по умолчанию 
        dto: App\Contact\Dto\ContactDto класс dto с которым работает сущность
        decorates:
          command - декоратор mediator команд контакта
          query - декоратор mediator запросов контакта
        services:
          pre_validator - переопределение сервиса валидатора контакта
          handler - переопределение сервиса обработчика сущностей
          file_system - переопределение сервиса сохранения файла

# CQRS model

Actions в контроллере разбиты на две группы
создание, редактирование, удаление данных

        1. putAction(PUT), postAction(POST), deleteAction(DELETE)
получение данных

        2. getAction(GET), criteriaAction(GET)

каждый метод работает со своим менеджером

        1. CommandManagerInterface
        2. QueryManagerInterface

При переопределении штатного класса сущности, дополнение данными осуществляется декорированием, с помощью MediatorInterface


группы  сериализации

    1. API_GET_CONTACT, API_CRITERIA_CONTACT - получение контакта
    2. API_POST_CONTACT - создание контакта
    3. API_PUT_CONTACT -  редактирование контакта

# Статусы:

    создание:
        контакт создан HTTP_CREATED 201
    обновление:
        контакт обновлен HTTP_OK 200
    удаление:
        контакт удален HTTP_ACCEPTED 202
    получение:
        контакт найден HTTP_OK 200
    ошибки:
        если контакт не найден ContactNotFoundException возвращает HTTP_NOT_FOUND 404
        если контакт не уникален UniqueConstraintViolationException возвращает HTTP_CONFLICT 409
        если контакт не прошел валидацию ContactInvalidException возвращает HTTP_UNPROCESSABLE_ENTITY 422
        если контакт не может быть сохранен ContactCannotBeSavedException возвращает HTTP_NOT_IMPLEMENTED 501
        все остальные ошибки возвращаются как HTTP_BAD_REQUEST 400

# Constraint

Для добавления проверки поля сущности contact нужно описать логику проверки реализующую интерфейс Evrinoma\UtilsBundle\Constraint\Property\ConstraintInterface и зарегистрировать сервис с этикеткой evrinoma.contact.constraint.property

    evrinoma.contact.constraint.property.custom:
        class: App\Contact\Constraint\Property\Custom
        tags: [ 'evrinoma.contact.constraint.property' ]

## Description
Формат ответа от сервера содержит статус код и имеет следующий стандартный формат
```text
    [
        TypeModel::TYPE => string,
        PayloadModel::PAYLOAD => array,
        MessageModel::MESSAGE => string,
    ];
```
где
TYPE - типа ответа

    ERROR - ошибка
    NOTICE - уведомление
    INFO - информация
    DEBUG - отладка

MESSAGE - от кого пришло сообщение
PAYLOAD - массив данных

## Notice

показать проблемы кода

```bash
vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php --verbose --diff --dry-run
```

применить исправления

```bash
vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php
```

# Тесты:

    composer install --dev

### run all tests

    /usr/bin/php vendor/phpunit/phpunit/phpunit --bootstrap src/Tests/bootstrap.php --configuration phpunit.xml.dist src/Tests --teamcity

### run personal test for example testPost

    /usr/bin/php vendor/phpunit/phpunit/phpunit --bootstrap src/Tests/bootstrap.php --configuration phpunit.xml.dist src/Tests/Functional/Controller/ApiControllerTest.php --filter "/::testPost( .*)?$/" 

## Thanks

## Done

## License
    PROPRIETARY
   