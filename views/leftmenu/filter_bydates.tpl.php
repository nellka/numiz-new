<div id="hidden-by-date-filter" class="menu-heading">
	<a href="#s" style="color:#ffffff;text-decoration:none;">
		<span id="hidden-by-date-filter" style="padding-left:17px;">Новые поступления по дате</span>
	</a>

  
<div id='fb-bydate' class="filter-block">

<ul class="filter_heading_ul">
	<div id="filter-groupbydate_container" >	     
		<?php 					
		foreach ($tpl['filter']['bydate']['filter'] as $filter) { ?>		
				<div class="checkbox">				
				<input type="radio" name="bydate" value="<?=$filter['filter_id']?>" <?=($filter['filter_id']==$bydate)?"checked":""?> />                              <?if($filter['filter_id']){?>
				    <a href="<?=$r_url?>?bydate=<?=$filter['filter_id']?>"> <?=$filter['name'];?></a>
                <?} else {?>
                   <a href="<?=$r_url?>"> <?=$filter['name'];?></a> 
                <?}?>
				</div>	
      <?}?>  
  </div>
</ul> 	
</div>  
</ul>   
</div>
   
