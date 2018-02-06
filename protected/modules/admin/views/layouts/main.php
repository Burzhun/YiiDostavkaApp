<!DOCTYPE html>
<html lang="ru">
<head>

	<!-- ЗАГОЛОВКИ -->
	<meta charset="utf-8">
	<title>Доставка05. Административная страница</title>
	<link href="/images/favicon.ico" rel="SHORTCUT ICON">

	<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>

	<script type="text/javascript" src="/js/jquery_tablesorter.js"></script>
	<script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
	<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- BOOTSTRAP -->
	<link href="/css/bootstrap.css" rel="stylesheet">
	<script src="/js/bootstrap.js"></script>
	<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
	<!-- Инициализируем компоненты -->
	<script type="text/javascript">
		$.ready(function () {
		});
	</script>
	<style>
		.appr-site,
		.appr-partner,
		.appr-delivered{
			display: block;
		}

		.appr-site{
			background: url("/images/blue-fon.png") repeat-x;
			background-size: contain;
			color: #ffffff;
			height: 33%;
		}
		.appr-partner{
			background: url("/images/yellow-fon.png") repeat-x;
		    background-size: contain;
		    color: #000000;
		    height: 33%;
		}
		.appr-delivered{
			background: url("/images/green-fon.png") repeat-x;
    		background-size: contain;
    		color: #ffffff;
    		height: 33%;
		}
		.table tbody .appr-td{
			text-align: center;
			padding: 0;
		}
		.appr-div{
			position: absolute;
			top: 0;
			bottom: 0;
			left: 0;
			right: 0;
		}
	</style>
</head>

<body>

<!-- Верхнее меню -->
<div class="navbar">
	<div class="navbar-inner">
		<div class="container">
			<a class="brand" href="/">Доставка 05</a>
			<div class="nav-collapse">
				<?php
				$this->widget('zii.widgets.CMenu', array(
					'items' => array(
						array(
							'label' => 'Главная',
							'url' => array('/admin'),
							'active' => $this->id == 'default' && $this->action->id == 'index' ? true : false,
						),
						array('label' => 'Пользователи', 'url' => array('/admin/user'), 'active' => $this->id == 'user' ? true : false),
						array('label' => 'Партнеры', 'url' => array('/admin/partner'), 'active' => $this->id == 'partner' ? true : false),
						array('label' => 'Заказы', 'url' => array('/admin/order'), 'active' => $this->id == 'order' ? true : false),

						User::hasStatAccess() ? array(
							'label' => 'Контент сайта',
							'url' => false,
							'htmlOptions' => array('class' => 'dropdown'),
							'linkOptions' => array('encode' => false, 'class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'),
							'itemOptions' => array('class' => 'dropdown'),
							'submenuOptions' => array('class' => 'dropdown-menu'),
							'items' => array(
								array('label' => 'Тег', 'url' => array('/admin/tag'), 'active' => $this->id == 'tag' ? true : false),
								array('label' => 'Отзывы', 'url' => array('/admin/review'), 'active' => $this->id == 'review' ? true : false),
								array('label' => 'Опрос', 'url' => array('/admin/opros'), 'active' => $this->id == 'opros' ? true : false),
								array('label' => 'Комментарии', 'url' => array('/admin/default/orderComments'), 'active' => $this->id == 'default' && $this->action->id == 'orderComments' ? true : false),
								array('label' => 'Отмены', 'url' => array('/admin/default/orderCancels'), 'active' => $this->id == 'default' && $this->action->id == 'orderCancels' ? true :false),
								array('label' => 'Страницы', 'url' => array('/admin/pages'), 'active' => $this->id == 'pages' ? true : false),
								array('label' => 'Баннеры', 'url' => array('/admin/banners'), 'active' => $this->id == 'banners' ? true : false),
								array('label' => 'Бег.строка', 'url' => array('/admin/runtext'), 'active' => $this->id == 'runtext' ? true : false),
								array('label' => 'Бонусы', 'url' => array('/admin/bonus'), 'active' => $this->id == 'bonus' ? true : false),
								array('label' => 'Города', 'url' => array('/admin/city'), 'active' => $this->id == 'city' ? true : false),
								array('label' => 'Домены', 'url' => array('/admin/domain'), 'active' => $this->id == 'domain' ? true : false),
								array('label' => 'Тексты', 'url' => array('/admin/text'), 'active' => $this->id == 'text' ? true : false),
								array('label' => 'Промокод', 'url' => array('/admin/promo'), 'active' => $this->id == 'promo' ? true : false),
								array('label' => 'Настройки и seo', 'url' => array('/admin/config'), 'active' => $this->id == 'config' ? true : false),
								array('label' => 'Seo', 'url' => array('/admin/default/seo'), 'active' => $this->id == 'seo' ? true : false),
								array('label' => 'Районы ', 'url' => array('/admin/rayon'), 'active' => $this->id == 'rayon' ? true : false),
								array('label' => 'Разделы ', 'url' => array('/admin/section'), 'active' => $this->id == 'section' ? true : false),
								array('label' => 'Редирект ', 'url' => array('/admin/redirects'), 'active' => $this->id == 'redirects' ? true : false),
								array('label' => 'Забаненные ', 'url' => array('/admin/default/banned'), 'active' => $this->id == 'banned' ? true : false),
								array('label' => 'Ошибки ', 'url' => array('/admin/default/errors'), 'active' => $this->id == 'errors' ? true : false),
								array('label' => 'Причины отмены ', 'url' => array('/admin/default/cancel_reasons'), 'active' => $this->id == 'cancel_reasons' ? true : false),
							)
						):null,

						User::hasStatAccess() ? array('label' => 'Блог', 'url' => array('/admin/post'), 'active' => $this->id == 'post' ? true : false) :null,

						User::hasStatAccess()? array(
							'label' => 'Статистика',
							'url' => false,
							'htmlOptions' => array('class' => 'dropdown'),
							'linkOptions' => array('encode' => false, 'class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'),
							'itemOptions' => array('class' => 'dropdown'),
							'submenuOptions' => array('class' => 'dropdown-menu'),
							'items' => array(
								array('label' => 'Статистика по устройствам', 'url' => array('/admin/statistics/device?from='.date('Y-m-d'))),
								array('label' => 'Карта заказов', 'url' => array('/admin/statistics/map')),
								array('label' => 'Информация по платежам', 'url' => array('/admin/statistics/payment')),
								array('label' => 'Статистика по партнерам', 'url' => array('/admin/statistics/partners?from='.date('Y-m-01'))),
								array('label' => 'Информация по пользователям', 'url' => array('/admin/statistics/users')),
								array('label' => 'Информация по заказам', 'url' => array('/admin/statistics/orders')),
								array('label' => 'Информация по заказам операторов', 'url' => array('/admin/statistics/orders2?date=2016-05-23')),
								array('label' => 'Информация по отмененным заказам', 'url' => array('/admin/statistics/cancelledOrders')),
								array('label' => 'Статистика отмен', 'url' => array('/admin/statistics/cancels')),
								array('label' => 'Статистика заказов по времени', 'url' => array('/admin/statistics/orders_time?date_from='.date('Y-m-d',time()-86400).'&date_to='.date('Y-m-d',time()-86400))),
							)
						):null,

						array('label' => 'Выход', 'url' => array('/user/logout')),
					),
					'htmlOptions' => array('class' => "nav"),
				));
				?>
			</div>
			<!-- /.nav-collapse -->
		</div>
	</div>
	<!-- /navbar-inner -->
</div>
<!-- /navbar -->
<?php echo $content; ?>
<br>
<!--LiveInternet counter--><script type="text/javascript">document.write("<a href='//www.liveinternet.ru/click;dostavka05' target=_blank><img src='//counter.yadro.ru/hit;dostavka05?t45.2;r" + escape(document.referrer) + ((typeof(screen)=="undefined")?"":";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?screen.colorDepth:screen.pixelDepth)) + ";u" + escape(document.URL) +";h"+escape(document.title.substring(0,80)) +  ";" + Math.random() + "' border=0 width=31 height=31 alt='' title='LiveInternet'><\/a>")</script><!--/LiveInternet-->
</body>
</html>