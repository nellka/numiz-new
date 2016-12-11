<?
if($tpl['user']['user_id']==352480){ 
	//var_dump($tpl['module'],$_SERVER['REQUEST_URI']);
	}?>

<div id="cssmenu" class="wraper" itemscope itemtype="http://schema.org/SiteNavigationElement">
    <ul id="ddmenu">  
       <li class="home"><?if($_SERVER['REQUEST_URI']!='/'){?><a href="http://www.numizmatik.ru/"></a><?}?></li>
       <li class="top"><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopcoins","Магазин монет",array("onmouseover"=>"showMenuDescription(0);",'class'=>in_array($tpl['module'],array('shopcoins','order'))?'active':''))?>      
       <div id="menu3"  class="submenu">   
            <ul>
            <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopcoins","Монеты",array("onmouseover"=>"showMenuDescription(1);","title"=>"Монеты стоимость(цены) весь мир", "class"=>"topmenu"))?></li>
            <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopcoins/meloch","Мелочь",array("onmouseover"=>"showMenuDescription(8);",'title'=>'Дешевые монеты стоимость(цены) весь мир', "class"=>"topmenu"))?></li>
            <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopcoins/notgeldy","Нотгельды",array("onmouseover"=>"showMenuDescription(10);",'title'=>'Нотгельды стоимость(цены) весь мир', "class"=>"topmenu"))?></li>
            <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopcoins/nabori_monet","Наборы монет",array("onmouseover"=>"showMenuDescription(7);",'title'=>'Наборы монет стоимость(цены) весь мир', "class"=>"topmenu"))?></li>
            <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopcoins/loty_dlya_nachinayushchih","Лоты монет для начинающих нумизматов",array("onmouseover"=>"showMenuDescription(9);",'title'=>'Лоты монет для начинающих нумизматов', "class"=>"topmenu"))?></li>
            <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopcoins/newcoins","Новинки монет ".(date('Y',time())-2)."-".date('Y',time()),array("onmouseover"=>"showMenuDescription('newcoins');",'title'=>'Новинки монет', "class"=>"topmenu"))?></li>
            <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopcoins/banknoti","Банкноты",array("onmouseover"=>"showMenuDescription(2);",'title'=>'Банкноты стоимость(цены) весь мир', "class"=>"topmenu"))?></li>
            <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopcoins/aksessuary","Аксессуары для монет",array("onmouseover"=>"showMenuDescription(3);",'title'=>'Аксессуары для коллекционеров цены', "class"=>"topmenu"))?></li>
            <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopcoins/podarochnye_nabory","Подарочные наборы",array("onmouseover"=>"showMenuDescription(4);",'title'=>'Подарочные наборы монет  стоимость(цены) весь мир', "class"=>"topmenu"))?></li>
            <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopcoins/knigi","Книги о монетах",array("onmouseover"=>"showMenuDescription(5);",'title'=>'Книги о нумизматике бонистике цены', "class"=>"topmenu"))?></li>
            <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopcoins/revaluation","Распродажа монет",array("onmouseover"=>"showMenuDescription('revaluation');",'title'=>'Распродажа монет', "class"=>"topmenu"))?></li>    
            <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopcoins/baraholka","Барахолка",array("onmouseover"=>"showMenuDescription(11);",'title'=>'Барахолка', "class"=>"topmenu"))?></li>     
             <? if($tpl['user']['user_id']){?>
             <li><a href='http://www.numizmatik.ru/shopcoins/mycoins.php' onmouseover="showMenuDescription('0');" title='Монеты из ваших заказов в интернет-магазине монет' class="topmenu"><span class="error">Монеты из ваших заказов</span></a></li> 
             <?}?> 
             <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopcoins/series","Серии монет",array("onmouseover"=>"showMenuDescription('0');",'title'=>'Серии монет', "class"=>"topmenu"))?></li>     
              
            </ul>          
           <div class="menuDescription" id='menudiscription-m0'>          
           <p>
            Клуб "Нумизмат" специализируется на продаже, покупке и оценке монет, в том числе из драгоценных металлов.В наших салонах продаж всегда в наличии большое количество разнообразных монет разных стран и городов. Так же мы предоставляем такую услугу как оценка и покупка ваших монет. Наши специалисты имеют огромный навык в направлении "нумизматика", что помогает в короткое время оценить стоимость монет, благодаря чему вы с лёгкостью сможете продать ненужные вам монеты.  </p>
           </div>
           <?foreach ($tpl['topmenu'] as $mid=>$menuItems){?>
           <div class="menuDescription" id='menudiscription-<?=$mid?>' style="display:none">          
           <? 
          
          foreach ($menuItems as $titem){
           		echo "<div class='itemmenu'  itemscope itemtype=\"http://schema.org/Product\">";           		
           		echo contentHelper::render('shopcoins/items/itemMenu',$titem);
           		echo "</div>";
           		
           }?>
           </div>
           <?}?>           
       </div>
       </li>       	
       <li class="top"><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopcoinshelp.php","Покупателям",array('title'=>'', "class"=>in_array($tpl['module'],array('garantii-podlinosti-monet','delivery','how','shopcoinsrules','shopcoinshelp','panorama'))?'active':''))?>
          <div id="menu4"  class="submenu">          
            <ul>                
                <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopcoins/delivery.php","Оплата и доставка",array('title'=>'Способы оплаты и доставки монет, банкнот, аксессуаров, книг по нумизматике',"class"=>"topmenu"))?></li>
                <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopcoins/pokupka_monet_v_magazine.html","Как заказать (Видео <img src='http://www.numizmatik.ru/images/video.gif' title='Как заказать' alt='Как заказать'>)",array('title'=>'Как сделать заказ в магазине',"class"=>"topmenu"))?></li>
                <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopcoins/shopcoinsrules.php","Правила магазина",array('title'=>'Правила монетной лавки',"class"=>"topmenu"))?> </li>
                <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopcoins/shopcoinshelp.php","ЧаВо по магазину",array('title'=>'Помощник по магазину',"class"=>"topmenu"))?></li>
                 <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/garantii-podlinosti-monet","Гарантии подлиности монет",array('title'=>'Гарантии подлинности монет и банкнот от Клуба Нумизмат',"class"=>"topmenu"))?></li> 
                <? if($tpl['user']['user_id']){?>                
	                <li><a href='http://www.numizmatik.ru/shopcoins/order.php' title='Ваши заказы монет, банкнот, аксессуаров для коллекционеров в интернет-магазине монет' class=topmenu>Ваши заказы</a></li>
	                
	             	<li><a href='http://www.numizmatik.ru/shopcoins/mycoins.php' onmouseover="showMenuDescription('0');" title='Монеты из ваших заказов в интернет-магазине монет' class="topmenu"><span class="error">Монеты из ваших заказов</span></a></li> 
                <?}?>   
                <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopinfo.php","Контакты",array('title'=>'Салон продаж',"class"=>"topmenu"))?></li>
				<li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/panorama.php","Панорама нашего магазина",array('title'=>'Панорама нашего магазина',"class"=>"topmenu"))?></li>
                </ul>
           </div> 
       </li>            
      <li class="top"><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/ocenka-stoimost-monet","Скупка-оценка",array('title'=>'Способы оплаты и доставки монет, банкнот, аксессуаров, книг по нумизматике',"class"=>in_array($tpl['module'],array('ocenka-stoimost-monet','gde-prodat-monety'))?'active':''))?>
       <div id="menu5"  class="submenu">          
            <ul>               
                <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/ocenka-stoimost-monet","Оценка стоимости монет",array('title'=>'Оценка стоимости(цены) монет(ы)',"class"=>"topmenu"))?></li>
                <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/gde-prodat-monety","Покупка/скупка монет",array('title'=>'Покупка-скупка монет, коллекций монет. ',"class"=>"topmenu"))?></li>
                <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/change/obmen-monet.php","Обмен монетами",array('title'=>'Обмен монетами',"class"=>"topmenu"))?></li>
                <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopinfo.php","Контакты",array('title'=>'Салон продаж',"class"=>"topmenu"))?></li>
               </ul> 
           </div>      
      </li>
      <li class="top"><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/catalognew","Каталог",array('title'=>'Каталог монет России, Германии, США и других стран','class'=>in_array($tpl['module'],array('catalognew'))?'active':''))?>
       <div id="menu6"  class="submenu">          
            <ul>          
                <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/catalognew","Каталог монет",array('title'=>'Каталог монет России, Германии, США и других стран',"class"=>"topmenu"))?></li>
                <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/catalognew/nabori_monet","Наборы монет",array('title'=>'Каталог наборов монет весь мир',"class"=>"topmenu"))?></li>
                <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/catalognew/banknoti","Банкноты",array('title'=>'Каталог банкнот весь мир',"class"=>"topmenu"))?></li>
                <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/catalognew/podarochnye_nabory","Подарочные наборы",array('title'=>'Каталог подарочных наборов монет весь мир',"class"=>"topmenu"))?></li>
                <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/catalognew/aksessuary","Аксессуары",array('title'=>'Каталог аксессуаров для коллекционеров',"class"=>"topmenu"))?></li>
                <li><a href='http://www.numizmatik.ru/program/' title='Скачать программное обеспечение для нумизмата совершенно бесплатно' class=topmenu>Программа нумизмата</a></li>
                <li><a href='http://www.numizmatik.ru/programandroid/' title='Скачать нумизматическое приложение для смартфона (планшета) бесплатно' class=topmenu>Приложения для смартфона</a></li>
                </ul>
            </div>      
      </li>
   <li class="top"><a href="http://www.numizmatik.ru/ocenka-stoimost-monet"><span>Полезное</span></a>
      <div id="menu10"  class="submenu">          
            <ul>         
             <li><a href='http://www.numizmatik.ru/biblio/' title='Нумизматическая библиотека - интересные для любого коллекционера статьи, обзоры и аналитические материалы' class=topmenu>Библиотека нумизмата</a> </li>
             <li><a href='http://www.numizmatik.ru/collector/' title='Клуб Нумизмат Найдите коллекционеров в своем городе, узнайте кто еще интересуется монетами на вашу тематику' class=topmenu>Клуб Нумизмат</a> </li>
             <li><a href='http://www.numizmatik.ru/advertise/' title='Объявления от коллекционеров по покупке/продаже/обмену монет' class=topmenu>Объявления пользователей</a> </li>
             <li><a href='http://www.numizmatik.ru/buycoins/' title='Нужна редкая монета, которую долго не можете найти? Оставьте у нас свою заявку.' class=topmenu>Нужны монеты</a> </li>
             <li><a href='http://www.numizmatik.ru/pricecoins/' title='Динамический ценник на монеты СССР и России' class=topmenu>Ценник на монеты</a> </li>
             <li><a href='http://www.numizmatik.ru/price/' title='Цены на монеты' class=topmenu>Стоимость монет</a> </li>
             <li><a href='http://www.numizmatik.ru/chat/' title='Нумизматический чат' class=topmenu>Нумизматический чат</a> </li>
             <li><a href='http://www.numizmatik.ru/classificator/' title='Распознавание монет' class=topmenu>Распознавание монет</a> </li>
             <li><a href='http://www.numizmatik.ru/russiancoins/' title='Юбилейные монеты России' class=topmenu>Юбилейные монеты</a> </li>
             <li><a href='http://www.numizmatik.ru/moneti' title='Из истории происхождения монет' class=topmenu>Монета, история</a> </li>
             <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/subscribe","Подписка",array('title'=>'Подписка на поступления монет, банкнот, аксессуаров, новости нумизматики, форум нумизматов',"class"=>"topmenu"))?></li>
             <li><a href='http://www.numizmatik.ru/game/' title='Игровой раздел Клуба Нумизмат' class=topmenu>Игровой раздел</a> </li>
             </ul>
      	</div>
      </li>
      <li class="top"><a href="http://www.numizmatik.ru/forum/"><span>Форумы</span></a>      
      <div id="menu8"  class="submenu">          
            <ul>          
                <li><a href='http://www.numizmatik.ru/forum/' title='Нумизматический форум, место встречи коллекционеров всей страны' class=topmenu>Форум нумизматов</a></li>
                <li><a href='http://tboard.numizmatik.ru' title='Архив старого Нумизматического форума' class=topmenu>Архив форума</a></li>
                <li><a href='http://www.numizmatik.ru/blacklist' title='Клуб Нумизмат рекомендует быть осторожным Опасайтесь мошенников' class=topmenu>Черный список</a></li>
                <li><a href='http://www.numizmatik.ru/detector' title='Форум кладоискателей' class=topmenu>Форум кладоискателей</a></li>
            </ul>
        </div>        
      </li>
      <li class="top"><a href="#" class="<?=in_array($tpl['module'],array('news','video'))?'active':''?>"><span>Общая информация</span></a>
       <div id="menu9"  class="submenu">          
          <ul>          
          <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/news","Новости нумизматики",array('title'=>'Последние новости нумизматики',"class"=>"topmenu"))?></li>
          <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/video.php","Видео для нумизматов",array('title'=>'Видео для нумизматов',"class"=>"topmenu"))?></li>
          <li><a href='http://www.numizmatik.ru/dlya-dilerov' title='Для нумизматических дилеров' class=topmenu>Для нумизматических дилеров</a></li>
          <li><a href='http://www.numizmatik.ru/change/obmen-monet.php' title='Обмен монет' class=topmenu>Обмен монетами</a></li>
          <li><a href='http://www.numizmatik.ru/press/' title='Для прессы' class=topmenu>Для прессы</a></li>
          <li><a href='http://www.numizmatik.ru/advice/' title='Ваши советы' class=topmenu>Ваши советы</a></li>
          <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopinfo.php","Контакты",array('title'=>'Салон продаж',"class"=>"topmenu"))?></li>      
          </ul>
       </div>
     </li>
     <li class="top"><a href="http://www.numizmatik.ru/auction"><span>Аукцион</span></a>
      <div id="menu7"  class="submenu">          
            <ul>          
                <li><a href='http://www.numizmatik.ru/auction' title='Аукцион монет России, Германии, США и других стран' class=topmenu>Аукцион монет</a></li>
                <li><a href='http://www.numizmatik.ru/auction/about.php' title='Правила аукциона ионет' class=topmenu>Правила аукциона</a></li>
            </ul>
         </div>
      </li>      
     
      
       <li class="top"><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopinfo.php","Салон продаж",array('title'=>'Салон продаж','class'=>in_array($tpl['module'],array('about'))?'active':''))?>
            <div id="menu11"  class="submenu">          
            <ul>          
                <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopinfo.php","Салон продаж",array('title'=>'Салон продаж',"class"=>"topmenu"))?></li> 
                <li><a title="Почему нам доверяют и покупают у нас монеты и банкноты?" href="http://www.numizmatik.ru/presentation.pdf">О Клубе Нумизмат</a>
                <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/about.php","Наш суперский коллектив",array('title'=>'Наш суперский коллектив',"class"=>"topmenu"))?></li>      
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