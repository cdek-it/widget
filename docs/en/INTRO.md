# The widget’s functions
The widget is designed to display relevant information about CDEK pick-up points linked to a map, with an opportunity to calculate delivery (time and price) for the specified dimensions of the goods. The widget can be displayed as a static element of the page, as well as a pop-up window.

## Navigation
- Introduction (current page)
- [Installation 3.0](INSTALL_3.md)
- [Installation 2.0](INSTALL_2.md)
- [Setup 3.0](SETUP_3.md)
- [Setup 2.0](SETUP_2.md)
- [Migration 2.0 => 3.0](MIGRATION_2_3.md)

## The widget’s capabilities
- Display list of pick-up points for the selected city
- Change of displayed city
- Calculation of delivery for the specified dimensions
- Opportunity for the buyer to select a pick-up point with transfer all the data necessary for processing to the handler function
- Display of detailed information for each pick-up point and parcel terminals
- Display of support information about the service
- Flexible widget display settings
- Minimization of integration conflicts
- Easy connection and configuration

## Requirements
Following components installed on the server are required for correct operation of the widget:
- PHP version not older than 5.3, 7.4 version is recommended
- CURL extension for PHP

Requirements to the element (`root` parameter), in which the widget will be built:
- Height of the element must be specified; otherwise it may be equal to zero and the widget will not be displayed on the website.
- Make sure that this element has no styles related to the box model, excluding “width\height\float”. Otherwise, the widget may appear distorted.
- It is recommended to set the element's width not less than 800 px, height – not less than 600 px.

## Compatibility of backend widget versions
- service.php of widget version 2 is not compatible with version 3
- service.php of the widget version 3.0, 3.1 is not compatible with versions older than 3.2
- service.php of the widget version 3.2 is not compatible with lower versions

## Compatibility with other scripts and other requirements
- Stability of the widget is not guaranteed when used on a page with the Yandex Maps widget
- Widget uses "Reset CSS" technology with help of [normalize.css](https://necolas.github.io/normalize.css/), stability of other pages, that don't use that technology is not guaranteed
- When placing the widget map code inside the <form> tag, if you click on the buttons to zoom in/out of the map and determine the location, the submit event of this form is sent

## Questions and comments on the widget’s work
Please send all your questions and comments to integrator@cdek.ru

If you have problems with connection, configuration or displaying the widget on your web-site, please indicate the link to your page where you tried to place the widget, so that we can answer your questions correctly.
To provide full assistance to our clients, we need the following information:
1. Link to a working example (if it’s impossible to provide the link, you need to save the page in html format and attach it to the email so that the picture on the client side is fully presented)
2. Comprehensive description of the problem
3. With which actions the error occurs
