# Setup 3.0

This page describes all possible settings for the widget to adapt to a specific store.

## Navigation

- [Introduction](INTRO.md)
- [Installation 3.0](INSTALL_3.md)
- [Installation 2.0](INSTALL_2.md)
- Setup 3.0 (current page)
- [Setup 2.0](SETUP_2.md)
- [Migration 2.0 => 3.0](MIGRATION_2_3.md)

## An example of creating a widget indicating all the settings

```html

<script type="text/javascript">
  new window.CDEKWidget({
                          from: {
                            country_code: 'RU',
                            city: 'Новосибирск',
                            postal_code: 630009,
                            code: 270,
                            address: 'ул. Большевистская, д. 101',
                          },
                          root: 'cdek-map',
                          apiKey: 'yandex-api-key',
                          canChoose: true,
                          servicePath: 'https://some-site.com/service.php',
                          hideFilters: {
                            have_cashless: false,
                            have_cash: false,
                            is_dressing_room: false,
                            type: false,
                          },
                          hideDeliveryOptions: {
                            office: false,
                            courier: false,
                          },
                          debug: false,
                          goods: [
                            {
                              width: 10,
                              height: 10,
                              length: 10,
                              weight: 10,
                            },
                          ],
                          defaultLocation: [55.0415, 82.9346],
                          lang: 'rus',
                          currency: 'RUB',
                          tariffs: {
                            office: [233, 137, 139],
                            door: [234, 136, 138],
                          },
                          onReady() {
                            alert('Виджет загружен');
                          },
                          onCalculate() {
                            alert('Расчет стоимости доставки произведен');
                          },
                          onChoose() {
                            alert('Доставка выбрана');
                          },
                        });
</script>
```

## Description of widget settings

All configuration happens when the widget object is created. Once created, it cannot be changed.

| Property name                 | Property type    | Default value                    | Description                                                                                                                                                                                                                                                                                                   |
|-------------------------------|------------------|----------------------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| from                          | string\|object   | No value                         | The address from which the cargo will be sent. Can contain only a city or an entire address                                                                                                                                                                                                                   |
| root                          | string           | `"cdek-map"`                     | ID of the element where the widget will be placed. If missing, this element will be created on the page                                                                                                                                                                                                       |
| apiKey                        | string           | No value                         | КYandex.Map API access key                                                                                                                                                                                                                                                                                    |
| canChoose                     | boolean          | `true`                           | Controlling the “Select” button in the pickup point description. If set to `false`, the button will not be displayed, which is suitable for an info widget in the “Delivery” section. If `true` – the button is shown with the ability to subscribe to the choice of pick-up point using the `onChoose` event |
| servicePath                   | string           | No value                         | Path to PHP file for widget calculations                                                                                                                                                                                                                                                                      |
| hideFilters                   | object           | Value set by children's elements | Controlling the hiding of user-available filters                                                                                                                                                                                                                                                              |
| hideFilters.have_cashless     | boolean          | `false`                          | Managing the hiding of the "Payment by card" filter                                                                                                                                                                                                                                                           |
| hideFilters.have_cash         | boolean          | `false`                          | Managing the hiding of the "Cash payment" filter                                                                                                                                                                                                                                                              |
| hideFilters.is_dressing_room  | boolean          | `false`                          | Controlling the hiding of the filter "There is a dressing room"                                                                                                                                                                                                                                               |
| hideFilters.type              | boolean          | `false`                          | Managing the hiding of the "Office type" filter                                                                                                                                                                                                                                                               |
| forceFilters                  | object           | Value set by children's elements | Manage forced filters (the client cannot change them)                                                                                                                                                                                                                                                         |
| forceFilters.have_cashless    | boolean          | `null`                           | Managing the "Payment by card" filter[^5]                                                                                                                                                                                                                                                                     |
| forceFilters.have_cash        | boolean          | `null`                           | Managing the "Cash payment" filter[^5]                                                                                                                                                                                                                                                                        |
| forceFilters.is_dressing_room | boolean          | `null`                           | Managing the filter "There is a fitting room"[^5]                                                                                                                                                                                                                                                             |
| forceFilters.type             | string           | `null`                           | Control of the "Pickup Point Type" filter, can take values `'ALL'`, `'PVZ'`, `'POSTAMAT'`[^5]                                                                                                                                                                                                                 |
| forceFilters.allowed_cod      | boolean          | `null`                           | Show only pickup points where cash on delivery is allowed[^5]                                                                                                                                                                                                                                                 |
| debug                         | boolean          | `false`                          | Enable debug output                                                                                                                                                                                                                                                                                           |
| goods                         | array            | `[]`                             | Information about carried goods[^1]                                                                                                                                                                                                                                                                           |
| sender                        | boolean          | `false`                          | Switching the widget to "sender" mode                                                                                                                                                                                                                                                                         |
| defaultLocation               | array\|string    | No value                         | Address string or array [longitude, latitude] of the point that will be displayed on the map when the widget is opened                                                                                                                                                                                        |
| lang                          | enum('rus','eng) | `"rus"`                          | Language string to be used in the widget                                                                                                                                                                                                                                                                      |
| currency                      | string           | `"RUB"`                          | Currency in which delivery will be calculated                                                                                                                                                                                                                                                                 |
| tariffs                       | object           | Value set by children's elements | A list of tariffs allowed for cost calculation and display to the user                                                                                                                                                                                                                                        |
| tariffs.office                | array            | `[]`                             | A list of tariffs "up to pickup point" allowed for cost calculation and display to the user[^2]                                                                                                                                                                                                               |
| tariffs.door                  | array            | `[]`                             | A list of door-to-door fares allowed for cost calculation and display to the user[^2]                                                                                                                                                                                                                         |
| tariffs.pickup                | array            | `[]`                             | A list of door-to-pickup fares allowed for cost calculation and display to the user[^2]                                                                                                                                                                                                                       |
| hideDeliveryOptions           | object           | Value set by children's elements | List of delivery types that should not be available to the buyer                                                                                                                                                                                                                                              |
| hideDeliveryOptions.door      | boolean          | `false`                          | The buyer should not be able to choose delivery to his address                                                                                                                                                                                                                                                |
| hideDeliveryOptions.office    | boolean          | `false`                          | The buyer should not be able to choose delivery to the pickup point                                                                                                                                                                                                                                           |
| onReady                       | function         | No value                         | A function called after the widget has finished loading[^3]                                                                                                                                                                                                                                                   |
| onCalculate                   | function         | No value                         | A function called after the delivery cost calculation is completed[^3]                                                                                                                                                                                                                                        |
| onChoose                      | function         | No value                         | A function called after the client selects a tariff and delivery point[^3]                                                                                                                                                                                                                                    |

[^1]: You can read more in the section [Operations with parcels](SETUP_3.md#operations-with-parcels).
[^2]: You can see a list of all tariffs at https://api-docs.cdek.ru/63347458.html in the section Appendix 2.
[^3]: You can read more in the section [Events of the widget](SETUP_3.md#events-of-the-widget).
[^5]: If the value is set to `null` - the client is allowed to set the filter itself.

## Events of the widget

During the operation of the widget, 3 types of events occur, to which you can subscribe a handler function to allow
to receive data from the widget: widget loading, delivery calculation, and user selection.

### The widget has finished loading (onReady)

The event is triggered when the widget has loaded all styles, scripts, maps, as well as information about cities and
points of issue
orders. It means that you can already use the widget's methods. Please note that the HTML and layout of the maps at this
point still
may not load.
There are no passed parameters in the event.

### The widget has calculated the delivery price (onCalculate)

The event is triggered when the widget receives data about the cost and delivery time.

The event passes two parameters to the handler function: an object with tariffs and an address object.

The tariff object has the following structure:

```
{
    office: {
        tariff_code: number,
        tariff_name: string,
        tariff_description: string,
        delivery_mode: number,
        period_min: number,
        period_max: number,
        delivery_sum: number,
    }[],
    door: {
        tariff_code: number,
        tariff_name: string,
        tariff_description: string,
        delivery_mode: number,
        period_min: number,
        period_max: number,
        delivery_sum: number,
    }[],
    pickup: {
        tariff_code: number,
        tariff_name: string,
        tariff_description: string,
        delivery_mode: number,
        period_min: number,
        period_max: number,
        delivery_sum: number,
    }[],
}
```

The address object has the following structure:

```
{ code?: number; address?: string }
```

Where `address` is filled in when selecting delivery to the address, and `code` is the city code of the selected pickup
point (if delivery to the pickup point is selected)

### The user has selected the tariff and delivery address (onChoose)

The event is triggered when you click on the "Select" button in the delivery menu for the pickup point and the address.

The event passes three parameters to the handler function:
the selected delivery mode, the selected tariff, and the selected address.
Depending on the selected mode, the address object will be different.

The delivery mode is a string that has the value `door` or `office`.
The tariff object has the following structure:

```
{
    tariff_code: number,
    tariff_name: string,
    tariff_description: string,
    delivery_mode: number,
    period_min: number,
    period_max: number,
    delivery_sum: number,
}
```

Address object for `office` mode:

```
{
    city_code: number,
    city: string,
    type: string,
    country_code: string,
    postal_code: string,
    have_cashless: boolean,
    have_cash: boolean,
    allowed_cod: boolean,
    is_dressing_room: boolean,
    code: string,
    name: string,
    address: string,
    work_time: string,
    location: number[],
}
```

Address object for `door` mode:

```
{
    name: string,
    position: number[],
    kind: string,
    precision: string,
    formatted: string,
    country_code: string,
    postal_code: string,
    city: string,
}
```

## Methods to control the widget

A widget object, once created, has several public methods that you can use to control the widget's state.

### Operations with parcels

To change/set the parameters of the cargo being sent, you must use the following methods:

| Method name                           | Method description                                                                                                                  |
|---------------------------------------|-------------------------------------------------------------------------------------------------------------------------------------|
| addParcel(parcel: iParcel\|iParcel[]) | Adds a cargo (or array of cargo) described as iParcel[^4] to a parcel without clearing it. The cost will be refreshed automatically |
| getParcels()                          | Returns cargo data in the form of an array containing iParcel[^4]                                                                   |
| resetParcels()                        | Resets information about the cargo used by the widget                                                                               |

[^4]: Object with structure: `{width: number, height: number, length: number, weight: number}`

### Visual part

When a widget has been created with the `popup` parameter set to `true`, you can control the visibility of the widget
using the following methods:

| Method name | Method description          |
|-------------|-----------------------------|
| open()      | Shows a popup with a widget |
| close()     | Hides a popup with a widget |

You can control the displayed map in the widget using the following methods:

| Method name                        | Method description                                                                                                                                                                                                     |
|------------------------------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| updateLocation(string \| number[]) | The map camera moves to the specified point according to the coordinates. If a string was passed, reverse geocoding will first be applied to it to obtain coordinates similar to the `params.defaultLocation` property |
