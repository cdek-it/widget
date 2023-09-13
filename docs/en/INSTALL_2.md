# Installation 2.0
After 3 version of the widget url`s and method of widget installation changes. That page describes installation of the old version.

# WARNING!
***2 version of the widget has been declared as deprecated, it will be off from the support and no bugfixes or improvements will be developed to it, please, migrate to 3 version***

Migration process described at page [Migration 2.0 => 3.0](MIGRATION_2_3.md).

## Navigation
- [Introduction](INTRO.md) 
- [Installation 3.0](INSTALL_3.md)
- Installation 2.0 (current page)
- [Setup 3.0](SETUP_3.md)
- [Setup 2.0](SETUP_2.md)
- [Migration 2.0 => 3.0](MIGRATION_2_3.md)

## Ways of installing the widget

There are two ways of installing the widget:
1. Local, when all the files are stored on your server.
2. Minimal, when all the files are loaded from our web-site, and only authorization data is downloaded from your web-site.

Local installation allows you to keep all the files on your server, which provides faster loading of scripts, compared to the minimal installation. Also, this type of installation allows you to edit scripts and widget styles to suit your requirements.

On the other hand, the minimal installation may also be good for you because you need to edit only 1 file and include a link to the file in the script.

## Unpacking the archive
To install the widget, you need to unpack the archive with the widget. The archive contains:
- the widget’s scripts – `widget` directory;
- examples of the widget’s work – `examples` directory;
- short instruction on the widget’s installation – `index.php` file.
It is important to know that the widget includes server files (`widget/scripts/service.php` and `widget/scripts/template.php`), correct function of which requires placing them on the web-server. This is why we recommend to see examples on the web-server.

**For local installation** of the widget, you need to copy `widget` directory to the web-site directory:
For instance, the web-site is located in `/home/site/` directory; copy the catalogue with the widget and the entire path to the widget will look like this: `/home/site/widget/`
Dependency of the scripts’ locations must be preserved. Note that the widget’s assembly contains a server file (`widget/scripts/service.php`).

**For minimal installation** of the widget, you need to copy only server file (`widget/scripts/service.php`) to your web-site, for instance, to `/home/site/widget/scripts/service.php` directory.

## Authorization and configuration
For the correct calculation of the price of delivery, the widget needs authorization data to access the CDEK integration service. To get the data for the Integration Account, you need to click the "Create Key" button in the Integration section in your personal account; then the Account ID and Password will appear in the integration section.

To enter the data, open `scripts/service.php` file. Enter the data in the 17th (account) and 20th (key) lines inside the quotation marks.

If you need to change priority of tariff calculations, change their order in the lines 11 (courier delivery) and 14 (customer pick-up from the pick-up point). You can find numbers of the tariffs in the documentation to the integration service https://confluence.cdek.ru/x/gUju in the section Appendix 1.

## Connecting scripts
To connect the **local version of the widget**, you need to add the following code to the page: (we recommend to place it inside the <head> tag):
`<script type="text/javascript" src="https://your.site/path-of-widget-on-your-site/widjet.js" charset="utf-8" id="ISDEKscript" ></script>`

To connect the **minimal version of the widget**, you need to add the following code to the page: (we recommend to place it inside the <head> tag):
`<script type="text/javascript" id="ISDEKscript" src="https://cdn.jsdelivr.net/gh/cdek-it/widget@2/widget/widjet.min.js" charset="utf-8"></script>`

## Placing the widget on a page

You will need to add an element, in which the widget will be built, to the page. Height of the element must be specified; otherwise it may be equal to zero and the widget will not be displayed on the website.

`<div id="forpvz" style="height:600px;"></div>`

Next, you will need to create a handler to initialize the widget:

For **local version** of the widget, the handler with a minimal number of parameters will look like this:
```html
<script type="text/javascript">
        var widjet = new ISDEKWidjet({
            defaultCity: 'Уфа',
            cityFrom: 'Омск',
            link: 'forpvz'
        });
    </script>
```
For **minimal version** of the widget, a minimal number of parameters will look like this:
```html
<script type="text/javascript">
        var widjet = new ISDEKWidjet({
        defaultCity: 'Уфа',
        cityFrom: 'Омск',
        link: 'forpvz',
        path: 'https://cdn.jsdelivr.net/gh/cdek-it/widget@2/widget/scripts/',
        servicepath: 'http://yoursite.net/service.php' // link to “service.php” file on your web-site
    });
    </script>
```
