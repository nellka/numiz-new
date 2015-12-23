<div >
<div id='item-top-menu' style='float: left; position: relative; height: 40px; z-index: 2000;overflow: hidden;'>
<div class="menu-heading" onclick="showShopcoinsMenu();return false">
	<a style="color:#ffffff;text-decoration:none;" href="#s" onclick="return false">
		<span style="padding-left:17px;">Разделы магазина</span>
	</a>
</div>
<ul id='#left_menu_shop' class="menu-sidebar_static">
   <li><a href='<?=$cfg['site_dir']?>shopcoins/index.php?materialtype=1' title='Монеты стоимость(цены) весь мир' class="topmenu <?=(isset($materialtype)&&!$search&&$materialtype==1)?'active':''?>">Монеты</a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/index.php?materialtype=8' title='Дешевые монеты стоимость(цены) весь мир' class="topmenu <?=(isset($materialtype)&&$materialtype==8)?'active':''?>">Мелочь </a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/index.php?materialtype=6' title='Цветные монеты' class="topmenu <?=(isset($materialtype)&&$materialtype==6)?'active':''?>">Цветные монеты </a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/index.php?materialtype=10' title='Нотгельды стоимость(цены) весь мир' class="topmenu <?=(isset($materialtype)&&$materialtype==10)?'active':''?>">Нотгельды </a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/index.php?materialtype=7' title='Наборы монет стоимость(цены) весь мир' class="topmenu <?=(isset($materialtype)&&$materialtype==7)?'active':''?>">Наборы монет</a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/index.php?materialtype=9' title='Лоты монет для начинающих нумизматов' class="topmenu <?=(isset($materialtype)&&$materialtype==9)?'active':''?>">Лоты монет для начинающих нумизматов</a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/index.php?search=newcoins' title='Новинки 2013-2015' class="topmenu <?=(isset($search)&&$search=='newcoins')?'active':''?>">Новинки 2015-2015</a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/index.php?materialtype=2' title='Банкноты стоимость(цены) весь мир' class="topmenu <?=(isset($materialtype)&&$materialtype==2)?'active':''?>">Боны</a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/?materialtype=3' title='Аксессуары для коллекционеров цены' class="topmenu <?=(isset($materialtype)&&$materialtype==3)?'active':''?>">Аксессуары для монет</a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/?materialtype=4' title='Подарочные наборы монет  стоимость(цены) весь мир' class=topmenu>Подарочные наборы</a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/index.php?materialtype=5' title='Книги о нумизматике бонистике цены' class="topmenu <?=(isset($materialtype)&&$materialtype==5)?'active':''?>">Книги о монетах</a>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/index.php?search=revaluation' title='Распродажа монет' class="topmenu <?=(isset($search)&&$search=='revaluation')?'active':''?>">Распродажа монет</a></li>        
   </ul>   
   
   <script> function showShopcoinsMenu(){
       console.log(jQuery('#left_menu_shop'));
        /*if(!jQuery('#left_menu_shop').is(':visible')){
            jQuery('#left_menu_shop').show();
        } else {
            jQuery('#left_menu_shop').hide();
        }*/
        console.log(jQuery('#item-top-menu').css("overflow"));
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
<div style='float:left'>
<a href="<?=$cfg['site_dir']?>/shopcoins/?materialtype=<?=$materialtype?>">Вернуться к подбору товара</a>
</div>

<div style='float:left'>
<?php include $cfg['path'] . '/views/common/breadcrumb.tpl.php'; ?> 
</div>	
	        
<div style='float:left'>
<a href="">Предыдущий</a>
<a href="">Следующий</a>
</div>		

</div >
<br style="clear: both;">