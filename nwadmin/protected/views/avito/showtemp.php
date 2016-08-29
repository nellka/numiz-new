<?php
$this->breadcrumbs=array(
    'Секции авито'=>CHtml::normalizeUrl(array('/avito/index')),
    'Объекты Авито'=>CHtml::normalizeUrl(array('/avito/avitoitems')),
	'Монеты в выборке',
);
$this->menu=array(
	array('label'=>'Создать', 'url'=>array('create')),
);
if (Yii::app()->user->hasFlash('error')) { ?>
	<div class="flash-error">
	    <?php echo Yii::app()->user->getFlash('error') ?>
	</div>
<?}

if($items){?>
<h3>Монеты в выборке</h3>
<table border="1" cellpadding="0" cellspacing="0">
<tr style="border: 1px solid black;">
    <td  style="border: 1px solid black;">Id</td>
    <td  style="border: 1px solid black;">Image</td>
    <td  style="border: 1px solid black;">Категория</td>
	<td  style="border: 1px solid black;">Страна</td>
	<td  style="border: 1px solid black;">Название</td>
	<td  style="border: 1px solid black;">Металл</td>
	<td  style="border: 1px solid black;">Год</td>
	<td  style="border: 1px solid black;">Цена</td>
</tr>
<?
foreach ($items as $item){?>
    <tr>
        <td  style="border: 1px solid black;"><?=$item->shopcoins?></td>
        <td  style="border: 1px solid black;"><img src='http://www.numizmatik.ru/shopcoins/images/<?=$item->shop->image_small?>'></td>
        <td  style="border: 1px solid black;"><?=Shopcoins::$sections[$item->materialtype]?></td>
        <td  style="border: 1px solid black;"><?=$item->groups->name?></td>       
        <td  style="border: 1px solid black;"><a target="_blank" href="http://www.numizmatik.ru/shopcoins/?module=shopcoins&task=show&catalog=<?=$item->shopcoins?>"><?=$item->shop->name?></a></td>
        <td  style="border: 1px solid black;"><?=$item->metal->name?></td>
        <td  style="border: 1px solid black;"><?=$item->year?></td>
        <td  style="border: 1px solid black;"><?=round($item->year)?></td>
	</tr>
<?}?>
</table>
<?}