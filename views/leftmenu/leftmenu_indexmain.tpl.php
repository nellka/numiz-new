<div class="menu-heading  hidden-shopcoins-menu" id="hidden-shop-menu">
    <span class='hidden-shopcoins-span'><a href='<?=$cfg['site_dir']?>shopcoins/moneti' title='Монеты стоимость(цены) весь мир' class="white mw">Магазин монет</a></span></div>
<ul class="menu-sidebar top" id="left_menu_full" itemscope itemtype="http://schema.org/SiteNavigationElement">
<li><a href='<?=$cfg['site_dir']?>shopcoins' title='Монеты стоимость(цены) весь мир' class=topmenu>Монеты</a></li>
<li><a href='<?=$cfg['site_dir']?>shopcoins/meloch' title='Дешевые монеты стоимость(цены) весь мир' class=topmenu>Мелочь </a></li>
<li><a href='<?=$cfg['site_dir']?>shopcoins/nabori_monet' title='Наборы монет стоимость(цены) весь мир' class=topmenu>Наборы монет</a></li>
<li><a href='<?=$cfg['site_dir']?>shopcoins/cvetnie_moneti' title='Цветные монеты' class="topmenu">Цветные монеты <span class="red">New!</span></a></li>
<li><a href='<?=$cfg['site_dir']?>shopcoins/banknoti' title='Банкноты стоимость(цены) весь мир' class=topmenu>Банкноты</a></li>
<li><a href='<?=$cfg['site_dir']?>shopcoins/aksessuary' title='Аксессуары для коллекционеров цены' class=topmenu>Аксессуары для монет</a></li>
<li><a href='<?=$cfg['site_dir']?>shopcoins/podarochnye_nabory' title='Подарочные наборы монет  стоимость(цены) весь мир' class=topmenu>Подарочные наборы</a></li>
<li><a href='<?=$cfg['site_dir']?>shopcoins/loty_dlya_nachinayushchih' title='Лоты монет для начинающих' class=topmenu>Лоты монет для начинающих</a></li>
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
<li><a title="Обмен монет" href="<?=$cfg['site_dir']?>change/obmen-monet.php">Обмен монетами</a></li>
<li><a title="Покупка-скупка монет, коллекций монет. " href="<?=$cfg['site_dir']?>gde-prodat-monety">Покупка/скупка монет</a></li>
<li><a title="Оценка стоимости(цены) монет(ы)" href="<?=$cfg['site_dir']?>ocenka-stoimost-monet">Оценка стоимости монет</a></li>
<li><a title="Для нумизматических дилеров" href="<?=$cfg['site_dir']?>dlya-dilerov">Для нумизматических дилеров</a></li>
<li><a title="Почему нам доверяют и покупают у нас монеты и банкноты?" href="<?=$cfg['site_dir']?>presentation.pdf">О Клубе Нумизмат</a></li>
<li><a title="Новые поступления монет,банкнот,аксессуаров - канал RSS" href="<?=$cfg['site_dir']?>shopcoins/rss.xml">
        <img src="<?=$cfg['site_dir']?>images/rss.png" title="Новые поступления монет,банкнот,аксессуаров - канал RSS" alt="Новые поступления монет,банкнот,аксессуаров - канал RSS"> Новые поступления</a>
</li>
 </ul>  

<div class="statictic">
<h5>Статистика Клуба Нумизмат</h5>
Зарегистрированных пользователей:<?=$tpl['stat']['users']?><br>
В магазине позиций товар: <?=$tpl['stat']['items']?><br>
Новостей о нумизматике:	<?=$tpl['stat']['news']?>	<br>
На форуме нумизматов тем: 15942	<br>
В "Тор 100" нумизматических сайтов: 448 <br><br>
</div>
 
<div class="menu-heading hidden-shopcoins-menu" onclick="showMainLeftMenu('left_menu_cat')">
    <span class='hidden-shopcoins-span'>Каталог монет</span></div>
<ul class="menu-sidebar top" id="left_menu_cat">
<li><a title="Каталог монет России, Германии, США и других стран" href="<?=$cfg['site_dir']?>catalognew">Каталог монет</a></li>
<li><a title="Каталог наборов монет весь мир" href="<?=$cfg['site_dir']?>catalognew/index.php?materialtype=7">Наборы монет</a></li>
<li><a title="Каталог банкнот весь мир" href="<?=$cfg['site_dir']?>catalognew/prodaza_banknot_i_bon.html">Банкноты</a></li>
<li><a title="Каталог подарочных наборов монет весь мир" href="<?=$cfg['site_dir']?>catalognew/index.php?materialtype=4">Подарочные наборы</a></li>
<li><a title="Каталог аксессуаров для коллекционеров" href="<?=$cfg['site_dir']?>catalognew/index.php?materialtype=3">Аксессуары</a></li>
<li><a title="Каталог металлодетекторов(металлоискателй)" href="<?=$cfg['site_dir']?>catalognew/detectors.php">Металлоискатели</a></li>
<li><a title="Скачать программное обеспечение для нумизмата совершенно бесплатно" href="<?=$cfg['site_dir']?>program/">Программа нумизмата</a></li>
<li><a title="Скачать нумизматическое приложение для смартфона (планшета) бесплатно" href="<?=$cfg['site_dir']?>programandroid/">Приложения для смартфона</a></li>
 </ul>  

<div class="menu-heading hidden-shopcoins-menu"  onclick="showMainLeftMenu('left_menu_news')">
    <span class='hidden-shopcoins-span'>Нумизмату</span></div>
<ul class="menu-sidebar top" id="left_menu_news" >
<li><a title="Последние новости нумизматики" href="<?=$cfg['site_dir']?>news">Новости нумизматики</a></li>
<li><a title="Последние новости нумизматики - новостной канал RSS" target="_blank" href="http://numizmatik.ru/news/rss.xml">
        <img title="Последние новости нумизматики - новостной канал RSS" alt="Последние новости нумизматики - новостной канал RSS"  src="<?=$cfg['site_dir']?>images/rss.png"> Канал новостей</a>
</li>
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
</ul>  

<div class="menu-heading hidden-shopcoins-menu" onclick="showMainLeftMenu('left_menu_auction')">
    <span class='hidden-shopcoins-span'>Аукцион монет</span></div>
<ul class="menu-sidebar top" id="left_menu_auction">
<li><a title="Аукцион монет России, Германии, США и других стран" href="<?=$cfg['site_dir']?>auction/">Аукцион монет</a></li>
<li><a title="Правила аукциона ионет" href="<?=$cfg['site_dir']?>auction/about.php">Правила аукциона</a></li>
</ul>  
<div class="menu-heading hidden-shopcoins-menu" onclick="showMainLeftMenu('left_menu_journal')">
    <span class='hidden-shopcoins-span'>Газеты и журналы</span></div>
<ul class="menu-sidebar top" id="left_menu_journal">
<li><a title="Журнал - Антиквариат. Предметы искусства и коллекционирования" href="<?=$cfg['site_dir']?>journal/index.php?journal=2">Антиквариат</a></li>
<li><a title="Журнал - Золотой червонец" href="<?=$cfg['site_dir']?>journal/index.php?journal=3">Золотой червонец</a></li>
<li><a title="Журнал - Антикварное обозрение" href="<?=$cfg['site_dir']?>journal/index.php?journal=5">Антикварное обозрение</a></li></ul>  

<div class="menu-heading hidden-shopcoins-menu" onclick="showMainLeftMenu('left_menu_serv')">
    <span class='hidden-shopcoins-span'>Сервисы</span></div>
<ul class="menu-sidebar top" id="left_menu_serv">
<li><a title="Баннерообменная сеть сайтов рунета, посвященных нумизматике, бонистике и коллекционированию" href="<?=$cfg['site_dir']?>bcnn/">Баннерная сеть</a></li>
<li><a title="Рейтинг сайтов рунета, посвященных нумизматике, бонистике и коллекционированию" href="<?=$cfg['site_dir']?>rating/">TOP 100</a></li>
<li><a title="Информатор Клуба Нумизмат" href="<?=$cfg['site_dir']?>quotes/">Информеры</a></li>
<li><a title="Информер новостей нумизматики" href="<?=$cfg['site_dir']?>news/webmaster.php">Новости нумизматики</a></li>
</ul>  
<div class="menu-heading hidden-shopcoins-menu" onclick="showMainLeftMenu('left_menu_link')">
    <span class='hidden-shopcoins-span'>Обратная связь</span></div>
<ul class="menu-sidebar top" id="left_menu_link">
<li><a title="Вопрос недели опросы" href="<?=$cfg['site_dir']?>weekquestion/">Вопрос недели</a></li>
<li><a title="Гостевая книга" href="<?=$cfg['site_dir']?>guestbook/">Гостевая книга</a></li>
<li><a title="Ваши советы" href="<?=$cfg['site_dir']?>advice/">Ваши советы</a></li>
<li><a title="Контакты" href="<?=$cfg['site_dir']?>contacts/">Обратная связь</a></li>
<li><a title="Для прессы" href="<?=$cfg['site_dir']?>press/">Для прессы</a></li>
</ul>  

<script> 

 $('.hidden-shopcoins-menu').click(function (e) {
     if ($(e.target).prop('id') == "hidden-shop-menu") {
        showMainLeftMenu('left_menu_full');return false;
     }    
});
    
    
function showMainLeftMenu(name){
    
    if(!$('#'+name).is(':visible')){
        $('#'+name).show();
    } else {
        $('#'+name).hide();
    }
    return false;
}   
</script>