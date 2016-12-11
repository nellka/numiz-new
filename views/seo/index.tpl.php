<?
$time_script_start =time();

$tpl['seo_css_path'] = "http://www.numizmatik.ru/ocenka-stoimost-monet/";
?>
<!doctype html>
<html lang="en-US">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<title><?=$tpl['seo']['_Title']?></title>
		<meta name="description" content="<?=$tpl['seo']['_Description']?>">
		<meta name="Keywords" content="<?=$tpl['seo']['_Keywords']?>" >
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="format-detection" content="telephone=no">
		<meta name="MobileOptimized" content="320">
		<!--[if IE]><script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
		<!-- FAVICON -->
		<link href="favicon.ico" rel="shortcut icon">
		<!-- reviews end -->
		<link rel="stylesheet" href="<?=$tpl['seo_css_path']?>/css/widgets.css">
		
		<link rel="stylesheet" href="<?=$tpl['seo_css_path']?>/css/main.min.css?r=1">
		<link href='https://fonts.googleapis.com/css?family=Roboto:400,500,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'> </head>		
		<!-- Scripts load -->
		<script>
			window.jQuery || document.write('<script src="<?=$tpl['seo_css_path']?>/js/jquery-1.11.3.min.js"><\/script>')
		</script>
		<script src="<?=$tpl['seo_css_path']?>/js/widgets.js"></script>
		<script src="<?=$tpl['seo_css_path']?>/js/functions.js"></script>
		<script src="http://api-maps.yandex.ru/2.0/?load=package.full&amp;lang=ru-RU"></script>
		<div class="medium-res"></div>
		<div class="small-res"></div>
		<style>
		<?if($tpl['is_mobile']){?>
		img{
		max-width:280px;
		}
		<?} else {?>
    		.seo img{
                border: 1px solid #cccccc;
                max-width: 400px;
           }            
            .seo img:hover{
                display:block;
                top: 50px; 
                left: 90px; 
                z-index: 100;
                max-width:1000px;
                max-height:1000px;
                border: 1px solid #cccccc;        
            }	
		<?}?>
		</style>		
		 <script>
		 
		function _Call(){}
		
        function AddMakeCall(form){
			ga("send", "event", "user", "send-phone");
        	
        	var callphone = jQuery('#'+form+' #callphone').val().trim();
        	
        	if(!callphone){
        		jQuery('#'+form+' #error-call').text('Введите телефон');
        	} else {      		
        		
	            jQuery.ajax({
				    url: '<?=$cfg['site_dir']?>addcall.php?ajax=1&datatype=json', 
				    type: "POST",
				    data:{'callphone':callphone,'callfio':'ocenka'}  ,         
				    dataType : "json",                   
				    success: function (data, textStatus) { 
				    	var error = '';
			            for(i=0;i<data.errors.length;i++){
			                error+=data.errors[i];
			            }
				    	if(!error){

				    		jQuery('#'+form+' #error-call').text('Обратный звонок Вами заказан. Вам перезвонит наш сотрудник.');
				    	} else {				    		
				    		jQuery('#'+form+' #error-call').text(error);				    
				    	}				       
				    }
				});
        	} 
        }
        </script>
	<body>
		<!--[if lt IE 9]>
		<p class="browserupgrade" style="color: #fff;background: #000;padding: 20px 15px; text-align: center;">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
		<!-- HEADER -->
		<header class="header">
				<nav class="navigation" role="navigation">
					<div class="container"> <a href="#" class="nav-opener"><span>Menu</span></a>
						<div class="drop">
							<ul class="drop__list">
								<li class="drop__item"><a class="drop__link1" href="<?=$tpl['seo_css_path']?>#good-coins">Какие монеты покупаем / не покупаем</a></li>								
								<li class="drop__item"><a class="drop__link1" href="<?=$tpl['seo_css_path']?>#trust">Почему нам доверяют</a></li>
                                <li class="drop__item"><a class="drop__link1" href="<?=$tpl['seo_css_path']?>#our-experts">Эксперты-нумизматы</a></li>
								<li class="drop__item"><a class="drop__link1" href="<?=$tpl['seo_css_path']?>#reviews">Отзывы</a></li>
								<li class="drop__item"><a class="drop__link1" href="<?=$tpl['seo_css_path']?>#answers">Вопрос-ответ</a></li>
								<li class="drop__item"><a class="drop__link1" href="<?=$tpl['seo_css_path']?>#sale-coins">Где продать монеты в Москве</a></li>
							</ul>
						</div>
					</div>
				</nav>
				<div class="header-body" itemscope itemtype="http://schema.org/WPHeader">
					<div class="container">
						<div class="logo-block logo-block--header">
							<div class="logo-block__logo">
								<a href="http://www.numizmatik.ru/"><img itemprop="image" alt="Салон-магазин Клуб Нумизмат" title="Салон-магазин Клуб Нумизмат" class="logo-block__img" src="<?=$tpl['seo_css_path']?>/img/logo.png" alt="Клуб Нумизмат"></a>
							</div> <strong class="logo-block__slogan">Бесплатная оценка и скупка монет в Москве и МО</strong> </div>
						<div class="info info--header" itemscope  itemtype="http://schema.org/Organization">
							<div class="info__block info__block--address-block"> <span class="icon-map"></span>
								<div class="info__text-block"> <span class="info__text" itemprop="name">Салон-магазин Клуб Нумизмат</span> 
								<address class="info__address" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
								<span itemprop="addressLocality">Москва</span>, <span itemprop="streetAddress">ул.Тверская 12, стр.8</span>
								</address> 
								</div>
							</div>
							<div class="info__block info__block--work-block"> <span class="icon-clock"></span>
								<div class="info__text-block"> <span class="info__text">Режим работы оценщика:</span>
									<dl class="list-description"> <dt class="list-description__item">пн-пт:</dt>
										<dd class="list-description__definition">9:00-19:00</dd> <dt class="list-description__item">сб-вс:</dt>
										<dd class="list-description__definition">10:00-18:00</dd>
									</dl>
								</div>
							</div>
							<div class="info__block info__block--phone-block">
								<a class="icon-tel" href="tel:+78003331477" onclick='ga("send", "event", "user", "call");_Call();'></a>
								<div class="wrap-tel-block">
									<div class="tel-block">
										<span class="tel-block__tel"><a class="tel-block__link" href="tel:+78003331477" onclick='ga("send", "event", "user", "call");_Call();' itemprop="telephone">8-800-333-14-77</a></span>
										<span class="tel-block__tel"><a class="tel-block__link" href="tel:+79030060044" onclick='ga("send", "event", "user", "call");_Call();' itemprop="telephone">+7-903-006-00-44</a></span> </div>
									<div class="icons-tel-block">
										<a href="viber://add?number=79030060044" class="icon-viber"></a>
										<a href="whatsapp://send?text=Hello%20World!" class="icon-call"></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</header><!-- HEADER [END]  main-page-->
		<div class="welcome-to-numizmat">		
			<main class="content ">
				<div class="visual">
					<div class="container">
						<h1 class="visual__title" data-animation="fadeIn">Бесплатно оценим и выкупим Ваши монеты<br> <span class="visual__mark">на 20% дороже</span> банков и ломбардов в день обращения!</h1>
						<ul class="details-list">
							<li class="details-list__item"> <span class="icon-man"></span> <span class="details-list__text">Эксперты нумизматики I-ой категории</span> </li>
							<li class="details-list__item"> <span class="icon-person"></span> <span class="details-list__text">Центр Москвы, 2 минуты от метро</span> </li>
							<li class="details-list__item"> <span class="icon-security"></span> <span class="details-list__text">Конфиденциальность и безопасность</span> </li>
						</ul>
						<div class="services">
							<div class="services__block services__block--block-img" data-animation="fadeInLeft">
								<div class="block-circle"> <img class="block-circle__img" src="<?=$tpl['seo_css_path']?>/pic/img-services.jpg" alt="image description"> </div>
							</div>
							<div class="services__block services__block--block-list">
								<ul class="services__list">
									<li class="services__item"><span class="icon-checkmark"></span><span class="services__text">Моментальная наличная или безналичная оплата.</span></li>
									<li class="services__item"><span class="icon-checkmark"></span><span class="services__text">Бесплатный звонок по России для консультации.</span></li>
									<li class="services__item"><span class="icon-checkmark"></span><span class="services__text">Дорого покупаем инвестиционные монеты.</span></li>
									<li class="services__item"><span class="icon-checkmark"></span><span class="services__text">Получаете до 80% от реальной рыночной цены.</span></li>
									<li class="services__item"><span class="icon-checkmark"></span><span class="services__text">2 минуты от м.Чеховская, м.Пушкинская, м.Тверская.</span></li>
									<li class="services__item"><span class="icon-checkmark"></span><span class="services__text">Безопасность сделки.</span></li>
								</ul>
							</div>
							<div class="services__block services__block--block-evaluation" data-animation="fadeInRight">
								<div class="triangle-box">
									<div class="triangle-box__triangle-left">
										<div class="triangle-box__triangle-right">
											<div class="triangle-box__inner">
												<div class="evaluation">
													<form action="#" name='cf-1' id='cf-1'> 
													<strong class="evaluation__title">Вам нужны деньги на текущие расходы?</strong> 
													<span class="evaluation__sub-title">Купим Ваши монеты дороже ломбардов и банков</span> 
													<span class="block-coin__title--red" id='error-call'></span>
													<input type="phone" id=callphone name="callphone" placeholder="Введите Ваш телефон" class="js-phone-mask">														
													<button type="button" onclick="AddMakeCall('cf-1')">Бесплатно оценить монеты</button>
														<div class="evaluation__sup-text">Сегодня оценили 7 человек</div>
													</form>
												</div>
											</div>
										</div>
									</div>
									<div class="triangle-box__triangle"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="good-coins js-tabs-parent" id="good-coins">
					<div class="container" itemscope itemtype="http://schema.org/Article">
					<div style="text-align:left" class="seo">
                    					<?
                    echo "<h1><span itemprop=\"headline\">".$tpl['seo']['title']."</span></h1>";
                    echo '<div  itemprop="articleBody">'.$tpl['seo']['text']."</div>";?>
                    
                    <br>
                    <?
                    foreach ($tpl['seo']['next'] as $row){?>
                        <a href="<?=$cfg['site_dir']?><?=$row['alias']?>"><?=$row['title']?></a><br>
                    <?}?>
                    </div>
					</div>
				</div>
			</main>
			<!-- MAIN CONTENT [END] -->
		</div>
		<!-- FOOTER FIX [END] -->
		<!-- FOOTER -->
		<footer class="footer" itemscope itemtype="http://schema.org/WPFooter">
			<div class="container">
				<div class="logo-block">
					<div class="logo-block__logo">
						<a href="#"><img class="logo-block__img" src="<?=$tpl['seo_css_path']?>/img/logo.png" alt="Клуб Нумизмат"></a>
					</div> <strong class="logo-block__slogan">Бесплатная оценка и скупка монет в Москве и МО</strong> </div>
				<div class="info">
					<div class="info__holder">
						<div class="info__block info__block--address-block"> <span class="icon-map"></span>
							<div class="info__text-block"> <span class="info__text">Салон-магазин Клуб Нумизмат</span> <address class="info__address">Москва, ул.Тверская 12, стр.8</address> </div>
						</div>
						<div class="info__block info__block--work-block"> <span class="icon-clock"></span>
							<div class="info__text-block"> <span class="info__text">Режим работы оценщика:</span>
								<dl class="list-description"> <dt class="list-description__item">пн-пт:</dt>
									<dd class="list-description__definition">9:00-19:00</dd> <dt class="list-description__item">сб-вс:</dt>
									<dd class="list-description__definition">10:00-18:00</dd>
								</dl>
							</div>
						</div>
						<div class="info__block info__block--phone-block">
							<a href="tel:+78003331477" class="icon-tel" onclick='ga("send", "event", "user", "call");_Call();'></a>
							<div class="wrap-tel-block">
								<div class="tel-block">
									<span class="tel-block__tel"><a class="tel-block__link" onclick='ga("send", "event", "user", "call");_Call();' href="tel:+78003331477">8-800-333-14-77</a></span>
									<span class="tel-block__tel"><a class="tel-block__link" onclick='ga("send", "event", "user", "call");_Call();' href="tel:+79030060044">+7-903-006-00-44</a></span> </div>
								<div class="icons-tel-block">
									<a href="viber://add?number=79030060044" class="icon-viber"></a>
									<a href="whatsapp://send?text=Hello%20World!" class="icon-call"></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="footer-bottom">
				<div class="container">
					<ul class="footer-bottom__list">
						<li class="footer-bottom__item">2001-2016 Клуб Нумизмат</li>
						<li class="footer-bottom__item">ИНН:504908219824</li>
						<li class="footer-bottom__item">ОГРН:309504726100042</li>
					</ul>
					<br>
					<?
					 include $cfg['path'] . '/views/common/metrica.tpl.php'; ?>

				</div>
			</div>
		</footer>
		<!-- FOOTER END -->
		<!-- reviews -->
		<div class="popups-container">
			<div id="review-1" class="review-popup"> <strong class="review-popup__title">Some title</strong>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Modi soluta laboriosam, autem nemo magni quod praesentium optio adipisci, temporibus culpa commodi officia harum iusto eaque quae atque asperiores at, maxime. Lorem ipsum dolor sit amet, consectetur
					adipisicing elit. Velit rem enim excepturi? Sint minus magni quae dolorum animi eius, nostrum deleniti beatae cumque officiis nihil quos sequi nobis eum modi!</p>
			</div>
		</div>
		
	</body>

</html>


