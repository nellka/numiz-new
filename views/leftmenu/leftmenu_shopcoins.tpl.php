<?/*if(isset($mycoins)&&$mycoins) {?>
   <div class="menu-heading"  id='hidden-shopcoins-menu'></div> 
<?} else {*/?>
<div class="menu-heading"  id='hidden-shopcoins-menu'>
	<a style="color:#ffffff;text-decoration:none;" href="#s" >
		<span style="padding-left:17px;" id='hidden-shopcoins-span'>Разделы магазина</span>
	</a>

<ul id='left_menu_shop' class="menu-sidebar_static" style="display:<?=$mini?'none':'block'?>" >
   <li><?=($tpl['user']['user_id']==811)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/moneti/?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$materialtype==1)?" - ":" + ").")</font></a>":""?>
   <a href='<?=$cfg['site_dir']?>shopcoins/moneti' title='Монеты стоимость(цены) весь мир' class="topmenu <?=(isset($materialtype)&&$materialtype==1)?'active':''?>">Монеты</a>   </li>
   <li>
   <?=($tpl['user']['user_id']==811)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/nabori_monet/?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$materialtype==7)?" - ":" + ").")</font></a>":""?>
   <a href='<?=$cfg['site_dir']?>shopcoins/nabori_monet' title='Наборы монет стоимость(цены) весь мир' class="topmenu <?=(isset($materialtype)&&$materialtype==7)?'active':''?>">Наборы монет</a></li>
   <li>
   <?=($tpl['user']['user_id']==811)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/meloch/?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$materialtype==8)?" - ":" + ").")</font></a>":""?>
   <a href='<?=$cfg['site_dir']?>shopcoins/meloch' title='Дешевые монеты стоимость(цены) весь мир' class="topmenu <?=(isset($materialtype)&&$materialtype==8)?'active':''?>">Мелочь </a></li>
   <li>
   <?=($tpl['user']['user_id']==811)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/cvetnie_moneti/?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$materialtype==6)?" - ":" + ").")</font></a>":""?>
   <a href='<?=$cfg['site_dir']?>shopcoins/cvetnie_moneti' title='Цветные монеты' class="topmenu <?=(isset($materialtype)&&$materialtype==6)?'active':''?>">Цветные монеты </a></li>
   <li>
   <?=($tpl['user']['user_id']==811)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/banknoti/?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$materialtype==2)?" - ":" + ").")</font></a>":""?><a href='<?=$cfg['site_dir']?>shopcoins/banknoti' title='Банкноты стоимость(цены) весь мир' class="topmenu <?=(isset($materialtype)&&$materialtype==2)?'active':''?>">Банкноты</a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/aksessuary' title='Аксессуары для коллекционеров цены' class="topmenu <?=(isset($materialtype)&&$materialtype==3)?'active':''?>">Аксессуары для монет</a></li>
   <li><?=($tpl['user']['user_id']==811)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/podarochnye_nabory/?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$materialtype==7)?" - ":" + ").")</font></a>":""?>
   <a href='<?=$cfg['site_dir']?>shopcoins/podarochnye_nabory' title='Подарочные наборы монет  стоимость(цены) весь мир' class="topmenu <?=(isset($materialtype)&&$materialtype==4)?'active':''?>">Подарочные наборы</a></li>
   <li><?=($tpl['user']['user_id']==811)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/baraholka/?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$materialtype==11)?" - ":" + ").")</font></a>":""?>
   <a href='<?=$cfg['site_dir']?>shopcoins/baraholka' title='Барахолка' class="topmenu <?=(isset($materialtype)&&$materialtype==11)?'active':''?>">Барахолка</a></li>
    <li>
    <?=($tpl['user']['user_id']==811)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/loty_dlya_nachinayushchih/?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$materialtype==9)?" - ":" + ").")</font></a>":""?>
    <a href='<?=$cfg['site_dir']?>shopcoins/loty_dlya_nachinayushchih' title='Лоты монет для начинающих нумизматов' class="topmenu <?=(isset($materialtype)&&$materialtype==9)?'active':''?>">Лоты монет для начинающих нумизматов</a></li>
   <li>
   <?=($tpl['user']['user_id']==811)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/notgeldy/?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$materialtype==10)?" - ":" + ").")</font></a>":""?>
   <a href='<?=$cfg['site_dir']?>shopcoins/notgeldy' title='Нотгельды стоимость(цены) весь мир' class="topmenu <?=(isset($materialtype)&&$materialtype==10)?'active':''?>">Нотгельды </a></li>
   <li>
   <?=($tpl['user']['user_id']==811)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/moneti_sssr/?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$materialtype==12)?" - ":" + ").")</font></a>":""?>
   <a href='<?=$cfg['site_dir']?>shopcoins/moneti_sssr' title='Дешевые монеты СССР цены и стоимость покупки и продажи смотреть' class="topmenu <?=(isset($materialtype)&&$materialtype==12)?'active':''?>"> Монеты СССР</a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/knigi' title='Книги по нумизматике стоимость(цены) смотреть' class="topmenu <?=(isset($materialtype)&&$materialtype==5)?'active':''?>">Книги по нумизматике</a>
   <li>
   <?=($tpl['user']['user_id']==811)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/revaluation?nocheck=1' style='float:left' class='pls'><font color=black>(".(($nocheck&&$materialtype=='revaluation')?" - ":" + ").")</font></a>":""?>
   <a href='<?=$cfg['site_dir']?>shopcoins/revaluation' title='Распродажа монет' class="topmenu <?=($materialtype=='revaluation')?'active':''?>">Распродажа монет</a></li>        
   <li><?=($tpl['user']['user_id']==811)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/newcoins?nocheck=1' class='pls left'><font color=black>(".(($nocheck&&$materialtype=='newcoins')?" - ":" + ").")</font></a>":""?>
   <a href='<?=$cfg['site_dir']?>shopcoins/newcoins' title='Новинки <?=(date('Y',time())-1)?>-<?=date('Y',time())?>' class="topmenu <?=($materialtype=='newcoins')?'active':''?>">Новинки <?=(date('Y',time())-1)?>-<?=date('Y',time())?></a></li>
   
   <li><a href='<?=$cfg['site_dir']?>shopcoins/series' title='Серии монет'>Серии монет</a></li>     
             
 </ul>   
  </div>
    <? include('stat_shopcoins.tpl.php');
   //подключаем фильтры для магазина
    if($tpl['task']=='catalog_base')  include('filter_country.tpl.php');
?>

   <script> 
   $('#hidden-shopcoins-menu').click(function (e) {
         if ($(e.target).prop('id') == "hidden-shopcoins-menu"||$(e.target).prop('id') == "hidden-shopcoins-span") {
            showMainLeftMenu('left_menu_shop');return false;
        }
    });
     $('#hidden-shopcoins-stat').click(function (e) {           
         if ($(e.target).prop('id') == "hidden-shopcoins-stat") {    
            showMainLeftMenu('stat_shop');return false;
         }
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
<?//}?>