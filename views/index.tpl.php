<div class="wraper">
    <ul class="tabs">
        <li class="active">Нумизматик рекомендует</li>
        <li class="passive">Новинки</li>
        <li class="passive">Акции</li>
        <li class="passive">Распродажа</li>
    </ul>
</div>  
 <div class="clearfix"></div>
<div class="triger">
    <div class="wraper" style="height:350px;padding-top:15px;"> 
      <div class="panes"> 
          <div>
          <?foreach ($tpl['coins']['populars'] as $row){?>
              <div class="coin_info">
				  <center><img src="http://www.numizmatik.ru/shopcoins/images/874725.jpg"></center><br>
				  <a href=""><?=$row['name']?></a><br>
				  <b>Страна:</b> <?=$row['gname']?><br>
				  <b>Год:</b> <?=$row['year']?><br>
				  <b>Металл:</b> <?=$row['metal']?><br>
				  <b>Состояние:</b> <?=$row['condition']?><br><br>
				  <center><span style="color:red;"><?=$row['currencyprice']?> руб.</span></center>
				  <br>
				  <a href="">
					<img src="<?=$cfg['site_dir']?>images/static_images/stars.jpg" border="">
				  </a>
				  <br>
				  <a href="" class="button25">Купить</a>
			  </div>
          <?}?>
         </div>   
          <div class="les"> 
          <?foreach ($tpl['coins']['new'] as $row){?>
              <div class="coin_info">
				  <center><img src="http://www.numizmatik.ru/shopcoins/images/857893.jpg"></center><br>
				  <a href=""><?=$row['name']?></a><br>
				  <b>Страна:</b> <?=$row['gname']?><br>
				  <b>Год:</b> <?=$row['year']?><br>
				  <b>Металл:</b> <?=$row['metal']?><br>
				  <b>Состояние:</b> <?=$row['condition']?><br><br>
				  <center><span style="color:red;"><?=$row['currencyprice']?> руб.</span></center>
				  <br>
				  <a href="">
					<img src="<?=$cfg['site_dir']?>images/static_images/stars.jpg" border="">
				  </a>
				  <br>
				  <a href="" class="button25">Купить</a>
			  </div>
          <?}?>          
          </div> 
          <div class="les">
          <?foreach ($tpl['coins']['actions'] as $row){?>
              <div class="coin_info">
				  <center><img src="http://www.numizmatik.ru/shopcoins/images/865802.jpg"></center><br>
				  <a href=""><?=$row['name']?></a><br>
				  <b>Страна:</b> <?=$row['gname']?><br>
				  <b>Год:</b> <?=$row['year']?><br>
				  <b>Металл:</b> <?=$row['metal']?><br>
				  <b>Состояние:</b> <?=$row['condition']?><br><br>
				  <center><span style="color:red;"><?=$row['currencyprice']?> руб.</span></center>
				  <br>
				  <a href="">
					<img src="<?=$cfg['site_dir']?>images/static_images/stars.jpg" border="">
				  </a>
				  <br>
				  <a href="" class="button25">Купить</a>
			  </div>
          <?}?>          
          </div> 
          <div class="les">
          <?foreach ($tpl['coins']['sales'] as $row){?>
               <div class="coin_info">
				  <center><img src="http://www.numizmatik.ru/shopcoins/images/874727.jpg"></center><br>
				  <a href=""><?=$row['name']?></a><br>
				  <b>Страна:</b> <?=$row['gname']?><br>
				  <b>Год:</b> <?=$row['year']?><br>
				  <b>Металл:</b> <?=$row['metal']?><br>
				  <b>Состояние:</b> <?=$row['condition']?><br><br>
				  <center><span style="color:red;"><?=$row['currencyprice']?> руб.</span></center>
				  <br>
				  <a href="">
					<img src="<?=$cfg['site_dir']?>images/static_images/stars.jpg" border="">
				  </a>
				  <br>
				  <a href="" class="button25">Купить</a>
			  </div>
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
    <div class="wraper" style="height:350px;padding-top:15px;"> 
          <?foreach ($tpl['coins']['populars_in_category']   as $row){?>
              <div class="coin_info">
				  <center><img src="http://www.numizmatik.ru/shopcoins/images/880394.jpg"></center><br>
				  <a href=""><?=$row['name']?></a><br>
				  <b>Страна:</b> <?=$row['gname']?><br>
				  <b>Год:</b> <?=$row['year']?><br>
				  <b>Металл:</b> <?=$row['metal']?><br>
				  <b>Состояние:</b> <?=$row['condition']?><br><br>
				  <center><span style="color:red;"><?=$row['currencyprice']?> руб.</span></center>
				  <br>
				  <a href="">
					<img src="<?=$cfg['site_dir']?>images/static_images/stars.jpg" border="">
				  </a>
				  <br>
				  <a href="" class="button25">Купить</a>
			  </div>
          <?}?>              
   </div> 
</div>              