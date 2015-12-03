<div class="wraper">
    <ul class="tabs">
        <li class="active">Нумизматик рекомендует</li>
        <li>Новинки</li>
        <li>Акции</li>
        <li>Распродажа</li>
    </ul>
</div>  
 <div class="clearfix"></div>
<div class="triger">
    <div class="wraper"> 
      <div class="panes"> 
          <div>
          <?foreach ($tpl['coins']['populars'] as $row){?>
              <div style="float:left;width:200px"><? var_dump($row);?></div>
          <?}?>
         </div>   
          <div class="les">
          <?foreach ($tpl['coins']['new'] as $row){?>
              <div style="float:left;width:200px"><? var_dump($row);?></div>
          <?}?>          
          </div> 
          <div class="les">
          <?foreach ($tpl['coins']['actions'] as $row){?>
              <div style="float:left;width:200px"><? var_dump($row);?></div>
          <?}?>          
          </div> 
          <div class="les">
          <?foreach ($tpl['coins']['sales'] as $row){?>
              <div style="float:left;width:200px"><? var_dump($row);?></div>
          <?}?>          
          </div>  
        </div>
    </div>
</div>
<div class="clearfix"></div>
<div class="wraper">
<h3>Разделы монет</h3>   
</div>
<div class="clearfix"></div>
              
                
        <script> 
    jQuery(function() {
     jQuery("ul.tabs").tabs("div.panes > div");
      });
    </script>
<div class="wraper clearfix central_banner" >
    <?=$tpl['banners']['main_center_1']?>
    <?=$tpl['banners']['main_center_2']?>
</div>

<div class="wraper" >
   <h3>Популярные в категориях</h3>  
</div>  

 <div class="triger">
    <div class="wraper"> 
          <?foreach ($tpl['coins']['populars_in_category']   as $row){?>
              <div style="float:left;width:200px"><? var_dump($row);?></div>
          <?}?>              
   </div> 
</div>              