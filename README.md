# Виджет ПВЗ от СДЭК

![Size](https://img.shields.io/github/size/cdek-it/widget/dist%2Fcdek-widget.umd.js)
[![jsDelivr hits (GitHub)](https://img.shields.io/jsdelivr/npm/hy/@cdek-it/widget)](https://www.jsdelivr.com/package/npm/@cdek-it/widget)
[![GitHub release (with filter)](https://img.shields.io/github/v/release/cdek-it/widget)](https://github.com/cdek-it/widget/releases)

Виджет предназначен для отображения актуальной информации о пунктах самовывоза СДЭК с привязкой их к карте, возможностью расчета доставки (сроков и стоимости) для указанных габаритов товаров. Виджет может отображаться как статичный элемент на странице, так и всплывающим окном.

## Возможности виджета
 - Отображение списка ПВЗ для выбранного города
 - Фильтрация и поиск необходимых ПВЗ согласно предпочтениям пользователя
 - Расчет доставки для указанных габаритов
 - Возможность выбора покупателем ПВЗ с передачей в функцию-обработчик всех необходимых для обработки данных
 - Вывод детальной информации для каждого ПВЗ и постаматов
 - Вывод справочной информации по сервису
 - Гибкая настройка отображения виджета
 - Минимизация конфликтов при интеграции
 - Простое подключение и настройка

## Требования к виджету
Для корректной работы виджета необходимо, чтобы на сервере были установлены следующие компоненты:
 - версия PHP не менее 5.3, рекомендуемая 7.4
 - наличие расширения CURL для PHP

Требования к элементу (параметр `root`), в который будет встроен виджет:
 - Высота элемента должна быть задана, иначе она может быть равна нулю и вы не увидите виджет на своем сайте.
 - Убедитесь, что этот элемент без каких-либо стилей, относящихся к блочной модели, за исключением width\height\float. В противном случае внешний вид виджета может исказиться.
 - Рекомендуется задать ширину элементу не менее 800 пикселей, а высоту – не менее 600 пикселей.

# Документация
Документация по настройке и установке виджета описана в [вики](https://github.com/cdek-it/widget/wiki) проекта

## Вопросы и замечания по поводу работы виджета
Все вопросы и замечания можно отправлять на электронную почту integrator@cdek.ru

Если у вас не получается подключить, настроить или отобразить виджет на вашем сайте, то укажите, пожалуйста, ссылку на вашу страницу, где вы пытались разместить виджет, для того чтобы мы смогли корректно ответить на ваши вопросы.
Для полноценной помощи клиентам нам необходима следующая информация:
1. Ссылка на работающий пример (если ссылку дать не могут, то необходимо страницу сохранить в формате html и прикрепить к письму, чтоб было полное представление картины на стороне клиента)
2. Подробное описание проблемы
3. Если происходит ошибка, то при каких действиях
