# The widget’s settings

Current page describes all possible settings of the 2 version of the widget.

# WARNING!

***2 version of the widget has been declared as deprecated, it will be off from the support and no bugfixes or
improvements will be developed to it, please, migrate to 3 version***

Migration process described at page [Migration 2.0 => 3.0](MIGRATION_2_3.md).

## Navigation

- [Introduction](INTRO.md)
- [Installation 3.0](INSTALL_3.md)
- [Installation 2.0](INSTALL_2.md)
- [Setup 3.0](SETUP_3.md)
- Setup 2.0 (current page)
- [Migration 2.0 => 3.0](MIGRATION_2_3.md)

## Example of creating the widget with all settings

```html

<script type="text/javascript">
  var widjet = new ISDEKWidjet({
                                 showWarns: true,
                                 showErrors: true,
                                 showLogs: true,
                                 hideMessages: false,
                                 path: 'https://cdn.jsdelivr.net/gh/cdek-it/widget@2/widget/scripts/',
                                 servicepath: 'https://yoursite.net/service.php', //link to “service.php” file on your web-site
                                 templatepath: 'http://yoursite.net/template.php',
                                 choose: true,
                                 popup: true,
                                 country: 'Россия',
                                 defaultCity: 'Уфа',
                                 cityFrom: 'Омск',
                                 link: null,
                                 hidedress: true,
                                 hidecash: true,
                                 hidedelt: false,
                                 detailAddress: true,
                                 region: true,
                                 apikey: 'API-key Yandex.MAP’,
                                 goods: [
                                   {
                                     length: 10,
                                     width: 10,
                                     height: 10,
                                     weight: 1
                                   }],
                                 onReady: onReady,
                                 onChoose: onChoose,
                                 onChooseProfile: onChooseProfile,
                                 onChooseAddress: onChooseAddress,
                                 onCalculate: onCalculate
                               });

  function onReady() {
    alert('Виджет загружен');
  }

  function onChoose(wat) {
    alert(
      'Выбран пункт выдачи заказа ' + wat.id + "\n" +
      'цена ' + wat.price + "\n" +
      'срок ' + wat.term + " дн.\n" +
      'город ' + wat.cityName + ', код города ' + wat.city
    );
  }

  function onChooseProfile(wat) {
    alert(
      'Выбрана доставка курьером в город ' + wat.cityName + ', код города ' + wat.city + "\n" +
      'цена ' + wat.price + "\n" +
      'срок ' + wat.term + ' дн.'
    );
  }

  function onChooseAddress(wat) {
    alert(
      'Выбрана доставка курьером по адресу ‘ + wat.address + ”, \n “ + 
    'цена ' + wat.price + "\n" +
    'срок ' + wat.term + ' дн.'
  )

  }

  function onCalculate(wat) {
    alert('Расчет стоимости доставки произведен');
  }
</script>
```

## Description of server settings

### showWarns

Values: `true` / `false`

Default value: `true`

If `true` value is set, the browser’s console will display warnings of non-critical errors in the widget’s work.

### showErrors

Values: `true` / `false`

Default value: `true`

If `true` value is set, the browser’s console will display warnings of critical errors in the widget’s work.

### showLogs

Values: `true` / `false`

Default value: `true`

If `true` value is set, the browser’s console will display information of all stages of the widget’s work: requests,
responses, connection.

### hideMessages

Values: `true` / `false`

Default value: `false`

A universal key to switch off all notifications of the module. `true` value is equal to setting all the above-mentioned
notification settings in `false`.

### path

Values: `string`

Default value: scripts directory in relation to the `widjet.js` file.

Thus, if your `widjet.js` file is located at http://yoursite.net/scripts/widget/widjet.js, the variable will direct
to http://yoursite.net/scripts/widget/scripts/
Path to the widget’s scripts if they are located separately from the loader (`widjet.js`).

### templatepath

Values: `string`

Default value: `{path}/template.php`

Path to the widget’s template, if it is located separately from the loader (`widjet.js`).

### servicepath

Values: `string`

Default value: `{path}/service.php`

Path to the php file for the widget’s calculations, if it is located separately from the loader (`widjet.js`).

### apikey

Values: `string`

Default value: `9720c798-730b-4af9-898a-937b264afcdd`

API key for the Yandex Maps service. We recommend to follow the link below to generate a new key for your web-site.
Otherwise `429 Too Many Requests` errors may occur.
https://tech.yandex.ru/maps/jsapi/doc/2.1/quick-start/index-docpage/#get-api-key
> We recommend to register your API key for the Yandex Maps so that the widget on your web-site works with your keys
> only and the restrictions on the free keys is applied to your web-site only.

## Description of display settings

### choose

Values: `true` / `false`

Default value: `true`

Managing «Choose» button in the pick-up point’s description. If `false` is set, the button will not be displayed, which
is suitable for an info-widget in the “Delivery” section. If `true` is set, the button will be displayed with an option
to subscribe to select the pick-up point with the help of `onChoose` event.

### link

Values: `id элемента`

Default value: `false`

If an option is set (not `false`), then the widget is considered static (always located on the page). The option’s value
must be set as an id of the tag in which the widget will be placed. If the option is not set, the widget will
automatically expand to the entire screen (unless the `popup` option is set).

### popup

Values: `true`/`false`

Default value: `false`

If the option is set (`true`), the widget is considered as popup, and will be displayed via `open` method only.

### hidedress

Values: `true`/`false`

Default value: `false`

If the option is set (`true`), the filter of pick-up points with a try-on option will be hidden. In other words, it
hides a button with this filter and all pick-up points for this city are displayed.

### hidecash

Values: `true`/`false`

Default value: `false`

If the option is set (`true`), the filter of pick-up points with a cashless payments option will be hidden. The pick-up
points widget will be displayed without the “cashless payments” button.

### hidedelt

Values: `true`/`false`

Default value: `false`

If the option is set (`true`), the panel with delivery options will be hidden.

### region

Values: `true`/`false`

Default value: `false`

If the option is set (`true`), pick-up points for the entire region will be displayed. For Moscow and the Moscow region,
as well as for St. Petersburg and the Leningrad region, pick-up points in the city and the region are displayed. Thus,
if you select the city of Gatchina (Leningrad Region), then the pick-up points in the city of Gatchina will be
displayed, and pick-up points in the entire Leningrad region and St. Petersburg will be displayed on the map. At that,
if you choose a pick-up point in another city (St. Petersburg, for example), then the price of delivery will be
recalculated.

### detailAddress

Values: `true`/`false`

Default value: `false`

If the option is set (`true`), users will be offered to specify the exact delivery address after choosing courier
delivery.

## Delivery settings

### defaultCity

Values: a city’s name, it is also possible to use ‘auto’ parameter, to automatically find the cient’s city via Yandex
tools and show it on the map. You can use ID of the city, a number.

Default value: `"Moscow"`

The city that will be displayed on the map at the start of the widget and where the pick-up points will be loaded.
Important! The city must be located in this country when the `country` parameter is used
Important! When the `lang` parameter is used, the city’s name must be written in the same language as in the `lang`
parameter. Thus, “Moscow, Astana” must be written for an English translation.

### lang

Values: `"rus"` or `"eng"`

Default value: `"rus"`

Which translation language to use in the widget – for now, only Russian and English are available.

### currency

Values: currency code (ISO 4217) from list: RUB, KZT, USD, EUR, GBP, CNY, BYN, UAH, KGS, AMD, TRY, THB, KRW, AED, UZS,
MNT

Default value: `‘RUB’`

### country

Values: a country's name (Russia, China (PRC), Kazakhstan etc)

Default value: `"all"`

A country, for which cities and pick-up points are selected.
Important! When the `lang` parameter is used, the country’s name must be written in the same language as in the `lang`
parameter. Thus, “Russia, Kazakhstan” must be written for an English translation.

### cityFrom

Values: a city's name

Default value: `"Moscow"`

A city from which the parcel will be sent.
Important! The city must be located in this country when the `country` parameter is used

### goods

Values: `[{length:<длина груза >, width: <ширина груза >, height: <высота груза >, weight: <вес груза>}]`

Array of the cargoes sent. Dimensions are set in centimeters (cm), weight – in kilograms (kilos).

An example of setting a parcel’s dimensions with the following characteristics: length 25 cm, width 17 cm, height 7 cm,
weight 6 kilos.

```js
goods: [
    {
        length: 25,
        width: 17,
        height: 7,
        weight: 6
    }]
```

## The widget’s events

During the operation of the widget, there are 4 types of events to which you can subscribe the handler function. It will
allow receiving data from the widget: the widget loading, delivery calculation and selection of a pick-up point.

### Loading the widget (onReady)

The event is triggered when the widget has loaded all the styles, scripts, maps, as well as information about cities and
pick-up points. It means that you can already use the widget’s methods. Please note that loading of html and maps markup
may not be finished at this point.

There are no transferred parameters in the event.

To work with this event, you need to specify the name of the handler function in the parameter and describe execution of
this function:

```html

<script type="text/javascript">
  var widjet = new ISDEKWidjet({
                                 defaultCity: 'Омск',
                                 cityFrom: 'Уфа',
                                 country: 'Россия',
                                 link: 'forpvz',
                                 onReady: startWidget
                               });

  function startWidget() {
    alert('Виджет загружен');
  }
</script>
```

### Calculation of delivery (onCalculate)

The event is triggered when the widget has received information about delivery price and dates.
The event transfers to the handler function the following object:
`{ profiles: <profiles object>, city: <city code from CDEK database>, cityName: <current city>}`.

Profiles
object: `<profile (courier/pickup)> :{price: <price>, currency: <currency>, term: <time>, tariff: <calculated tariff>}`.

Here’s an example of a such object in JSON format:

```json
{
  "city": "270",
  "cityName": "Новосибирск",
  "profiles": {
    "courier": {
      "price": "1150",
      "currency": "RUB",
      "term": "2-3",
      "tarif": 11
    },
    "pickup": {
      "price": "600",
      "currency": "RUB",
      "term": "3-4",
      "tarif": 62
    }
  }
}
```

You can use the following code to create a handler:

```html

<script type="text/javascript">
  var widjet = new ISDEKWidjet({
                                 defaultCity: 'auto',
                                 cityFrom: 'Уфа',
                                 country: 'Россия',
                                 link: 'forpvz',
                                 onCalculate: function(wat) {
                                   alert('Доставка в город ' + wat.cityName +
                                           "\nкурьером: " + wat.profiles.courier.price + ' (тариф ' +
                                           wat.profiles.courier.tarif +
                                           ")\nдо ПВЗ: " + wat.profiles.pickup.price + ' (тариф ' +
                                           wat.profiles.pickup.tarif + ')'
                                   );
                                   console.log('Расчет доставки ', wat);
                                 }
                               });
</script>
```

In this example, each delivery calculation will be accompanied by a message on the cost of delivery to the selected city
indicating the selected tariff, and the text “Delivery calculation” and the contents of the object with delivery
parameters will be displayed in the browser console.

### Selecting pick-up point (onChoose)

The event is triggered when a buyer chooses a pick-up point by pressing the “Choose” button in a detailed description of
a pick-up point.

The event sends into a handler function an
array `{id: <id of the selected pick-up point>, PVZ: <information about pick-up point>, price: <delivery price>, currency: <delivery currency>, term: <delivery time>, tariff: <calculated tariff>, city: <selected city>}`

An example of object in JSON format:

```json
{
  "id": "NSK41",
  "city": "270",
  "cityName": "Новосибирск",
  "price": "1150",
  "currency": "RUB",
  "term": "3-4",
  "tarif": 62,
  "PVZ": {
    "Name": "Кольцово",
    "WorkTime": "Пн-Пт 10:00-19:00, Сб 10:00-16:00",
    "Address": "р.п. Кольцово, 18А",
    "Phone": "+79639494987, +79833160261",
    "Note": "",
    "cX": "83.1851620",
    "cY": "54.9422560"
  }
}
```

You can use the following code to create a handler for this event:

```html

<script type="text/javascript">
  var widjet = new ISDEKWidjet({
                                 defaultCity: 'auto',
                                 cityFrom: 'Уфа',
                                 country: 'Россия',
                                 link: 'forpvz'
                               });
  widjet.binders.add(choosePVZ, 'onChoose');

  function choosePVZ(wat) {
    alert('Доставка в город ' + wat.cityName +
            "\nдо ПВЗ с кодом: " + wat.id + ', цена ' + wat.price + ' руб.'
    );
    console.log('Выбран ПВЗ ', wat);
  }
</script>
```

In this example, when a pick-up point is selected (“Choose” button for a pick-up point must be displayed, that is,
parameter `choose=true`), a message is displayed, where you can see the city, code of the pick-up point and delivery
price, and a message with the text and an object with the delivery parameters will be displayed in the browser’s
console.

### Selecting delivery profile (Courier) (onChooseProfile)

The event is triggered when the buyer chooses courier delivery to a particular city.

The event sends into a handler function an
array `{id: <courier>, price: <delivery price>, currency: <delivery currency>, term: <delivery time>, tariff: <calculated tariff>, city: <selected city>}`

An example of object transferred to the function in JSON format:

```json
{
  "id": "courier",
  "city": "270",
  "cityName": "Новосибирск",
  "price": "1150",
  "currency": "RUB",
  "term": "2-3",
  "tarif": 11
}
```

See an example of creating a handler for this event below. In the example, a message is displayed in which the city,
delivery price and time are indicated, and a message with text and an object with delivery parameters is displayed in
the browser console.

```html

<script type="text/javascript">
  var widjet = new ISDEKWidjet({
                                 defaultCity: 'auto',
                                 cityFrom: 'Уфа',
                                 country: 'Россия',
                                 link: 'forpvz',
                                 hidedress: true,
                                 hidecash: true,
                                 onChooseProfile: onChooseProfile
                               });

  function onChooseProfile(wat) {
    alert('Выбрана доставка курьером в город ' + wat.cityName + "\n" +
            'цена ' + wat.price + "\n" +
            'срок ' + wat.term + ' дн.'
    );
    console.log('Выбрана доставка курьером ', wat);
  }
</script>
```

### Selecting delivery address (Courier) (onChooseAddress)

The event is triggered when the buyer chooses courier delivery to a concrete delivery address.

The event sends into a handler function an
array `{id: <courier>, price: <delivery price>, currency: <delivery currency>, term: <delivery time>, tariff: <calculated tariff>, city: <selected city>, cityName: <name of selected city>, address: <selected delivery address>}`

An example of object transferred to the function in JSON format:

```json
{
  "id": "courier",
  "city": "270",
  "cityName": "Новосибирск",
  "price": "1150",
  "currency": "RUB",
  "term": "2-3",
  "tarif": 11,
  "address": "Россия, Новосибирск, Большевистская улица, 101"
}
```

See an example of creating a handler for this event below. In the example, a message is displayed in which the city,
delivery price and time are indicated, and a message with text and an object with delivery parameters is displayed in
the browser console.

```html

<script type="text/javascript">
  var widjet = new ISDEKWidjet({
                                 defaultCity: 'auto',
                                 cityFrom: 'Уфа',
                                 country: 'Россия',
                                 link: 'forpvz',
                                 hidedress: true,
                                 hidecash: true,
                                 detailAddress: true,
                                 onChooseAddress: onChooseAddress
                               });

  function onChooseAddress(wat) {
    alert('Выбрана доставка курьером по адресу ' + wat.address + "\n" +
            'цена ' + wat.price + "\n" +
            'срок ' + wat.term + ' дн.'
    );
    console.log('Выбрана доставка курьером ', wat);
  }
</script>
```

## The widget’s methods

### Operations with cities

`city.get()`

Returns the identifier of the current city which is displayed on the map.

`city.set(city)`

Sets “city” city as current. Can take both the city name and the city identifier as a parameter.

`city.check(city)`

Checks if the city “city” is available and returns its identifier if successful.

### Operations with parcels

Requirements for the transferred object (params) are unified for setting goods/parcels:

- length – cargo’s length (cm),
- width – cargo's width (cm),
- height – cargo's height (cm),
- weight – cargo’s weight (kilos)

`сargo.add(params)`

Adds a cargo with the indicated parameters (params) to the parcel without resetting it to zero. Price will be
re-calculated automatically.

`cargo.get()`

Returns data on goods (all available fields) in the form of an object, the format description of which looks as follows
in JSON:

```json
{
  "0": {
    "length": "15",
    "width": "10",
    "height": "10",
    "weight": "0.4"
  },
  "1": {
    "length": "25",
    "width": "7",
    "height": "17",
    "weight": "2.4"
  }
}
```

`cargo.reset()`

Resets all information about cargos.

### User interface
`open()`

Displays the widget if it is in the popup mode (unless options `link` and `popup=true` are set).

`close()`

Closes the widget if it is in the popup mode (unless options `link` and `popup=true` are set).
