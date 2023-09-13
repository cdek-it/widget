# Migration of the widget from 2.0 version up to 3.0 version

Version 3.0 of the widget makes many significant changes to the code and logic of the widget itself.

## Navigation

- [Introduction](INTRO.md)
- [Installation 3.0](INSTALL_3.md)
- [Installation 2.0](INSTALL_2.md)
- [Setup 3.0](SETUP_3.md)
- [Setup 2.0](SETUP_2.md)
- Migration 2.0 => 3.0 (current page)

## New widget differences

Main conceptual differences from the previous version:

- Using the JS API Yandex.Map 3.0
- Using CSS reset technology
- Changing the logic of the search field.
- Changing the widget design
- Refusal to use jQuery and plugins connected to it as external dependencies.
- Assembling the widget code as a umd module

For correct communication with the previous version, you must use the new script connection address[^1].

[^1]: You can read more at page [Setup 3.0](INSTALL_3.md#script-setup)

The widget's base class name has also been changed from `ISDEKWidjet` to `CDEKWidget`, accordingly on its page. So now
it should be connected as:

```js
const widget = new window.CDEKWidget({ ... });
```

In addition to renaming the class, the input parameters[^2] of the widget have changed, and new ones have been added:

| Previous name | Current name                     |
|---------------|----------------------------------|
| link          | root                             |
| apikey        | apiKey                           |
| choose        | canChoose                        |
| servicepath   | servicePath                      |
| hidecash      | hideFilters.have_cash[^3]        |
| hidedress     | hideFilters.is_dressing_room[^3] |
| hidedelt      | hideDeliveryOptions[^4]          |
| showLogs      | debug                            |
| defaultCity   | defaultLocation                  |

[^2]: You can read more at page [Setup 3.0](SETUP_3.md#description-of-widget-settings)
[^3]: A parameter is a property of an object `hideFilters`
[^4]: Instead of one parameter that hides all delivery, an object is now can be specified that allows you to hide
delivery methods separately, and not just together

In addition to the parameters, the list of events that the widget produces has been changed. Now there are only three of them: `onReady`, `onCalculate`, `onChoose`[^5].

[^5]: You can read more at page [Setup 3.0](SETUP_3.md#events-of-the-widget)

The names of the methods responsible for adding weight to the calculator for calculation have also been changed[^6].

| Previous name | Current name |
|---------------|--------------|
| cargo.add     | addParcel    |
| cargo.get     | getParcels   |
| cargo.reset   | resetParcels |

[^6]: You can read more at page [Setup 3.0](SETUP_3.md#operations-with-parcels)
