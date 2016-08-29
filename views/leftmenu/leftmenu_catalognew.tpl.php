<?/*if(isset($mycoins)&&$mycoins) {?>
   <div class="menu-heading"  id='hidden-shopcoins-menu'></div> 
<?} else {*/?>
<div class="menu-heading"  id='hidden-shopcoins-menu'>
	<a style="color:#ffffff;text-decoration:none;" href="#s" >
		<span style="padding-left:17px;" id='hidden-shopcoins-span'>Разделы каталога</span>
	</a>

<ul id='left_menu_shop' class="menu-sidebar_static">
   <li><a href='<?=$cfg['site_dir']?>catalognew/index.php?materialtype=1' title='Каталог монет со всего мира' class="topmenu <?=($materialtype==1)?'active':''?>">Монеты</a>   </li>
   <li><a href='<?=$cfg['site_dir']?>catalognew/index.php?materialtype=7' title='Каталог наборов монет со всего мира' class="topmenu <?=($materialtype==7)?'active':''?>">Наборы монет</a></li>
  <li><a href='<?=$cfg['site_dir']?>catalognew/index.php?materialtype=2' title='Каталог подарочных наборов монет со всего мира' class="topmenu <?=($materialtype==2)?'active':''?>">Банкноты</a></li>
   <li><a href='<?=$cfg['site_dir']?>catalognew/index.php?materialtype=4' title='Каталог подарочных наборов монет со всего мира' class="topmenu <?=($materialtype==4)?'active':''?>">Подарочные наборы</a></li>
   <li><a href='<?=$cfg['site_dir']?>catalognew/index.php?materialtype=3' title='Аксессуары для нумизматов и коллекционеров' class="topmenu <?=($materialtype==3)?'active':''?>"><font color="red">New</font> Аксессуары для монет</a></li>
</ul>   
  </div>
    <? //include('stat_shopcoins.tpl.php');
   //подключаем фильтры для магазина
    include(DIR_TEMPLATE.'leftmenu/filters/catalognew/filter_country.tpl.php');
    
    if($materialtype==1000){?>
        <div class="center">
            <a title="Покупка-скупка монет и коллекционных материалов" border="0" href="<?=$cfg["site_dir"]?>gde-prodat-monety">
            <img border="0" alt="Покупка-скупка монет и коллекционных материалов" src="<?=$cfg["site_dir"]?>images/banner_kladoiskatel.gif">
            </a>
        </div>
    <?}
?>

   <script> 
   /*
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
   }  */ 
    </script>
