<div class="menu-heading"  id='hidden-shopcoins-menu'>
	<a style="color:#ffffff;text-decoration:none;" href="#s" >
		<span style="padding-left:17px;" id='hidden-shopcoins-span'>Разделы магазина</span>
	</a>

<ul id='left_menu_shop' class="menu-sidebar_static" style="display:<?=$mini?'none':'block'?>" >
   <li><?=($tpl['user']['user_id']==811&$materialtype!=3&&$materialtype!=5)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/index.php?materialtype=1&nocheck=1' style='float:left' class='pls'><font color=black>(".(($nocheck&&$materialtype==1)?" - ":" + ").")</font></a>":""?>
   <a href='<?=$cfg['site_dir']?>shopcoins/index.php?materialtype=1' title='Монеты стоимость(цены) весь мир' class="topmenu <?=(isset($materialtype)&&!$search&&$materialtype==1)?'active':''?>">Монеты</a>   </li>
   <li>
   <?=($tpl['user']['user_id']==811&$materialtype!=3&&$materialtype!=5)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/index.php?materialtype=7&nocheck=1' style='float:left' class='pls'><font color=black>(".(($nocheck&&$materialtype==7)?" - ":" + ").")</font></a>":""?>
   <a href='<?=$cfg['site_dir']?>shopcoins/index.php?materialtype=7' title='Наборы монет стоимость(цены) весь мир' class="topmenu <?=(isset($materialtype)&&$materialtype==7)?'active':''?>">Наборы монет</a></li>
   <li>
   <?=($tpl['user']['user_id']==811&$materialtype!=3&&$materialtype!=5)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/index.php?materialtype=8&nocheck=1' style='float:left' class='pls'><font color=black>(".(($nocheck&&$materialtype==8)?" - ":" + ").")</font></a>":""?>
   <a href='<?=$cfg['site_dir']?>shopcoins/index.php?materialtype=8' title='Дешевые монеты стоимость(цены) весь мир' class="topmenu <?=(isset($materialtype)&&$materialtype==8)?'active':''?>">Мелочь </a></li>
   <li>
   <?=($tpl['user']['user_id']==811&$materialtype!=3&&$materialtype!=5)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/index.php?materialtype=6&nocheck=1' style='float:left' class='pls'><font color=black>(".(($nocheck&&$materialtype==6)?" - ":" + ").")</font></a>":""?>
   <a href='<?=$cfg['site_dir']?>shopcoins/index.php?materialtype=6' title='Цветные монеты' class="topmenu <?=(isset($materialtype)&&$materialtype==6)?'active':''?>">Цветные монеты </a></li>
   <li>
   <?=($tpl['user']['user_id']==811&$materialtype!=3&&$materialtype!=5)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/index.php?materialtype=2&nocheck=1' style='float:left' class='pls'><font color=black>(".(($nocheck&&$materialtype==2)?" - ":" + ").")</font></a>":""?>
   <a href='<?=$cfg['site_dir']?>shopcoins/index.php?materialtype=2' title='Банкноты стоимость(цены) весь мир' class="topmenu <?=(isset($materialtype)&&$materialtype==2)?'active':''?>">Банкноты</a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/?materialtype=4' title='Аксессуары для коллекционеров цены' class="topmenu <?=(isset($materialtype)&&$materialtype==3)?'active':''?>">Аксессуары для монет</a></li>
   <li><?=($tpl['user']['user_id']==811&$materialtype!=3&&$materialtype!=5)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/index.php?materialtype=7&nocheck=1' style='float:left' class='pls'><font color=black>(".(($nocheck&&$materialtype==7)?" - ":" + ").")</font></a>":""?>
   <a href='<?=$cfg['site_dir']?>shopcoins/?materialtype=4' title='Подарочные наборы монет  стоимость(цены) весь мир' class=topmenu>Подарочные наборы</a></li>
   <li><?=($tpl['user']['user_id']==811&$materialtype!=3&&$materialtype!=5)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/index.php?materialtype=11&nocheck=1' style='float:left' class='pls'><font color=black>(".(($nocheck&&$materialtype==11)?" - ":" + ").")</font></a>":""?>
   <a href='<?=$cfg['site_dir']?>shopcoins/index.php?materialtype=11' title='Барахолка' class="topmenu <?=(isset($materialtype)&&!$search&&$materialtype==11)?'active':''?>">Барахолка</a></li>
    <li>
    <?=($tpl['user']['user_id']==811&$materialtype!=3&&$materialtype!=5)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/index.php?materialtype=9&nocheck=1' style='float:left' class='pls'><font color=black>(".(($nocheck&&$materialtype==9)?" - ":" + ").")</font></a>":""?>
    <a href='<?=$cfg['site_dir']?>shopcoins/index.php?materialtype=9' title='Лоты монет для начинающих нумизматов' class="topmenu <?=(isset($materialtype)&&$materialtype==9)?'active':''?>">Лоты монет для начинающих нумизматов</a></li>
   <li>
   <?=($tpl['user']['user_id']==811&$materialtype!=3&&$materialtype!=5)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/index.php?materialtype=10&nocheck=1' style='float:left' class='pls'><font color=black>(".(($nocheck&&$materialtype==10)?" - ":" + ").")</font></a>":""?>
   <a href='<?=$cfg['site_dir']?>shopcoins/index.php?materialtype=10' title='Нотгельды стоимость(цены) весь мир' class="topmenu <?=(isset($materialtype)&&$materialtype==10)?'active':''?>">Нотгельды </a></li>
   <li>
   <?=($tpl['user']['user_id']==811&$materialtype!=3&&$materialtype!=5)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/index.php?materialtype=12&nocheck=1' style='float:left' class='pls'><font color=black>(".(($nocheck&&$materialtype==12)?" - ":" + ").")</font></a>":""?>
   <a href='<?=$cfg['site_dir']?>shopcoins/index.php?materialtype=12' title='Дешевые монеты СССР цены и стоимость покупки и продажи смотреть' class="topmenu <?=(isset($materialtype)&&$materialtype==12)?'active':''?>"> Монеты СССР</a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/index.php?materialtype=5' title='Книги по нумизматике стоимость(цены) смотреть' class="topmenu <?=(isset($materialtype)&&$materialtype==5)?'active':''?>">Книги по нумизматике</a>
   <li>
   <?=($tpl['user']['user_id']==811&$materialtype!=3&&$materialtype!=5)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/index.php?search=revaluation&nocheck=1' style='float:left' class='pls'><font color=black>(".(($nocheck&&$search=='revaluation')?" - ":" + ").")</font></a>":""?>
   <a href='<?=$cfg['site_dir']?>shopcoins/index.php?search=revaluation' title='Распродажа монет' class="topmenu <?=(isset($search)&&$search=='revaluation')?'active':''?>">Распродажа монет</a></li>        
   <li><?=($tpl['user']['user_id']==811&$materialtype!=3&&$materialtype!=5)?"&nbsp;&nbsp;<a href='".$cfg['site_dir']."shopcoins/index.php?search=newcoins&nocheck=1' style='float:left' class='pls'><font color=black>(".(($nocheck&&$search=='newcoins')?" - ":" + ").")</font></a>":""?>
   <a href='<?=$cfg['site_dir']?>shopcoins/index.php?search=newcoins' title='Новинки 2013-2015' class="topmenu <?=(isset($search)&&$search=='newcoins')?'active':''?>">Новинки 2015-2015</a></li>
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
