<div class="menu-heading subheader"  id='hidden-shopcoins-stat'>
	<a style="color:#ffffff;text-decoration:none;" href="#" >
		<span style="padding-left:17px;" id='hidden-shopcoins-stat'>Последние просмотры (<?=(int) $tpl['user']['fullcount']?>)</span>
	</a>
   <div class="stabs filter-block" style="display:none" id='stat_shop'>
        <ul>
        <li><a href="<?=$cfg['site_dir']?>shopcoins/?module=hstat&task=coins">Монеты (<?=(int) $tpl['user']['coinscount']?>)</a></li>
        <li><a href="<?=$cfg['site_dir']?>shopcoins/?module=hstat&task=filter">Страны и номиналы (<?=(int) $tpl['user']['filterscount']?>)</a></li>
        <li><a href="<?=$cfg['site_dir']?>shopcoins/?module=hstat&task=search">Поиски (<?=(int) $tpl['user']['searchcount']?>)</a></li>
        </ul>
        <div id="tabs-1" class="tabs_cont">    		
           
        </div>
    </div>
 </div>
   <script>
  $(function() {
    $( "#stat_shop" ).tabs({
      beforeLoad: function( event, ui ) {
        ui.jqXHR.fail(function() {
          ui.panel.html(
            "Couldn't load this tab. We'll try to fix this as soon as possible. " +
            "If this wouldn't be a demo." );
        });
      }
    });
  });
  
  function loadTab(obj){
      //console.log(obj);
      if(obj.context.href){
        $.ajax({	
    	    url: obj.context.href, 
    	    type: "GET",        
    	    dataType : "html",                   
    	    success: function (data) { 	
    	        var active_tab = $("#stat_shop").tabs().tabs('option', 'selected')+1;
    	        $('#ui-tabs-'+active_tab).html(data);
    	    }
         }); 
      }
  }
  </script>  