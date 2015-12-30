<div id='products'>
<?	
if( $childen_data_nominals&&$tpl['datatype']=='text_html') {
	$filter_nominal_content =  contentHelper::render('leftmenu/filter_block',array('name'=>'Номинал','filter_group_id'=>'nominal','filter_group_id_full'=>'nominals','filter'=>$childen_data_nominals,'materialtype'=>$materialtype,'checked'=>$nominals));
	?>   
   <script>
    $(function(){    	 
    	$('#fb-nominals').remove();
    	$('<?=escapeJavaScriptText($filter_nominal_content)?>').insertAfter('#fb-groups');
        $('#search-params #fb-nominals input').on('change',function(){sendData();});   
    });   
   </script>
<?} elseif (!$childen_data_nominals){?>
 <script>
	$('#fb-nominals').remove();
	 </script>
<?}



include('pager.tpl.php');

if($tpl['shop']['errors']){?>
	<font color="red"><?=implode("<br>",$tpl['shop']['errors'])?></font>
<?} else {
?>
<div class="product-grid">
<?
    $i=1;
    foreach ($tpl['shop']['MyShowArray'] as $key=>$rows){		
    	if(in_array($materialtype,array(7,4))){
    		echo "<div class='blockshop_spisok'>";
    		include('items/item_nabor.tpl.php');
    		echo "</div>";
    	} else {
    		echo "<div class='blockshop'>";
    		include('items/item.tpl.php');
    		echo "</div>";
    	}	
    	$i++;	
    }?>
</div>
<?}?>