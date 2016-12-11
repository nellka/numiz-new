<?
$display = 'block';
if($tpl['pagenum']>1||$group_data) $display = 'none';
if($bydate) $display = 'block';

?>
<div id="hidden-by-date-filter" class="menu-heading">
	<a href="#s" style="color:#ffffff;text-decoration:none;">
		<span id="hidden-by-date-filter-span" style="padding-left:17px;">Новые поступления по дате</span>
	</a>

  
<div id='fb-bydate' class="filter-block" style="display:<?=$display?>">

<ul class="filter_heading_ul">
	<div id="filter-groupbydate_container" class="b-d-block">	     
		<?php 					
		foreach ($tpl['filter']['bydate']['filter'] as $filter) { ?>		
			<?if($filter['filter_id']){?>
			    <a href="<?=$r_url?>?bydate=<?=$filter['filter_id']?>" class="<?=($filter['filter_id']==$bydate)?"blue":""?>"> <?=$filter['name'];?></a>
            <?} else {?>
               <a href="<?=$r_url?>" class="<?=($filter['filter_id']==$bydate)?"blue":""?>"> <?=$filter['name'];?></a> 
            <?}?>
      <?}?>  
  </div>
</ul> 	
</div>  
</ul>   
</div>
   
