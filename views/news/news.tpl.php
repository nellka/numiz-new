<div id='news' class="news-cls">

	<div>
		<h1>Новости</h1>
	</div>

    <?include('onpage.tpl.php');?>
	<div id='pager' class="pager">		
		<?include('pager.tpl.php');?>
	</div>

	<?
if($tpl['news']['errors']){?>
		<div class="error"><?=implode("<br>",$tpl['news']['errors'])?></div>
	<?} else {
		?>
		<div class="news-grid">
			<?
			foreach ($tpl['news']['data'] as $key=>$rows){?>
				<div class='blockshop' id='news-<?=$rows['news']?>'>
					<div class='blockshop-full' itemscope itemtype="http://schema.org/Article">
						<div class="center">
						<a class="borderimage primage" title='Читать новость нумизматики - <?=htmlspecialchars($rows["name"])?>' href="<?=$rows['namehref']?>">
							<img itemprop="image" alt="Читать новость нумизматики - <?=htmlspecialchars($rows["name"])?>" title="Читать новость нумизматики - <?=htmlspecialchars($rows["name"])?>" src="<?=$rows["img"]?>">
						</a>
						</div>


						<div class="coinname">
							<a href="<?=$rows['namehref']?>" target=_blank title='Читать новость нумизматики - <?=htmlspecialchars($rows["name"])?>'><span itemprop="headline"><?=$rows["name"]?></span></a>
						</div>
						<div class="gray">
						<meta itemprop="datePublished" content="<?=date('Y-m-d',$rows["date"])?>">
						<?=date('d.m.Y',$rows["date"])?>
						</div>
						<div class="subinfo" itemprop="description"><?=$rows["text"]?></div>
						<div>
							<div class="left"><a href="<?=$rows['namehref']?>">Читать дальше</a></div>
							<!--<div class="right"><a href="#">32 Коментария</a></div>-->
						</div>
					</div>
				</div>
			<?}?>
		</div>

	<?}?>

	<div style="width: 100%; display: table;">
		<?include('pager.tpl.php');?>
	</div>
</div>