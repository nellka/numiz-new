<?if(!$sh_mt){?>
<center><a href="<?=$_SERVER['REQUEST_URI']?>" onclick="$.cookie('fv', 1);" class="full">Полная версия сайта</a></center>
<?}?>
<div class="top-menu">        
  <div id="header" class="wraper">
		<ul class="left cssmenu">
	          <li>
				<a href="#" class="coins" onclick="$('#userMenu').hide();showInvis('shopMenu');return false;"></a>
				
					<ul id="shopMenu" >
						<div  class="shadoweffect shadowblock"></div>
						<!--<li class='main-top'>Магазин монет</li>-->
<li><a href='<?=$cfg['site_dir']?>shopcoins' title='Монеты стоимость(цены) весь мир' class=topmenu>Монеты</a></li>
<li><a href='<?=$cfg['site_dir']?>shopcoins/meloch' title='Дешевые монеты стоимость(цены) весь мир' class=topmenu>Мелочь </a></li>
<li><a href='<?=$cfg['site_dir']?>shopcoins/nabori_monet' title='Наборы монет стоимость(цены) весь мир' class=topmenu>Наборы монет</a></li>
<li><a href='<?=$cfg['site_dir']?>shopcoins/cvetnie_moneti' title='Цветные монеты' class="topmenu">Цветные монеты <span class="red">New!</span></a></li>
<li><a href='<?=$cfg['site_dir']?>shopcoins/banknoti' title='Банкноты стоимость(цены) весь мир' class=topmenu>Банкноты</a></li>
<li><a href='<?=$cfg['site_dir']?>shopcoins/aksessuary' title='Аксессуары для коллекционеров цены' class=topmenu>Аксессуары для монет</a></li>
<li><a href='<?=$cfg['site_dir']?>shopcoins/podarochnye_nabory' title='Подарочные наборы монет  стоимость(цены) весь мир' class=topmenu>Подарочные наборы</a></li>
<li><a href='<?=$cfg['site_dir']?>shopcoins/loty_dlya_nachinayushchih' title='Лоты монет для начинающих нумизматов' class=topmenu>Лоты монет для начинающих</a></li>
<li><a href='<?=$cfg['site_dir']?>shopcoins/notgeldy' title='Нотгельды стоимость(цены) весь мир' class=topmenu>Нотгельды </a></li>
<li><a href='<?=$cfg['site_dir']?>shopcoins/moneti_sssr' title='Дешевые монеты СССР цены и стоимость покупки и продажи смотреть' class="topmenu "> Монеты СССР</a></li>
<li><a href='<?=$cfg['site_dir']?>shopcoins/newcoins' title='Новинки монет 2014-2016' class=topmenu>Новинки монет 2014-<?=date('Y',time())?></a></li>
<li><a href='<?=$cfg['site_dir']?>shopcoins/knigi' title='Книги о нумизматике бонистике цены' class=topmenu>Книги о монетах</a>
<li><a href='<?=$cfg['site_dir']?>shopcoins/revaluation' title='Распродажа монет' class=topmenu>Распродажа монет</a></li>
<li><a href='<?=$cfg['site_dir']?>shopcoins/baraholka' title='Барахолка' class="topmenu">Барахолка</a></li>
<li><a href='<?=$cfg['site_dir']?>shopcoins/series' title='Серии монет'>Серии монет</a></li>
<li><a title="Гарантии подлинности монет и банкнот от Клуба Нумизмат" href="<?=$cfg['site_dir']?>garantii-podlinosti-monet">Гарантии подлиности монет</a></li>
<li><a title="Способы оплаты и доставки монет, банкнот, аксессуаров, книг по нумизматике" href="<?=$cfg['site_dir']?>shopcoins/delivery.php">Оплата и доставка</a></li>
<li><a title="Ваши заказы монет, банкнот, аксессуаров для коллекционеров в интернет-магазине монет" href="<?=$cfg['site_dir']?>shopcoins/order.php">Личный кабинет и Ваши заказы</a></li>
<li><a title="Монеты из ваших заказов в интернет-магазине монет" href="<?=$cfg['site_dir']?>shopcoins/mycoins.php"><span class="red">New</span> Монеты из ваших заказов</a></li>
<li><a title="Салон продаж" href="<?=$cfg['site_dir']?>shopinfo.php"><span class="red">Салон продаж</span> (Контакты)</a></li>
<!--<li><a title="Обмен монет" href="<?=$cfg['site_dir']?>change/obmen-monet.php">Обмен монетами</a></li>
<li><a title="Покупка-скупка монет, коллекций монет. " href="<?=$cfg['site_dir']?>gde-prodat-monety">Покупка/скупка монет</a></li>
<li><a title="Оценка стоимости(цены) монет(ы)" href="<?=$cfg['site_dir']?>ocenka-stoimost-monet">Оценка стоимости монет</a></li>
<li><a title="Для нумизматических дилеров" href="<?=$cfg['site_dir']?>dlya-dilerov">Для нумизматических дилеров</a></li>
<li><a title="Почему нам доверяют и покупают у нас монеты и банкноты?" href="<?=$cfg['site_dir']?>presentation.pdf">О Клубе Нумизмат</a></li>
<li><a title="Новые поступления монет,банкнот,аксессуаров - канал RSS" href="<?=$cfg['site_dir']?>shopcoins/rss.xml"><img border="0" src="images/rss.png"> Новые поступления</a></li>
<li class='main-top' onclick="showMainLeftMenu('catshop');">Каталог монет</li>
<div id="catshop" >
<li><a title="Каталог монет России, Германии, США и других стран" href="<?=$cfg['site_dir']?>catalognew/">Каталог монет</a></li>
<li><a title="Каталог наборов монет весь мир" href="<?=$cfg['site_dir']?>catalognew/index.php?materialtype=7">Наборы монет</a></li>
<li><a title="Каталог банкнот весь мир" href="<?=$cfg['site_dir']?>catalognew/prodaza_banknot_i_bon.html">Банкноты</a></li>
<li><a title="Каталог подарочных наборов монет весь мир" href="<?=$cfg['site_dir']?>catalognew/index.php?materialtype=4">Подарочные наборы</a></li>
<li><a title="Каталог аксессуаров для коллекционеров" href="<?=$cfg['site_dir']?>catalognew/index.php?materialtype=3">Аксессуары</a></li>
<li><a title="Каталог металлодетекторов(металлоискателй)" href="<?=$cfg['site_dir']?>catalognew/detectors.php">Металлоискатели</a></li>
<li><a title="Скачать программное обеспечение для нумизмата совершенно бесплатно" href="<?=$cfg['site_dir']?>program/">Программа нумизмата</a></li>
<li><a title="Скачать нумизматическое приложение для смартфона (планшета) бесплатно" href="<?=$cfg['site_dir']?>programandroid/">Приложения для смартфона</a></li>
</div>
<li class='main-top' onclick="showMainLeftMenu('m-fornum');">Нумизмату</li>
<div id="m-fornum">
<li><a title="Последние новости нумизматики" href="<?=$cfg['site_dir']?>news">Новости нумизматики</a></li>
<li><a title="Последние новости нумизматики - новостной канал RSS" target="_blank" href="http://news.numizmatik.ru/rss.xml"><img border="0" src="images/rss.png"> Канал новостей</a></li>
<li><a title="Нумизматическая библиотека - интересные для любого коллекционера статьи, обзоры и аналитические материалы" href="<?=$cfg['site_dir']?>/biblio/">Библиотека нумизмата</a></li>
<li><a title="Клуб Нумизмат Найдите коллекционеров в своем городе, узнайте кто еще интересуется монетами на вашу тематику" href="<?=$cfg['site_dir']?>collector/">Клуб Нумизмат</a></li>
<li><a title="Регистрация" href="<?=$cfg['site_dir']?>user/registration.php">Регистрация</a></li>
<li><a title="Нумизматический форум, место встречи коллекционеров всей страны" href="<?=$cfg['site_dir']?>forum/">Форум нумизматов</a></li>
<li><a title="Архив сатрого Нумизматического форума" href="http://tboard.numizmatik.ru/">Архив форума</a></li>
<li><a title="Клуб Нумизмат рекомендует быть осторожным Опасайтесь мошенников" href="<?=$cfg['site_dir']?>blacklist/">Черный список</a></li>
<li><a title="Форум кладоискателей" href="<?=$cfg['site_dir']?>detector/">Форум кладоискателей</a></li>
<li><a title="Объявления от коллекционеров по покупке/продаже/обмену монет" href="<?=$cfg['site_dir']?>advertise/">Объявления пользователей</a></li>
<li><a title="Нужна редкая монета, которую долго не можете найти? Оставьте у нас свою заявку." href="<?=$cfg['site_dir']?>buycoins/">Нужны монеты</a></li>
<li><a title="Динамический ценник на монеты СССР и России" href="<?=$cfg['site_dir']?>pricecoins/"><font color="red">New</font> Ценник на монеты</a></li>
<li><a title="Цены на монеты" href="<?=$cfg['site_dir']?>price/">Стоимость монет</a></li>
<li><a title="Нумизматический чат" href="<?=$cfg['site_dir']?>chat/">Нумизматический чат</a></li>
<li><a title="Распознавание монет" href="<?=$cfg['site_dir']?>classificator/">Распознавание монет</a></li>
<li><a title="Юбилейные монеты России" href="<?=$cfg['site_dir']?>russiancoins/">Юбилейные монеты</a></li>
<li><a title="Из истории происхождения монет" href="<?=$cfg['site_dir']?>moneti">Монета, история</a></li>
<li><a title="Подписка на поступления монет, банкнот, аксессуаров, новости нумизматики, форум нумизматов" href="<?=$cfg['site_dir']?>subscribe/">Подписка</a></li>
<li><a title="Видео для нумизматов" href="<?=$cfg['site_dir']?>video.php">Видео для нумизматов</a></li>
<li><a title="Игровой раздел клуба нумизмат" href="<?=$cfg['site_dir']?>game/">Игровой раздел</a></li>
</div>
<li class='main-top' onclick="showMainLeftMenu('m-auction');">Аукцион монет</li>
<div id="m-auction">
<li><a title="Аукцион монет России, Германии, США и других стран" href="<?=$cfg['site_dir']?>auction/">Аукцион монет</a></li>
<li><a title="Правила аукциона ионет" href="<?=$cfg['site_dir']?>auction/about.php">Правила аукциона</a></li>
</div>
<li class='main-top' onclick="showMainLeftMenu('m-journal');">Газеты и журналы</li>
<div id="m-journal">
<li><a title="Журнал - Антиквариат. Предметы искусства и коллекционирования" href="<?=$cfg['site_dir']?>journal/index.php?journal=2">Антиквариат</a></li>
<li><a title="Журнал - Золотой червонец" href="<?=$cfg['site_dir']?>journal/index.php?journal=3">Золотой червонец</a></li>
<li><a title="Журнал - Антикварное обозрение" href="<?=$cfg['site_dir']?>journal/index.php?journal=5">Антикварное обозрение</a></li>
</div>
<li class='main-top' onclick="showMainLeftMenu('m-serv');">Сервисы</li>
<div id="m-serv">
<li><a title="Баннерообменная сеть сайтов рунета, посвященных нумизматике, бонистике и коллекционированию" href="<?=$cfg['site_dir']?>bcnn/">Баннерная сеть</a></li>
<li><a title="Рейтинг сайтов рунета, посвященных нумизматике, бонистике и коллекционированию" href="<?=$cfg['site_dir']?>rating/">TOP 100</a></li>
<li><a title="Информатор Клуба Нумизмат" href="<?=$cfg['site_dir']?>quotes/">Информеры</a></li>
<li><a title="Информер новостей нумизматики" href="<?=$cfg['site_dir']?>news/webmaster.php">Новости нумизматики</a></li>
</div>
<li class='main-top' onclick="showMainLeftMenu('m-link');">Обратная связь</li>
<div id="m-link">
<li><a title="Вопрос недели опросы" href="<?=$cfg['site_dir']?>weekquestion/">Вопрос недели</a></li>
<li><a title="Гостевая книга" href="<?=$cfg['site_dir']?>guestbook/">Гостевая книга</a></li>
<li><a title="Ваши советы" href="<?=$cfg['site_dir']?>advice/">Ваши советы</a></li>
<li><a title="Контакты" href="<?=$cfg['site_dir']?>contacts/">Обратная связь</a></li>
<li><a title="Для прессы" href=<?=$cfg['site_dir']?>"press/">Для прессы</a></li>
</div>-->
					</ul>	

			</li>			
		</ul>
		<ul class="cssmenu right" id='rmenu'>
			<li>
				<a href='<?=$cfg['site_dir']?>/shopcoins/delivery.php' class="car decoratnone">
					<img src="<?=$cfg['site_dir']?>images/mobile/auto.jpg">
				</a>
			</li>
            <li>
				<a href='<?=$cfg['site_dir']?>shopinfo.php' class="decoratnone">
					<img src="<?=$cfg['site_dir']?>images/mobile/geolocation.gif">
				</a>
			</li>

			<li>   
				<a href='<?=$cfg['site_dir']?>/shopcoins/index.php?page=orderdetails' class="cart decoratnone">
					<img src="<?=$cfg['site_dir']?>images/mobile/mobile_korz.jpg">
					<span id=inorderamount  class="inorderamount">
						<?=$tpl['user']['product_amount']?>
					</span>
				</a>
    	        <span id=inordersum style="display:none"><?=$tpl['user']['summ']?></span>				
			</li>			
	       	<li>
				<a href="#" class="user decoratnone" onclick="$('#shopMenu').hide();showInvis('userMenu');return false;">
					<img src="<?=$cfg['site_dir']?>images/mobile/profile_mobile.jpg">
				</a>
				
			</li>				
		</ul>
    </div>
</div>  

<div class="search-top-module" id='searchblock'>   
     <form action="<?=$cfg['site_dir']?>shopcoins/index.php" method=get>         
			<input type="hidden"  value="4">    
          	<input type="text" class="search rounded" name=search id=search value="<?=$search?>" placeholder="Россия, 1 рубль">
			<input type="submit" id=globalsearch-submit name="submit" value="Найти">             
    </form>    
</div>		
<div id="userMenu" class="blocklogin">
    
	<?if (!$tpl['user']["is_logined"]) {?>
	   <div class="u_l shadoweffect">		
		<li><a class="abold " href="<?=$cfg['site_dir']?>user/login.php">Войти</a> или <a  class="abold " href="<?=$cfg['site_dir']?>user/registration.php">Зарегистрироваться</a></li>			
		</div>    
	<?} else {?>
	   <div class="n_l shadoweffect">	
		<li><a class="abold " href='<?=$cfg['site_dir']?>shopcoins/order.php'>Мои заказы</a></li>
		<li><a class="abold " href="<?=$cfg['site_dir']?>shopcoins/?catalognewstr=1&savesearch=1">Монеты по заявкам (<?=$tpl['user']['catalogamount']?>)</li>
		<li><a href='<?=$cfg['site_dir']?>shopcoins/mycoins.php' title='Монеты из ваших заказов в интернет-магазине монет' class="abold "><span class="error">Монеты из ваших заказов</span></a> </li>
		<li><a class="abold " href="<?=$cfg['site_dir']?>shopcoins/?logout=1">Выйти</a></li>    
		</div>    
    <?}?>									
</div>	
	
<div id=MainBascet></div>
<div class="triger" id='small-logo' style="display:none">
<?php include $cfg['path'] . '/views/common/small-logo.tpl.php';?>
</div>
<div class="wraper" id=top>

<div class="logo" id="logoblock">
	<table width=100%>
		<tr>
			<td>
				 <a class="logo-img" href="http://www.numizmatik.ru"><img src="<?=$cfg['site_dir']?>images/logo_small.jpg" border=0></a>
			</td>
			<td class="fontsize12">
				<div id="contact-top-module">  
					<p><a href="http://www.numizmatik.ru/shopinfo.php" class="black"><b>Москва</b>, ул. Тверская 12 стр. 8</a><br>
				</div>       
				<div id="contact-top-phone" class="contact-top-phone">
					 +7 (800) 333-14-77 <br>
					 +7 (903) 006-00-44 <br>
					 +7 (915) 002-22-23
				</div> 
			</td>
		</tr>
	</table>
</div>
<div class="abold" >
	<center>
	 <a class="abold" href="http://www.numizmatik.ru/shopcoins">Продажа</a>,
	<a class="abold" href="<?=$cfg['site_dir']?>ocenka-stoimost-monet">Оценка </a> и 
    <a  class="abold" href="http://www.numizmatik.ru/gde-prodat-monety">Скупка монет</a> <br>
   
	<a href="http://www.numizmatik.ru/shopinfo.php" class="black">В Москве и Санкт-Петербурге</a>
	</center>
</div>
   
     
<script>
$(document).ready(function(){   
    $('body').on("click", ".ui-widget-overlay", function() {
          $(".ui-icon.ui-icon-closethick").trigger("click");
    }); 
 /* $('.dropdown li.top').click(function(event) {
       
        var cur_href =  $('a',this).filter( ':first' );       
        var is_active =  cur_href.hasClass('active');   
        if(!cur_href.hasClass('active_m'))  cur_href.addClass('active_m');         
        var is_visible = btn.is(':visible')?true:false;
        $('div.submenu').hide();
        
        if(!is_visible){
             btn.show();
        } else {
            btn.hide();
            cur_href.removeClass('active_m');
            if(is_active) cur_href.addClass('active');
        }
        return false;
    });  */
});
</script>     
      