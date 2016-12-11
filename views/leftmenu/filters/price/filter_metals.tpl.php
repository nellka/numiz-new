<?if ($tpl['filter']['metals']) {
	
	$metalUrlParams = array();
	$metalUrlParams['group'] = $urlParams['group'];      
	?>
	
	<table border=0 cellpadding=0 cellspacing=0 width=100%>
		<tr><td style="background:#ffcc66;" class="tboard" colspan=<?=count($tpl['filter']['metals']['filter'])?>><b><?=$GroupName?>:</b> </tr>
		<tr bgcolor="#ebe4d4">
		
		<?foreach ($tpl['filter']['metals']['filter'] as $filter) {
			$metalUrlParams['metal'] = array($filter['filter_id']=>$filter['name']);
			?>
		
			<td class=tboard>
			<? if(in_array($filter['filter_id'], $metals)){?>
				<strong><?= $filter["name"]?></strong>		
			<?} else {?>
				<a href="<?=urlBuild::makePrettyUrl($metalUrlParams,$r_url)?>"> <?=$filter['name'];?></a>
			<?}?>
		<?}?>
		</tr>
		
		<tr bgcolor="#fff8e8">		
		<?
		//var_dump($tpl['filter']['metals']['filter']);
		foreach ($tpl['filter']['metals']['filter'] as $filter) {
			$metalUrlParams['metal'] = array($filter['filter_id']=>$filter['name']);
			?>			
			<td class="tboard" nowrap style="padding:5px" valign="top">
			<?
				foreach ($filter["nominals"] as $n){
					if(in_array($n['id'],$nominal_data)&&in_array($filter['filter_id'], $metals)){
						echo "<b>".$n['name']."</b><br>";
					} else {
						$metalUrlParams['nominal'] = array($n['id']=>$n['name']); ?>
						<a href="<?=urlBuild::makePrettyUrl($metalUrlParams,$r_url)?>"> <?=$n['name'];?></a><br>						
					<?}					
				}?>				
			</td>
		<?}?>
		</tr>
		</table>
		
	<?/*
	$metalUrlParams = array();
	$metalUrlParams['group'] = $urlParams['group'];
	$metalUrlParams['materialtype'] = $urlParams['materialtype'];   
    $metalUrlParams['nominal'] = $urlParams['nominal'];   
    
    $metalUrlParams['usershopcoinssubscribe'] = $urlParams['usershopcoinssubscribe'];
	$metalUrlParams['usercatalogsubscribe'] = $urlParams['usercatalogsubscribe'];
	$metalUrlParams['usermycatalog'] = $urlParams['usermycatalog'];
	$metalUrlParams['usermycatalogchange'] = $urlParams['usermycatalogchange'];

    $style = '';
    if(isset($tpl['filter']['theme'])) $style .="s_t";
    if(isset($tpl['filter']['conditions'])&&$groups) $style .="c";
    ?>
	<div id='fb-metal' class="filter-block">
		<div class="filter_heading" id='metal-header-close' style="display:<?=$show_metal?'none':'block'?>">
			<div class="left">Металл</div> 						
			<div class="right"><a class="fc" href="#" onclick="ShowFilterB('metal',1);return false;">Развернуть</a></div>
		</div> 
		<div class="filter_heading" id='metal-header-open' style="display:<?=$show_metal?'block':'none'?>">
			<div class="left">Металл</div>    
			<div style="text-align:center;padding: 0 0 0 20px;" class="left">    	
    			    <a class="fc" id='metals-full-show' href="#" onclick="full_filter('metals');return false;"></a>
    		</div>       			
			<div class="right"><a class="fc" href="#" onclick="clear_filter('metals');return false;">Сбросить</a></div>
		</div>      
	
		<ul class="filter_heading_ul <?=$style?>" id='ul-metal' style="display:<?=$show_metal?'block':'none'?>">
			<div id="filter-groupmetals_container" class="filter-groupmetals_container_<?=(count($tpl['filter']['metals']['filter'])>13)?1:1?>">
				<?php 
				foreach ($tpl['filter']['metals']['filter'] as $filter) {
					$metalUrlParams['metal'] = array($filter['filter_id']=>$filter['name']);
					?>            
					<div class="checkbox">
	                    <input type="checkbox" name="metals[]" value="<?=$filter['filter_id']?>" <?=(is_array($metals)&&in_array($filter['filter_id'], $metals))?"checked":""?>/>
					       <a href="<?=urlBuild::makePrettyUrl($metalUrlParams,$r_url)?>"> <?=$filter['name'];?></a>
					</div>
				<?}?>        
			</div>   
	
		</ul> 	
	</div>
<?*/}?>



