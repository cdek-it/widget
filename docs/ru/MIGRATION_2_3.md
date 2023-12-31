# Миграция виджета с версий 2.0 до версии 3.0
Версия 3.0 виджета привносит много крупных изменений в код и логику работы самого виджета.

## Навигация
- [Введение](INTRO.md)
- [Установка 3.0](INSTALL_3.md)
- [Установка 2.0](INSTALL_2.md)
- [Настройка 3.0](SETUP_3.md)
- [Настройка 2.0](SETUP_2.md)
- Миграция 2.0 => 3.0 (Эта страница)

## Отличия нового виджета

Основные концептуальные отличия от предыдущей версии:

- Использования JS API Яндекс.Карт 3.0
- Использование Reset CSS технологии
- Изменение логики работы строки поиска
- Изменение дизайна виджета
- Отказ от использования jQuery и сопутствующих ему плагинов в качестве внешних зависимостей
- Сборка кода виджета в виде umd модуля

Для корректной миграции с предыдущей версии необходимо использовать новый адрес подключения скрипта[^1].

[^1]: Подробнее можно прочитать на странице [Установка 3.0](INSTALL_3.md#подключение-скриптов)

Было также изменено название базового класса виджета с `ISDEKWidjet` на `CDEKWidget`, соответственно к странице его надо
подключать как

```js
const widget = new window.CDEKWidget({ ... });
```

Помимо переименования класса, поменялись входные параметры[^2] виджета, а также добавились новые:

| Было        | Стало                            |
|-------------|----------------------------------|
| link        | root                             |
| apikey      | apiKey                           |
| choose      | canChoose                        |
| servicepath | servicePath                      |
| hidecash    | hideFilters.have_cash[^3]        |
| hidedress   | hideFilters.is_dressing_room[^3] |
| hidedelt    | hideDeliveryOptions[^4]          |
| showLogs    | debug                            |
| defaultCity | defaultLocation                  |

[^2]: С полным списком параметров можно ознакомиться на странице [Настройка 3.0](SETUP_3.md#описание-настроек-виджета)
[^3]: Параметр является свойством объекта `hideFilters`
[^4]: Вместо одного параметра, прячущего всю доставку теперь указывается объект, позволяющий спрятать методы доставки по отдельности, а не только вместе

Помимо параметров, был изменен список событий, которые отдает виджет. Теперь их только
три: `onReady`, `onCalculate`, `onChoose`[^5].

[^5]: Подробнее можно прочитать на странице [Настройка 3.0](SETUP_3.md#события-виджета)

Также были изменены названия методов, отвечающих за добавления груза к калькулятору для расчета[^6].

| Было        | Стало        |
|-------------|--------------|
| cargo.add   | addParcel    |
| cargo.get   | getParcels   |
| cargo.reset | resetParcels |

А вес посылки теперь указывается не в килограммах, а в граммах.

[^6]: Подробнее можно прочитать на странице [Настройка 3.0](SETUP_3.md#операции-с-посылками)
