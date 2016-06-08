<div class="menu-heading"  id='hidden-shopcoins-menu'>
	<a style="color:#ffffff;text-decoration:none;" href="#s" >
		<span style="padding-left:17px;" id='hidden-shopcoins-span'>Разделы магазина</span>
	</a>

<ul id='left_menu_shop' class="menu-sidebar_static" style="display:'none'" >
   <li><?=$tpl['user']['user_id']==811?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/moneti?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$materialtype==1)?" - ":" + ").")</font></a>":""?>
   <a href='<?=$cfg['site_dir']?>shopcoins/moneti' title='Монеты стоимость(цены) весь мир' >Монеты</a>   </li>
   <li>
   <?=$tpl['user']['user_id']==811?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/nabori_monet?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$materialtype==7)?" - ":" + ").")</font></a>":""?>
   <a href='<?=$cfg['site_dir']?>shopcoins/nabori_monet' title='Наборы монет стоимость(цены) весь мир'>Наборы монет</a></li>
   <li>
   <?=$tpl['user']['user_id']==811?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/meloch?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$materialtype==8)?" - ":" + ").")</font></a>":""?>
   <a href='<?=$cfg['site_dir']?>shopcoins/meloch' title='Дешевые монеты стоимость(цены) весь мир'>Мелочь </a></li>
   <li>
   <?=$tpl['user']['user_id']==811?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/cvetnie_moneti?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$materialtype==6)?" - ":" + ").")</font></a>":""?>
   <a href='<?=$cfg['site_dir']?>shopcoins/cvetnie_moneti' title='Цветные монеты'>Цветные монеты </a></li>
   <li>
   <?=$tpl['user']['user_id']==811?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/banknoti?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$materialtype==2)?" - ":" + ").")</font></a>":""?>
   <a href='<?=$cfg['site_dir']?>shopcoins/banknoti' title='Банкноты стоимость(цены) весь мир'>Банкноты</a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/aksessuary' title='Аксессуары для коллекционеров цены'>Аксессуары для монет</a></li>
   <li><?=$tpl['user']['user_id']==811?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/podarochnye_nabory?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$materialtype==7)?" - ":" + ").")</font></a>":""?>
   <a href='<?=$cfg['site_dir']?>shopcoins/podarochnye_nabory' title='Подарочные наборы монет  стоимость(цены) весь мир'>Подарочные наборы</a></li>
   <li><?=$tpl['user']['user_id']==811?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/baraholka?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$materialtype==11)?" - ":" + ").")</font></a>":""?>
   <a href='<?=$cfg['site_dir']?>shopcoins/baraholka' title='Барахолка' >Барахолка</a></li>
    <li>
    <?=$tpl['user']['user_id']==811?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/loty_dlya_nachinayushchih?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$materialtype==9)?" - ":" + ").")</font></a>":""?>
    <a href='<?=$cfg['site_dir']?>shopcoins/loty_dlya_nachinayushchih' title='Лоты монет для начинающих нумизматов'>Лоты монет для начинающих нумизматов</a></li>
   <li>
   <?=$tpl['user']['user_id']==811?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/notgeldy?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$materialtype==10)?" - ":" + ").")</font></a>":""?>
   <a href='<?=$cfg['site_dir']?>shopcoins/notgeldy' title='Нотгельды стоимость(цены) весь мир'>Нотгельды </a></li>
   <li>
   <?=$tpl['user']['user_id']==811?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/moneti_sssr?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$materialtype==12)?" - ":" + ").")</font></a>":""?>
   <a href='<?=$cfg['site_dir']?>shopcoins/moneti_sssr' title='Дешевые монеты СССР цены и стоимость покупки и продажи смотреть'> Монеты СССР</a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/knigi' title='Книги по нумизматике стоимость(цены) смотреть'>Книги по нумизматике</a>
   <li>
   <?=$tpl['user']['user_id']==811?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/index.php?search=revaluation?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$search=='revaluation')?" - ":" + ").")</font></a>":""?>
   <a href='<?=$cfg['site_dir']?>shopcoins/index.php?search=revaluation' title='Распродажа монет' class="topmenu <?=(isset($search)&&$search=='revaluation')?'active':''?>">Распродажа монет</a></li>        
   <li><?=$tpl['user']['user_id']==811?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/index.php?search=newcoins?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$search=='newcoins')?" - ":" + ").")</font></a>":""?>
   <a href='<?=$cfg['site_dir']?>shopcoins/index.php?search=newcoins' title='Новинки <?=(date('Y',time())-1)?>-<?=date('Y',time())?>' class="topmenu <?=(isset($search)&&$search=='newcoins')?'active':''?>">Новинки <?=(date('Y',time())-1)?>-<?=date('Y',time())?></a></li>
  <li><a href='<?=$cfg['site_dir']?>shopcoins/series' title='Серии монет'>Серии монет</a></li>   
 </ul>   
  </div> 
   <?  
   //подключаем фильтры для магазина
   //if($tpl['task']=='catalog_base')  include('filters.tpl.php');
?>

   <script> 
   
   $('#hidden-shopcoins-menu').click(function (e) {
         if ($(e.target).prop('id') == "hidden-shopcoins-menu"||$(e.target).prop('id') == "hidden-shopcoins-span") {
            //$(this).hide();
            showMainLeftMenu();return false;
        }
    });
    
   function showMainLeftMenu(){
        
        if(!$('#left_menu_shop').is(':visible')){
            $('#left_menu_shop').show();
        } else {
            $('#left_menu_shop').hide();
        }
        return false;
   }
   
   /* $('#hidden-shopcoins-menu').hover(
		function(){	 
		  $('#left_menu_shop').show();
		},
		function(){
		   $('#left_menu_shop').hide();
		}
	);	*/
    </script>
