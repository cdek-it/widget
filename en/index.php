<html lang="en">
<head>
    <title>Pickup Widget</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<script id="ISDEKscript" type="text/javascript" src="../widget/widjet.js"></script>
<script>
    var widjet = new ISDEKWidjet({
        hideMessages: false,
        defaultCity: 'Omsk',
        cityFrom: 'Moscow',
        choose: true,
        lang: 'eng',
        link: 'forpvz',
        hidedress: false,
        bymapcoord: false,
        hidecash: false,
        hidedelt: false,
        detailAddress: true,
        goods: [{
            length: 10,
            width: 10,
            height: 10,
            weight: 1
        }],
        onReady: onReady,
        onChoose: onChoose,
        onChooseProfile: onChooseProfile,
        onCalculate: onCalculate
    });

    function onReady() {
        console.log('ready');
    }

    function onChoose(wat) {
        console.log('chosen', wat);
        serviceMess(
            'The point of pickup is selected ' + wat.id + "\n<br/>" +
            'price ' + wat.price + "\n<br/>" +
            'time ' + wat.term + " days\n<br/>" +
            'city ' + wat.cityName + ' (code: ' + wat.city +')'
        );
    }

    function onChooseProfile(wat) {
        console.log('chosenCourier', wat);
        serviceMess(
            'Selected delivery by courier to the city ' + wat.city + "\n<br/>" +
            'price ' + wat.price + "\n<br/>" +
            'time ' + wat.term + ' days.'
        );
    }

    function onCalculate(wat) {
        console.log('calculated', wat);
    }

    addGood = function () {
        widjet.cargo.add({
            length: 20,
            width: 20,
            height: 20,
            weight: 2
        });
        ipjq('#cntItems').html ( parseInt(ipjq('#cntItems').html()) + 1 );
        ipjq('#weiItems').html ( parseInt(ipjq('#weiItems').html()) + 2 );
    }
</script>
<h1>Delivery type selection widget</h1>
<div style="float: right;padding-right: 15px;">
    <span style="padding: 5px 10px">
        <a href="..">Russian</a>
    </span>
    <span style="padding: 5px 10px; font-weight: bold">
        English
    </span>
</div>
Key features of the widget:
<ul>
    <li>Selecting a city and displaying a list of pickup points for it</li>
    <li>Delivery calculation for specified dimensions</li>
    <li>Possibility to select the buyer of the pickup point with the transfer of
        all the data necessary for processing to the handler function</li>
    <li>Display of detailed information for each pickup point</li>
    <li>Flexible display customization and simple widget connection</li>
</ul>

To connect the widget, you need to add the code to the desired page (it is recommended to place it inside the &lt;head&gt; tag):
<pre>&lt;script id="ISDEKscript" type="text/javascript" src="https://widget.cdek.ru/widget/widjet.js" charset="utf-8"&gt;&lt;/script&gt;</pre>

And also copy the <a href="https://widget.cdek.ru/pvzwidget.zip">service.php</a> file to your website,
in which you can make settings in accordance with your integration data.
For example, in lines 5-6 indicate the tariffs used:
<pre>
ISDEKservice::setTarifPriority(
    array(233, 137, 139, 16, 18, 11, 1, 3, 61, 60, 59, 58, 57, 83),
    array(234, 136, 138, 15, 17, 62, 63, 5, 10, 12)
);
</pre>

And in lines 17-18, indicate the account for the integration in order to receive the shipping cost in accordance with your contract:
<pre>
    protected static $account = 'ACCOUNT_FROM_INTEGRATION';
    protected static $key     = 'SECURE_PASSWORD_FROM_INTEGRATION';</pre>


To display the widget on your site, you need to create a javascript handler for the widget:
<pre>&lt;script type="text/javascript"&gt;
    var ourWidjet = new ISDEKWidjet ({
        defaultCity: 'Omsk', //default city to display with pickups points
        cityFrom: 'Moscow', // city from which orders will be sent
        lang: 'eng', // set the language of the widget
        country: 'Россия', // you can select the country for which to display the list of pickup points
        link: 'forpvz', // id of the HTML element into which the widget will be written
        path: 'https://widget.cdek.ru/widget/scripts/', //library directory
        servicepath: 'http://yoursite.net/service.php' //link to service.php file on your site
    });
&lt;/script&gt;</pre>

<p>And also on the page it is necessary to place an element into which the card with the points of issue of orders will be inscribed. The element requires a height.
<pre>
&lt;div id="forpvz" style="width:100%; height:600px;"&gt;&lt;/div&gt;
</pre>

<h3>Links</h3>
<p>Examples of using the widget: <a href="https://widget.cdek.ru/examples/">list of examples (RU)</a></p>
<p>The history of changes of the widget can be viewed in the file: <a href="./changes/">module changelog</a></p>

<h3>An example of how the widget works</h3>
<p>Some of the widget's capabilities are presented below. More detailed possibilities can be found by,
    <a href="https://widget.cdek.ru/pvzwidget.zip">downloading the documentation for the widget</a>.
</p>
<div style="width: 200px;">
    Shopping cart:
    <p> Number of goods: <span id="cntItems">1</span> pcs.</p>
    <p> Weight of goods: <span id="weiItems">2</span> kg.</p>
    <button onclick="addGood();">Add product</button>
</div>

<br/>
<br/>

<div id="forpvz" style="width:100%; height:600px;"></div>
<div id="service_message"></div>

<script>
    window.servmTimeout = false;
    serviceMess = function (text) {
        clearTimeout(window.servmTimeout);
        ipjq('#service_message').show().html(text);
        window.servmTimeout = setTimeout(function () {
            ipjq('#service_message').fadeOut(1000);
        }, 4000);
    }
</script>
<style>
    #service_message {
        position: fixed;
        bottom: 0;
        width: 100%;
        left: 0;
        background: white;
        border-radius: 10px 10px 0 0;
        padding: 18px;
        box-shadow: 0 -6px 5px #666;
        display: none;
    }
    body {margin: 0}

</style>
</body>
</html>