# Installation 3.0
From version 3, the addresses and method of installing the widget change. Installation of the old version is described
on the page [Setup 2.0](INSTALL_2.md)

## Navigation
- [Introduction](INTRO.md)
- Installation 3.0 (current page)
- [Installation 2.0](INSTALL_2.md)
- [Setup 3.0](SETUP_3.md)
- [Setup 2.0](SETUP_2.md)
- [Migration 2.0 => 3.0](MIGRATION_2_3.md)

## Authorization and setup
It is necessary to copy the file [service.php](../../dist/service.php) to the web server so that it is accessible via a direct link (for example, https://some-site.com/service.php).
To correctly calculate delivery costs, the widget requires authorization data to access the CDEK integration service. To obtain data on the Integration Account, you need to go to your personal account, In the Integration section, click the “Create Key” button, and then the Account ID and Password will appear in the Integration section.
To enter data, open the `service.php` file. The data is entered in the 7th (account) and 12th (key) lines inside empty quotes.

## Script setup
To connect the widget, you need to add the code `<script type="text/javascript" src="https://cdn.jsdelivr.net/gh/cdek-it/widget@latest/dist/cdek-widget.umd" to the desired page. js" charset="utf-8"></script>`. It is recommended to place it inside the `<head>` tag.

You can select the specific version of the widget to be loaded. The above example automatically downloads latest - the latest version. Accordingly, every time the author releases a new version, it will be updated automatically. This connection option is not recommended for use in a production environment.

Widget versions are created in the semver format: `<major version digit>.<minor version digit>.<patch digit>`.
- If you want to automatically receive bug fixes in the widget that will not affect existing functionality (but will not add new functionality), then you must specify the first two digits of the version in the src of the loaded script, instead of the latest. For example:
  `<script type="text/javascript" src="https://cdn.jsdelivr.net/gh/cdek-it/widget@3.0/dist/cdek-widget.umd.js" charset="utf-8" ></script>`
- If you want to automatically receive bug fixes and new functionality that is backward compatible, i.e. is guaranteed not to break an existing widget, then in the src of the loaded script, you must specify the first digit of the version, instead of the latest. For example: `<script type="text/javascript" src="https://cdn.jsdelivr.net/gh/cdek-it/widget@3/dist/cdek-widget.umd.js" charset="utf- 8"></script>`
- If you do not want to automatically receive the new code, then in the src of the loaded script you must specify the full version instead of the latest. For example: `<script type="text/javascript" src="https://cdn.jsdelivr.net/gh/cdek-it/widget@3.0.0/dist/cdek-widget.umd.js" charset=" utf-8"></script>`

## Receiving Yandex.Map key
To use the widget, you need to obtain a Yandex.Maps API key. Otherwise, the widget will not be displayed. The key generation process is described on the page: https://yandex.ru/dev/jsapi-v2-1/doc/ru/#get-api-key. Be sure to set the HTTP Referrer parameter equal to the address of your site for the key.

## Placing a widget on a page
You need to add an element to the page in which the widget will be embedded. This element must have a height specified, otherwise, it may take a height value of 0 and the widget will not be visible on the page.
`<div id="cdek-map" style="width:800px;height:600px"></div>`
Next, you need to create a handler to initialize the widget. In it you must specify the desired widget configuration, as well as a link to the `service.php` file located on your server. A handler with a minimum number of parameters will look like this:
```html
<script type="text/javascript">
   new window.CDEKWidget({ from: 'Novosibirsk', root: 'cdek-map', apiKey: 'yandex-api-key', servicePath: 'https://some-site.com/service.php', defaultLocation: 'Novosibirsk' });
</script>
```

The widget initialization handler must be called when an element with the specified element ID already exists on the page, otherwise it will be re-created.

Next, you need to configure the widget in accordance with the information from the [Setup 3.0](SETUP_3.md) page
