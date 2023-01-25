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
        factory_contact: App\Contact\Factory\ContactFactory фабрика для создания объектов контакта,
                 недостающие значения можно разрешить только на уровне Mediator
        factory_group: App\Contact\Factory\GroupFactory фабрика для создания объектов группы,
                 недостающие значения можно разрешить только на уровне Mediator
        entity_contact: App\Contact\Entity\Contact сущность контакта
        entity_group: App\Contact\Entity\Group сущность группы
        constraints: Вкл/выкл проверки полей сущности по умолчанию 
        dto_contact: App\Contact\Dto\ContactDto класс dto с которым работает сущность контакта
        dto_group: App\Contact\Dto\GroupDto класс dto с которым работает сущность группы
        decorates:
          command_contact - декоратор mediator команд контакта
          query_contact - декоратор mediator запросов контакта
          command_group - декоратор mediator команд группы
          query_group - декоратор mediator запросов группы
        services:
          pre_validator_contact - переопределение сервиса валидатора контакта
          handler_contact - переопределение сервиса обработчика сущностей
          pre_validator_group - переопределение сервиса валидатора группы
          handler_group - переопределение сервиса обработчика группы

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

    4. API_GET_CONTACT_GROUP, API_CRITERIA_CONTACT_GROUP - получение группы
    5. API_POST_CONTACT_GROUP - создание группы
    6. API_PUT_CONTACT_GROUP -  редактирование группы

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

    создание:
        группа создана HTTP_CREATED 201
    обновление:
        группа обновлена HTTP_OK 200
    удаление:
        группа удалена HTTP_ACCEPTED 202
    получение:
        группа найдена HTTP_OK 200
    ошибки:
        если группа не найдена ContactNotFoundException возвращает HTTP_NOT_FOUND 404
        если группа не уникальна UniqueConstraintViolationException возвращает HTTP_CONFLICT 409
        если группа не прошла валидацию ContactInvalidException возвращает HTTP_UNPROCESSABLE_ENTITY 422
        если группа не может быть сохранена ContactCannotBeSavedException возвращает HTTP_NOT_IMPLEMENTED 501
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
   