<br><div class="main_context">
<h1>Купить или продать монеты, скупка, продажа и оценка монет любого достоинства</h1>

<?
if($next_key){
echo "<h3>Купить или продать монеты, скупка, продажа и оценка монет {$m}{$p[$name][0]}</h3>

<p>Мы рады что Вы зашли на наш сайт по монетам и всё что с ними связано, на данный момент Вы скорее всего территориально {$p[$name][3]} {$p[$name][1]} ? 
<p></p>
<p>Мы можем предложить Вам весь комплекс услуг по скупке монет их оценке, продаже или покупке. Так как Ваше местоположение это  {$p[$name][0]}, то мы можем Вам помочь в этом! 
<p></p>
<p>Мы знаем как тяжело найти монеты или толкового покупателя на них. Мы умеем оценивать монеты и давать им реальную, оптимальную цену!
<p></p>
<p>Мы скупаем (покупка) монеты, продаем, оценивам монеты (золотые, серебрянные, бронзовые, медные и т.д.) по Москве и Московской области. 
<p></p>
<p> Мы купим или оценим Ваши монеты  {$m2}{$p[$name][1]}.
<p></p>
<p>Наши приемущества перед конкурентами и частными лицами: 
<ul><li>Мы работаем очень давно 
<li><a href=".$cfg['site_dir']."shopcoins>Магазин монет в Москве</a>
<li>Большой выбор монет практически всех номиналов и достоинств
<li>Цены Вас приятно удивят
<li><a href=".$cfg['site_dir']."gde-prodat-monety>Скупка монет</a>
<li><a href=".$cfg['site_dir']."ocenka-stoimost-monet>Оценка монет</a>
<li><a href=".$cfg['site_dir'].">Продажа монет</a>
<li><a href=".$cfg['site_dir']."shopcoins/delivery.php>Доставка монет к клиенту</a>
</ul>
<p></p>
<p>Мы стремимся быть первыми в этой области. И работаем {$p[$name][3]} {$p[$name][1]}. 
<p></p>
<p>А если это удобно и выгодно, то Вам это нужно {$p[$name][3]} {$p[$name][1]}, скорее звоните и мы Вам всегда поможем с монетами :
<p></p> 
<ul>
<h4>+7-903-006-00-44</h4>
<h4>+7-915-00-2222-3</h4>
</ul>
<p></p>
<p>Монеты это не только хобби, но и выгодный доход!
<p></p>

<p>{$m3} {$p[$name][0]} - мы гордимся своей работой !!!
<p>&nbsp;</p>
<p>Мы так же скупаем, оцениваем и продаем монеты:</p>
<ul>";
}

if ($next_key) {	
	$cur = $p[$p_keys[$current_key_index-1]];

	if ($cur) {
		echo "<li><a href='".$cfg['site_dir']."metro/".$p_keys[$current_key_index-1]."'>Скупка, оценка, продажа монет {$cur[3]} {$cur[1]}</a>";
	
		$cur=$p[$p_keys[$current_key_index-2]]; 
		if ($cur) {
			echo "<li><a href='".$cfg['site_dir']."metro/".$p_keys[$current_key_index-2]."'>Купить или продать монеты, скупка, продажа и оценка монет {$cur[3]} {$cur[1]}</a>";
		}	
	}		
	$cur=$p[$p_keys[$current_key_index+1]]; if ($cur) echo "<li><a href='".$cfg['site_dir']."metro/".$p_keys[$current_key_index+1]."'>Купить или продать монеты, скупка, продажа и оценка монет {$cur[3]} {$cur[1]}</a>";
	$cur=$p[$p_keys[$current_key_index+2]]; if ($cur) echo "<li><a href='".$cfg['site_dir']."metro/".$p_keys[$current_key_index+2]."'>Купить или продать золотые, серебрянные, медные и др. монеты, скупка, продажа и оценка монет {$cur[3]} {$cur[1]}</a>";
	$cur=$p[$p_keys[$current_key_index+3]]; if ($cur) echo "<li><a href='".$cfg['site_dir']."metro/".$p_keys[$current_key_index+3]."'>Купить или продать монеты любого номинала в Москве и МО, скупка, продажа и оценка монет {$cur[3]} {$cur[1]}</a>";
} else {	
	echo "<br>";	
	foreach ($tmparray as $key=>$value) {	
		echo "<a href='".$cfg['site_dir']."metro/".$p_keys[$value]."'>".str_replace(" ","",$p[$p_keys[$value]][0])."</a> ";
	}
	
	echo "<br><br><a href=".$cfg['site_dir']."place/goroda.html>Купить или продать монеты города Московской области</a>";		
		
}	
echo "</ul>";
/*
$maintext = '<div class=txt  style=\"margin-left:10px; margin-right:5px;\"><br><h1 style="font-size:16px; font-family:arial;">"Монеты – происхождение"</h1>
<p class=txt>Согласно истории древние греки приписывали изобретение так называемой «<strong>монеты</strong>»  своим мифическим героям, римляне завоеватели — своим богам Сатурну или Янусу.   Согласно их пониманию, самые древние  <strong>монеты</strong> с головой  двуликого бога и носом корабля Бог Янус сделал в честь бога Сатурна (или бога времени), который приплыл с острова Крит в Италию на корабле. Само же слово «<strong>монета</strong>» переводится с латинского как  «советница» или «предостерегающая». Данный титул имела в Риме только богиня Юнона — супруга великого Юпитера. <br>
<img src=./images/coins001.jpg><br> 

Лидийская <strong>монета</strong> VI века до н. э.<br>
 Геродот и часть других древних писателей писали о том, что первые <strong>монеты</strong> чеканили в Лидии (малоазийском государстве). Сегодня достоверно известно, что наидревнейшие <strong>монеты</strong> появились около 685 лет до н.э. и  именно там, при царе Ардизе. Чеканились они из природного сплава золота и серебра электрума. На одной стороне <strong>монеты</strong> стоит голова ассирийского льва, на другой - проба. Уже через несколько десятков лет <strong>монеты</strong> начали изготовлять в Эгине (греческом город). Чеканились они уже из серебра, форма их была отличной от лидийских <strong>монет</strong>. Ученые предполагают, что Эгинейская  <strong>монета</strong> была изобретена позже Лидийской, но независимо от них. И уже из Эгины и Лидии  <strong>монеты</strong> очень быстро разошлись по всей Древней Греции, а позже и в Иран и колониям. Затем уже <strong>монеты</strong> появились и у древних римлян и у отсталых варварских племён. Самостоятельно <strong>монеты</strong> были отчеканены в Китае и Индии. Так в Китае первые бронзовые <strong>монеты</strong> отливались уже в 12 веке до н.э.<br>
Быстрое распространение <strong>монет</strong> по всему миру связывают с удобством их дробления, хранения и соединения, достаточно  большой их стоимостью при незначительном объёме и весе, что весьма прибыльно и удобно при обмене.
<br>
</div><br>';*/
?>
<br><br>

<div class="table coin_division_block nnews">
	<h2 class="tboard table_active_block" style=" font-size: 14px;"><a href="<?=$cfg['site_dir']?>shopcoins">Магазин монет - купить монеты, банкноты, альбомы для монет, серебряные и золотые монеты</a></h3>
	<div class="triger-carusel">	
        <div class="d-carousel">
        <ul class="carousel">
        <?foreach ($tpl['catalog']['coins'] as $rows_show_relation2){ 
        	
        	$rows_show_relation2['metal'] = $tpl['metalls'][$rows_show_relation2['metal_id']];
        	$rows_show_relation2['condition'] = $tpl['conditions'][$rows_show_relation2['condition_id']]; ?>
        <li>        	
        
        <div class="coin_info-mini left " id='item<?=$rows_show_relation2['shopcoins']?>'>
            <div id=show<?=$rows_show_relation2['shopcoins']?>></div>
            <?	
            $statuses = $shopcoins_class->getBuyStatus($rows_show_relation2["shopcoins"],$tpl['user']['can_see'],$ourcoinsorder,$shopcoinsorder);
            $rows_show_relation2['buy_status'] = $statuses['buy_status'];
            $rows_show_relation2['reserved_status'] = $statuses['reserved_status'];	
            $rows_show_relation2['is_mobile'] = $tpl['is_mobile'];	
            echo contentHelper::render('shopcoins/item/itemmini-carusel',$rows_show_relation2);
            ?>				
        </div>
        </li>
        <?}?>
        </ul>
        </div>
    </div>	
</div>

                
<script> 
    $(function() {
        $('.d-carousel .carousel').jcarousel({
            scroll: 1,
            itemFallbackDimension: 75
        });
  });
</script>              

</div>