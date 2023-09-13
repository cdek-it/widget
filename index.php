<script id="ISDEKscript" type="text/javascript" src="widjet.js"></script>
<script>
	var widjet = new ISDEKWidjet({
		hideMessages: false,
		defaultCity: 'Омск',
		cityFrom: 'Омск',
		<?=$_REQUEST['COUNTRY'] ? "country: '" . $_REQUEST['COUNTRY'] . "'," : ''?>
		choose: true, //скрыть кнопку выбора
		//path : true,
		<?= (!$_REQUEST['FULL'] && !$_REQUEST['POPUP']) ? "link: 'qwerty'," : ''?>
		<?= $_REQUEST['POPUP'] ? "popup: true," : ''?>
		goods: [{
			length: 20,
			width: 20,
			height: 20,
			weight: 2
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
			'город ' + wat.city
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
</script>
<? if (!$_REQUEST['FULL'] && !$_REQUEST['POPUP']) { ?>
    <script>
		addGood = function () {
			widjet.cargo.add({
				length: 40,
				width: 200,
				height: 20,
				weight: 25
			});
		}
    </script>

    <button onclick="addGood();">Добавить товар</button>
    <div id="qwerty" style="width:100%; height:800px;"></div>
<? } else if ($_REQUEST['POPUP']) { ?>
    <div class="site-container">
        <button class="CDEK-widget__popup-button" onclick="widjet.open();">Открыть модуль</button>
    </div>
<? } ?>

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
