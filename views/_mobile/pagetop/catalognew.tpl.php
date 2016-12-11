<div class="menu-heading"  id='hidden-shopcoins-menu'>
	<a style="color:#ffffff;text-decoration:none;" href="#s" >
		<span style="padding-left:17px;" id='hidden-shopcoins-span'>Разделы каталога</span>
	</a>

<ul id='left_menu_shop' class="menu-sidebar_static" style="display:'none'" >
   <li><a href='<?=$cfg['site_dir']?>catalognew/' title='Монеты стоимость(цены) весь мир' >Монеты</a>   </li>
   <li><a href='<?=$cfg['site_dir']?>catalognew/nabori_monet' title='Наборы монет стоимость(цены) весь мир'>Наборы монет</a></li>
   <li><a href='<?=$cfg['site_dir']?>catalognew/banknoti' title='Банкноты стоимость(цены) весь мир'>Банкноты</a></li>
   <li><a href='<?=$cfg['site_dir']?>catalognew/podarochnye_nabory' title='Подарочные наборы монет  стоимость(цены) весь мир'>Подарочные наборы</a></li>
   <li><a href='<?=$cfg['site_dir']?>catalognew/aksessuary' title='Аксессуары для коллекционеров цены'>Аксессуары для монет</a></li>
   
 </ul>   
  </div> 

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
    </script>

<?php include $cfg['path'] . '/views/common/breadcrumb.tpl.php'; ?> 
