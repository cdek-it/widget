<?
header('Content-Type: text/html; charset=utf-8');
?>
<script id="ISDEKscript" type="text/javascript" src="widget/widjet.js"></script>
<script>
	var widjet = new ISDEKWidjet({
		hideMessages: false,
		defaultCity: 'auto',
		cityFrom: 'Омск',
		choose: true,
		link: 'forpvz',
        hidedress: false,
        bymapcoord: false,
        hidecash: false,
        hidedelt: false,
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
			'Выбран пункт выдачи заказа ' + wat.id + "\n<br/>" +
			'цена ' + wat.price + "\n<br/>" +
			'срок ' + wat.term + " дн.\n<br/>" +
			'город ' + wat.cityName + ' (код: ' + wat.city +')'
		);
	}

	function onChooseProfile(wat) {
		console.log('chosenCourier', wat);
		serviceMess(
			'Выбрана доставка курьером в город ' + wat.city + "\n<br/>" +
			'цена ' + wat.price + "\n<br/>" +
			'срок ' + wat.term + ' дн.'
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
				weight: 1
			});
            ipjq('#cntItems').html ( parseInt(ipjq('#cntItems').html()) + 1 );
            ipjq('#weiItems').html ( parseInt(ipjq('#weiItems').html()) + 2 );
		}
    </script>
<h1>Виджет выбора типа доставки</h1>

Основные возможности виджета:
<ul>
    <li>Выбор города и отображение списка ПВЗ для него</li>
    <li>Расчет доставки для указанных габаритов</li>
    <li>Возможность выбора покупателем ПВЗ с передачей в функцию-обработчик всех необходимых для обработки данных</li>
    <li>Вывод детальной информации для каждого ПВЗ</li>
    <li>Гибкая настройка отображения и простое подключение виджета</li>
    <li>Вывод детальной информации для каждого ПВЗ</li>
</ul>

Для подключения виджета необходимо на нужную страницу добавить код (рекомендуется его расположить внутри тега &lt;head&gt;):
<pre>&lt;script id="ISDEKscript" type="text/javascript" src="https://www.cdek.ru/website/edostavka/template/js/widjet.js"&gt;&lt;/script&gt;</pre>

А также скопировать к себе на сайт файл <a href="https://www.cdek.ru/website/edostavka/upload/custom/files/pvzwidget.zip">service.php</a>, в котором произвести настройки в соотвествии с вашими данными по интегарции.
Например, в строчках 5-6 указать используемые тарифы:
<pre>
ISDEKservice::setTarifPriority(
    array(233, 137, 139, 16, 18, 11, 1, 3, 61, 60, 59, 58, 57, 83),
    array(234, 136, 138, 15, 17, 62, 63, 5, 10, 12)
);
</pre>
А в строчках 17-18 указать аккаунт к интеграции, чтобы получать стоимость доставки в соответствии с вашим договором:
<pre>
    protected static $account = 'ACCOUNT_FROM_INTEGRATION';
    protected static $key     = 'SECURE_PASSWORD_FROM_INTEGRATION';</pre>

Для отображения виджета на вашем сайте необходимо создать javascript-обработчик для виджета:
<pre>&lt;script type="text/javascript"&gt;
    var ourWidjet = new ISDEKWidjet ({
        defaultCity: 'Новосибирск', //какой город отображается по умолчанию
        cityFrom: 'Омск', // из какого города будет идти доставка
        country: 'Россия', // можно выбрать страну, для которой отображать список ПВЗ
        link: 'forpvz', // id элемента страницы, в который будет вписан виджет
        path: 'https://www.cdek.ru/website/edostavka/template/scripts/', //директория с бибилиотеками
        servicepath: 'http://yoursite.net/service.php' //ссылка на файл service.php на вашем сайте
    });
&lt;/script&gt;</pre>

<p>А также на странице необходимо разместить элемент, в который будет вписана карта с пунктами выдачи заказов. Для элемента требуется указать высоту.
<pre>
&lt;div id="forpvz" style="width:100%; height:600px;"&gt;&lt;/div&gt;
</pre></p>
<p>Ниже представлена часть возможностей виджета. С более подробными возможностями можно ознакомиться, <a href="https://www.cdek.ru/website/edostavka/upload/custom/files/pvzwidget.zip">скачав документацию к виджету
    </a></p>

<div style="width: 200px;">
    Корзина покупателя:
    <p> Количество товаров: <span id="cntItems">1</span> шт.</p>
    <p> Вес товара: <span id="weiItems">1</span> кг.</p>
    <button onclick="addGood();">Добавить товар</button>
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
        box-shadow: 0px -6px 5px #666;
        display: none;
    }
    body {margin: 0}

</style>
