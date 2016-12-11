<div style="margin-top:25px;">
<div id='item-top-menu' class="item-top-smenu">
<div class="menu-heading" onclick="showShopcoinsMenu();return false">
	<a style="color:#ffffff;text-decoration:none;" href="#s" onclick="return false">
		<span style="padding-left:17px;">Разделы магазина</span>
	</a>
</div>
<? 

//$page_url = 'index.php';
//if(isset($mycoins)&&$mycoins) $page_url = 'mycoins.php';
?>
<ul id='#left_menu_shop' class="menu-sidebar_static">
   <li><a href='<?=$cfg['site_dir']?>shopcoins' title='Монеты стоимость(цены) весь мир' class="topmenu <?=(isset($materialtype)&&!$search&&$materialtype==1)?'active':''?>">Монеты</a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/meloch' title='Дешевые монеты стоимость(цены) весь мир' class="topmenu <?=(isset($materialtype)&&$materialtype==8)?'active':''?>">Мелочь </a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/cvetnie_moneti' title='Цветные монеты' class="topmenu <?=(isset($materialtype)&&$materialtype==6)?'active':''?>">Цветные монеты </a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/notgeldy' title='Нотгельды стоимость(цены) весь мир' class="topmenu <?=(isset($materialtype)&&$materialtype==10)?'active':''?>">Нотгельды </a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/nabori_monet' title='Наборы монет стоимость(цены) весь мир' class="topmenu <?=(isset($materialtype)&&$materialtype==7)?'active':''?>">Наборы монет</a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/loty_dlya_nachinayushchih' title='Лоты монет для начинающих' class="topmenu <?=(isset($materialtype)&&$materialtype==9)?'active':''?>">Лоты монет для начинающих</a></li>  
   <li><a href='<?=$cfg['site_dir']?>shopcoins/banknoti' title='Банкноты стоимость(цены) весь мир' class="topmenu <?=(isset($materialtype)&&$materialtype==2)?'active':''?>">Банкноты</a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/aksessuary' title='Аксессуары для коллекционеров цены' class="topmenu <?=(isset($materialtype)&&$materialtype==3)?'active':''?>">Аксессуары для монет</a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/podarochnye_nabory' title='Подарочные наборы монет  стоимость(цены) весь мир' class=topmenu>Подарочные наборы</a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/knigi' title='Книги о нумизматике бонистике цены' class="topmenu <?=(isset($materialtype)&&$materialtype==5)?'active':''?>">Книги о монетах</a>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/revaluation' title='Распродажа монет' class="topmenu <?=(isset($search)&&$search=='revaluation')?'active':''?>">Распродажа монет</a></li>   
    <li><a href='<?=$cfg['site_dir']?>shopcoins/newcoins' title='Новинки монет <?=(date('Y',time())-2)?>-<?=date('Y',time())?>' class="topmenu <?=(isset($search)&&$search=='newcoins')?'active':''?>">Новинки монет <?=(date('Y',time())-2)?>-<?=date('Y',time())?></a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/series' title='Серии монет'>Серии монет</a></li>      
   </ul>   
   
   <script> function showShopcoinsMenu(){
        /*if(!jQuery('#left_menu_shop').is(':visible')){
            jQuery('#left_menu_shop').show();
        } else {
            jQuery('#left_menu_shop').hide();
        }*/
         jQuery('#item-top-menu').animate({height: "40px"}, function() {
             if(jQuery('#item-top-menu').css("overflow")=='hidden'){
                jQuery('#item-top-menu').css("overflow", "visible");
             } else {
                 jQuery('#item-top-menu').css("overflow", "hidden");
             }
        });

        return false;
   }</script>
   
</div>