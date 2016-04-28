<div style="margin-top:25px;">
<div id='item-top-menu' style='float: left; height: 32px;  margin: 0 20px 0 0;  overflow: hidden; position: relative; z-index: 2000;'>
<div class="menu-heading" onclick="showShopcoinsMenu();return false">
	<a style="color:#ffffff;text-decoration:none;" href="#s" onclick="return false">
		<span style="padding-left:17px;">Разделы магазина</span>
	</a>
</div>
<? 

$page_url = 'index.php';
if(isset($mycoins)&&$mycoins) $page_url = 'mycoins.php';
?>
<ul id='#left_menu_shop' class="menu-sidebar_static">
   <li><a href='<?=$cfg['site_dir']?>shopcoins/<?=$page_url?>?materialtype=1' title='Монеты стоимость(цены) весь мир' class="topmenu <?=(isset($materialtype)&&!$search&&$materialtype==1)?'active':''?>">Монеты</a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/<?=$page_url?>?materialtype=8' title='Дешевые монеты стоимость(цены) весь мир' class="topmenu <?=(isset($materialtype)&&$materialtype==8)?'active':''?>">Мелочь </a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/<?=$page_url?>?materialtype=6' title='Цветные монеты' class="topmenu <?=(isset($materialtype)&&$materialtype==6)?'active':''?>">Цветные монеты </a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/<?=$page_url?>?materialtype=10' title='Нотгельды стоимость(цены) весь мир' class="topmenu <?=(isset($materialtype)&&$materialtype==10)?'active':''?>">Нотгельды </a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/<?=$page_url?>?materialtype=7' title='Наборы монет стоимость(цены) весь мир' class="topmenu <?=(isset($materialtype)&&$materialtype==7)?'active':''?>">Наборы монет</a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/<?=$page_url?>?materialtype=9' title='Лоты монет для начинающих нумизматов' class="topmenu <?=(isset($materialtype)&&$materialtype==9)?'active':''?>">Лоты монет для начинающих нумизматов</a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/<?=$page_url?>?search=newcoins' title='Новинки <?=(date('Y',time())-1)?>-<?=date('Y',time())?>' class="topmenu <?=(isset($search)&&$search=='newcoins')?'active':''?>">Новинки <?=(date('Y',time())-1)?>-<?=date('Y',time())?></a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/<?=$page_url?>?materialtype=2' title='Банкноты стоимость(цены) весь мир' class="topmenu <?=(isset($materialtype)&&$materialtype==2)?'active':''?>">Боны</a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/?materialtype=3' title='Аксессуары для коллекционеров цены' class="topmenu <?=(isset($materialtype)&&$materialtype==3)?'active':''?>">Аксессуары для монет</a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/?materialtype=4' title='Подарочные наборы монет  стоимость(цены) весь мир' class=topmenu>Подарочные наборы</a></li>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/<?=$page_url?>?materialtype=5' title='Книги о нумизматике бонистике цены' class="topmenu <?=(isset($materialtype)&&$materialtype==5)?'active':''?>">Книги о монетах</a>
   <li><a href='<?=$cfg['site_dir']?>shopcoins/<?=$page_url?>?search=revaluation' title='Распродажа монет' class="topmenu <?=(isset($search)&&$search=='revaluation')?'active':''?>">Распродажа монет</a></li>        
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