<div class="menu-heading"  id='hidden-shopcoins-menu'>
	<a style="color:#ffffff;text-decoration:none;" href="#s" >
		<span style="padding-left:17px;" id='hidden-shopcoins-span'>Разделы магазина</span>
	</a>

<ul id='left_menu_shop' class="menu-sidebar_static" style="display:<?=$mini?'none':'block'?>" itemscope itemtype="http://schema.org/SiteNavigationElement">
   <li><?=($tpl['user']['user_id']==811)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/moneti/?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$materialtype==1)?" - ":" + ").")</font></a>":""?>
   <?=contentHelper::returnSeoHref($cfg['site_dir']."shopcoins","Монеты",array("title"=>"Монеты стоимость(цены) весь мир", "class"=>"topmenu"))?></li>
   <li>
   <?=($tpl['user']['user_id']==811)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/nabori_monet/?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$materialtype==7)?" - ":" + ").")</font></a>":""?>
   <?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopcoins/nabori_monet","Наборы монет",array('title'=>'Наборы монет стоимость(цены) весь мир', "class"=>"topmenu"))?>
   </li>
   <li>
   <?=($tpl['user']['user_id']==811)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/meloch/?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$materialtype==8)?" - ":" + ").")</font></a>":""?>
   <?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopcoins/meloch","Мелочь",array('title'=>'Дешевые монеты стоимость(цены) весь мир', "class"=>"topmenu"))?>
   </li>
   <li>
   <?=($tpl['user']['user_id']==811)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/cvetnie_moneti/?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$materialtype==6)?" - ":" + ").")</font></a>":""?>
   <?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopcoins/cvetnie_moneti","Цветные монеты",array('title'=>'Цветные монеты', "class"=>"topmenu"))?>
   </li>
   <li>
   <?=($tpl['user']['user_id']==811)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/banknoti/?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$materialtype==2)?" - ":" + ").")</font></a>":""?>
   <?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopcoins/banknoti","Банкноты",array('title'=>'Банкноты стоимость(цены) весь мир', "class"=>"topmenu"))?>
   </li>
   <li>
   <?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopcoins/aksessuary","Аксессуары для монет",array('title'=>'Аксессуары для коллекционеров цены', "class"=>"topmenu"))?>
   </li>
   <li>
   <?=($tpl['user']['user_id']==811)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/podarochnye_nabory/?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$materialtype==7)?" - ":" + ").")</font></a>":""?>
   <?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopcoins/podarochnye_nabory","Подарочные наборы",array('title'=>'Подарочные наборы монет  стоимость(цены) весь мир', "class"=>"topmenu"))?>
   </li>
   <li><?=($tpl['user']['user_id']==811)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/baraholka/?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$materialtype==11)?" - ":" + ").")</font></a>":""?>
   <?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopcoins/baraholka","Барахолка",array('title'=>'Барахолка', "class"=>"topmenu"))?>
   </li>
   <li>
    <?=($tpl['user']['user_id']==811)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/loty_dlya_nachinayushchih/?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$materialtype==9)?" - ":" + ").")</font></a>":""?>
    <?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopcoins/loty_dlya_nachinayushchih","Лоты монет для начинающих",array('title'=>'Лоты монет для начинающих нумизматов', "class"=>"topmenu"))?>
   </li>
   <li>
   <?=($tpl['user']['user_id']==811)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/notgeldy/?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$materialtype==10)?" - ":" + ").")</font></a>":""?>
   <?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopcoins/notgeldy","Нотгельды",array('title'=>'Нотгельды стоимость(цены) весь мир', "class"=>"topmenu"))?>
   </li>
   <li>
   <?=($tpl['user']['user_id']==811)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/moneti_sssr/?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$materialtype==12)?" - ":" + ").")</font></a>":""?>
   <?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopcoins/moneti_sssr","Монеты СССР",array('title'=>'Дешевые монеты СССР цены и стоимость покупки и продажи смотреть', "class"=>"topmenu"))?>
   
   </li>
   <li>
   <?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopcoins/knigi","Книги по нумизматике",array('title'=>'Книги по нумизматике стоимость(цены) смотреть', "class"=>"topmenu"))?>
   </li>
   <li>
   <?=($tpl['user']['user_id']==811)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/revaluation?nocheck=1' style='float:left' class='pls'><font color=black>(".(($nocheck&&$materialtype=='revaluation')?" - ":" + ").")</font></a>":""?>
   <?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopcoins/revaluation","Распродажа монет",array('title'=>'Распродажа монет', "class"=>"topmenu"))?>
   </li>        
   <li>
   <?=($tpl['user']['user_id']==811)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/newcoins?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$materialtype=='newcoins')?" - ":" + ").")</font></a>":""?>
   <?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopcoins/newcoins","Новинки монет ".(date('Y',time())-2)."-".date('Y',time()),array('title'=>'Новинки монет', "class"=>"topmenu"))?>
   </li>
   
   <li><?=contentHelper::returnSeoHref("http://www.numizmatik.ru/shopcoins/series","Серии монет",array('title'=>'Серии монет', "class"=>"topmenu"))?>
   </li>     
             
 </ul>   
  </div>

  <script> 
      
	$('#hidden-shopcoins-menu').click(function (e) {
	     if ($(e.target).prop('id') == "hidden-shopcoins-menu"||$(e.target).prop('id') == "hidden-shopcoins-span") {
	        showMainLeftMenu('left_menu_shop');return false;
	    }
	});
	 $('.hidden-shopcoins-stat').click(function (e) {           
	     //if ($(e.target).prop('id') == "hidden-shopcoins-stat") {    
	        showMainLeftMenu('stat_shop');return false;
	     //}
	});
	
	$('#hidden-by-date-filter').click(function (e) {
	     if ($(e.target).prop('id') == "hidden-by-date-filter"||$(e.target).prop('id') == "hidden-by-date-filter-span") {
	        showMainLeftMenu('fb-bydate');return false;
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
