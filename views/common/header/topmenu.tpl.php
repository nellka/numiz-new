<div id="cssmenu" class="wraper">
    <ul id="ddmenu">  
       <li class="home"><a href="<?=$cfg['site_dir']?>"></a>
       <li class="top"><a href="http://www.numizmatik.ru" class="<?=('/'==$tpl['current_page'])?'active':''?>" >Магазин монет</a>
       <div id="menu3"  class="submenu">   
            <ul>
            <li><a href='<?=$cfg['site_dir']?>shopcoins/' title='Монеты стоимость(цены) весь мир' class=topmenu>Монеты</a></li>
            <li><a href='<?=$cfg['site_dir']?>shopcoins/index.php?materialtype=8' title='Дешевые монеты стоимость(цены) весь мир' class=topmenu>Мелочь </a></li>
            <li><a href='<?=$cfg['site_dir']?>shopcoins/index.php?materialtype=10' title='Нотгельды стоимость(цены) весь мир' class=topmenu>Нотгельды </a></li>
            <li><a href='<?=$cfg['site_dir']?>shopcoins/index.php?materialtype=7' title='Наборы монет стоимость(цены) весь мир' class=topmenu>Наборы монет</a></li>
             <li><a href='<?=$cfg['site_dir']?>shopcoins/index.php?materialtype=9' title='Лоты монет для начинающих нумизматов' class=topmenu>Лоты монет для начинающих нумизматов</a></li>
            <li><a href='<?=$cfg['site_dir']?>shopcoins/index.php?search=newcoins' title='Новинки 2013-2015' class=topmenu>Новинки 2015-2015</a></li>
            <li><a href='<?=$cfg['site_dir']?>shopcoins/index.php?materialtype=2' title='Банкноты стоимость(цены) весь мир' class=topmenu>Боны</a></li>
            <li><a href='<?=$cfg['site_dir']?>shopcoins/?materialtype=3' title='Аксессуары для коллекционеров цены' class=topmenu>Аксессуары для монет</a></li>
            <li><a href='<?=$cfg['site_dir']?>shopcoins/?materialtype=4' title='Подарочные наборы монет  стоимость(цены) весь мир' class=topmenu>Подарочные наборы</a></li>
            <li><a href='<?=$cfg['site_dir']?>shopcoins/index.php?materialtype=5' title='Книги о нумизматике бонистике цены' class=topmenu>Книги о монетах</a>
            <li><a href='<?=$cfg['site_dir']?>shopcoins/index.php?search=revaluation' title='Распродажа монет' class=topmenu>Распродажа монет</a></li>    
            </ul>          
             <div class="discription">
           <img src="<?=$cfg['site_dir']?>images/moneta1.jpg">
           <p>
            Клуб "Нумизмат" специализируется на продаже, покупке и оценке монет, в том числе из драгоценных металлов.В наших салонах продаж всегда в наличии большое количество разнообразных монет разных стран и городов. Так же мы предоставляем такую услугу как оценка и покупка ваших монет. Наши специалисты имеют огромный навык в направлении "нумизматика", что помогает в короткое время оценить стоимость монет, благодаря чему вы с лёгкостью сможете продать ненужные вам монеты.  </p>
           </div>
            </div>
       </li>       	
       <li class="top"><a href="http://www.numizmatik.ru/shopcoinshelp.php" >Покупателям</a>
          <div id="menu4"  class="submenu">          
            <ul>                
                <li><a href='<?=$cfg['site_dir']?>shopcoins/delivery.php' title='Способы оплаты и доставки монет, банкнот, аксессуаров, книг по нумизматике'>Оплата и доставка</a> </li>
                <li><a href='<?=$cfg['site_dir']?>shopcoins/how.php' title='Как сделать заказ в магазине' class=topmenu>Как заказать</a></li>
                <li><a href='<?=$cfg['site_dir']?>shopcoins/shopcoinsrules.php' title='Правила монетной лавки' class=topmenu>Правила магазина</a> </li>
                <li><a href='<?=$cfg['site_dir']?>shopcoins/shopcoinshelp.php' title='Помощник по магазину' class=topmenu>ЧаВо по магазину</a> </li>
                 <li><a href='http://www.numizmatik.ru/garantii-podlinosti-monet' title='Гарантии подлинности монет и банкнот от Клуба Нумизмат' class=topmenu>Гарантии подлиности монет</a></li> 
                <li><a href='<?=$cfg['site_dir']?>shopcoins/order.php' title='Ваши заказы монет, банкнот, аксессуаров для коллекционеров в интернет-магазине монет' class=topmenu>Ваши заказы</a></li>
                <li><a href='shopinfo.php' title='Салон продаж' class=topmenu>Контакты</a></li>
                </ul>
           </div> 
       </li>            
      <li class="top"><a href="<?=$cfg['site_dir']?>ocenka-stoimost-monet" class="<?=in_array($tpl['current_page'],array('ocenka-stoimost-monet','gde-prodat-monety'))?'active':''?>"><span>Скупка-оценка</span></a>
       <div id="menu5"  class="submenu">          
            <ul>               
                <li><a href='<?=$cfg['site_dir']?>ocenka-stoimost-monet' title='Оценка стоимости(цены) монет(ы)' class=topmenu>Оценка стоимости монет</a></li>
                <li><a href='<?=$cfg['site_dir']?>gde-prodat-monety' title='Покупка-скупка монет, коллекций монет. ' class=topmenu>Покупка/скупка монет</a></li>
                <li><a href='http://www.numizmatik.ru/change/obmen-monet.php' title='Обмен монет' class=topmenu>Обмен монетами</a></li>
                <li><a href='http://www.numizmatik.ru/shopinfo.php' title='Салон продаж' class=topmenu>Контакты</a></li>
               </ul> 
           </div>      
      </li>
      <li class="top"><a href="http://www.numizmatik.ru/ocenka-stoimost-monet"><span>Каталог</span></a>
       <div id="menu6"  class="submenu">          
            <ul>          
                <li><a href='http://www.numizmatik.ru/catalognew/' title='Каталог монет России, Германии, США и других стран' class=topmenu>Каталог монет</a></li>
                <li><a href='http://www.numizmatik.ru/catalognew/index.php?materialtype=7' title='Каталог наборов монет весь мир' class=topmenu>Наборы монет</a></li>
                <li><a href='http://www.numizmatik.ru/catalognew/index.php?materialtype=2' title='Каталог банкнот весь мир' class=topmenu>Банкноты</a></li>
                <li><a href='http://www.numizmatik.ru/catalognew/index.php?materialtype=4' title='Каталог подарочных наборов монет весь мир' class=topmenu>Подарочные наборы</a></li>
                <li><a href='http://www.numizmatik.ru/catalognew/index.php?materialtype=3' title='Каталог аксессуаров для коллекционеров' class=topmenu>Аксессуары</a></li>
                <li><a href='http://www.numizmatik.ru/catalognew/detectors.php' title='Каталог металлодетекторов(металлоискателй)' class=topmenu>Металлоискатели</a></li>
                <li><a href='http://www.numizmatik.ru/program/' title='Скачать программное обеспечение для нумизмата совершенно бесплатно' class=topmenu>Программа нумизмата</a></li>
                <li><a href='http://www.numizmatik.ru/programandroid/' title='Скачать нумизматическое приложение для смартфона (планшета) бесплатно' class=topmenu>Приложения для смартфона</a></li>
                </ul>
            </div>      
      </li>
      <li class="top"><a href="http://www.numizmatik.ru/ocenka-stoimost-monet"><span>Аукцион</span></a>
      <div id="menu7"  class="submenu">          
            <ul>          
                <li><a href='http://www.numizmatik.ru/auction/' title='Аукцион монет России, Германии, США и других стран' class=topmenu>Аукцион монет</a></li>
                <li><a href='http://www.numizmatik.ru/auction/about.php' title='Правила аукциона ионет' class=topmenu>Правила аукциона</a></li>
            </ul>
         </div>
      </li>      
      <li class="top"><a href="http://www.numizmatik.ru/ocenka-stoimost-monet"><span>Форумы</span></a>      
      <div id="menu8"  class="submenu">          
            <ul>          
                <li><a href='http://www.numizmatik.ru/forum/' title='Нумизматический форум, место встречи коллекционеров всей страны' class=topmenu>Форум нумизматов</a></li>
                <li><a href='http://tboard.numizmatik.ru/' title='Архив старого Нумизматического форума' class=topmenu>Архив форума</a></li>
                <li><a href='http://www.numizmatik.ru/blacklist/' title='Клуб Нумизмат рекомендует быть осторожным Опасайтесь мошенников' class=topmenu>Черный список</a></li>
                <li><a href='http://www.numizmatik.ru/detector/' title='Форум кладоискателей' class=topmenu>Форум кладоискателей</a></li>
            </ul>
        </div>        
      </li>
      <li class="top"><a href="http://www.numizmatik.ru/ocenka-stoimost-monet"><span>Общая информация</span></a>
       <div id="menu9"  class="submenu">          
          <ul>          
          <li><a href='http://news.numizmatik.ru/' title='Последние новости нумизматики' class=topmenu>Новости нумизматики</a></li>
          <li><a href='http://www.numizmatik.ru/video.php' title='Видео для нумизматов' class=topmenu>Видео для нумизматов</a></li>
          <li><a href='http://www.numizmatik.ru/dlya-dilerov' title='Для нумизматических дилеров' class=topmenu>Для нумизматических дилеров</a></li>
          <li><a href='http://www.numizmatik.ru/change/obmen-monet.php' title='Обмен монет' class=topmenu>Обмен монетами</a></li>
          <li><a href='http://www.numizmatik.ru/press/' title='Для прессы' class=topmenu>Для прессы</a></li>
          <li><a href='http://www.numizmatik.ru/advice/' title='Ваши советы' class=topmenu>Ваши советы</a></li>
          <li><a href='http://www.numizmatik.ru/shopinfo.php' title='Салон продаж' class=topmenu>Контакты</a></li>      
          </ul>
       </div>
     </li>
     <li class="top"><a href="http://www.numizmatik.ru/ocenka-stoimost-monet"><span>Полезное</span></a>
      <div id="menu10"  class="submenu">          
            <ul>         
             <li><a href='biblio/' title='Нумизматическая библиотека - интересные для любого коллекционера статьи, обзоры и аналитические материалы' class=topmenu>Библиотека нумизмата</a> </li>
             <li><a href='collector/' title='Клуб Нумизмат Найдите коллекционеров в своем городе, узнайте кто еще интересуется монетами на вашу тематику' class=topmenu>Клуб Нумизмат</a> </li>
             <li><a href='advertise/' title='Объявления от коллекционеров по покупке/продаже/обмену монет' class=topmenu>Объявления пользователей</a> </li>
             <li><a href='buycoins/' title='Нужна редкая монета, которую долго не можете найти? Оставьте у нас свою заявку.' class=topmenu>Нужны монеты</a> </li>
             <li><a href='pricecoins/' title='Динамический ценник на монеты СССР и России' class=topmenu>Ценник на монеты</a> </li>
             <li><a href='price/' title='Цены на монеты' class=topmenu>Стоимость монет</a> </li>
             <li><a href='chat/' title='Нумизматический чат' class=topmenu>Нумизматический чат</a> </li>
             <li><a href='classificator/' title='Распознавание монет' class=topmenu>Распознавание монет</a> </li>
             <li><a href='russiancoins/' title='Юбилейные монеты России' class=topmenu>Юбилейные монеты</a> </li>
             <li><a href='moneti' title='Из истории происхождения монет' class=topmenu>Монета, история</a> </li>
             <li><a href='subscribe/' title='Подписка на поступления монет, банкнот, аксессуаров, новости нумизматики, форум нумизматов' class=topmenu>Подписка</a> </li>
             <li><a href='game/' title='Игровой раздел Клуба Нумизмат' class=topmenu>Игровой раздел</a> </li>
             </ul>
      	</div>
      </li>
      
       <li class="top"><a href="http://www.numizmatik.ru/ocenka-stoimost-monet"><span>Контакты</span></a>       
          <div id="menu11"  class="submenu">          
            <ul>          
                <li><a href='shopinfo.php' title='Салон продаж' class=topmenu>Контакты</a></li>      
            </ul> 
           </div>
       </li>
    </ul>
    
    <div class="rss_lenta"><a href='http://www.numizmatik.ru/shopcoins/rss.xml' target='_blank' title='Новые поступления монет,банкнот,аксессуаров - канал RSS' class=topmenu>Подписаться на RSS</a></div>
</div>
  <? if($tpl['is_mobile']){?>
<style type="text/css">
 #cssmenu ul li a:hover {
  background: none;  
}
 #cssmenu ul li a.active_m{
  background: #ffcc66;  
}
</style>  
<script>
$(document).ready(function(){  
  $('#ddmenu li.top li').click(function(event) {
      event.stopPropagation();
  }); 

  $('#ddmenu li.top').click(function(event) {
        var btn = $('div.submenu',this);
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
    });  
});
</script>
 <? } else {?>
 <script>   
$(document).ready(function(){ 
    var cur_href =  $('a',this).filter( ':first' );
    $('#ddmenu li').hover(function () {
     clearTimeout($.data(this,'timer'));
     $('div.submenu',this).stop(true,true).slideDown(200);
    }, function () {
    $.data(this,'timer', setTimeout($.proxy(function() {
      $('div.submenu',this).stop(true,true).slideUp(200);
    }, this), 100));
    });   
});
</script>
 <?}?>