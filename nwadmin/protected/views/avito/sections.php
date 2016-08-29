<?php
$this->breadcrumbs=array(
    'Секции авито'=>'/index',
    'Объекты Авито'=>'/avitoitem',
	'Сохраненные секции',
);
$this->menu=array(
	array('label'=>'Создать', 'url'=>array('create')),
);
if (Yii::app()->user->hasFlash('error')) { ?>
	<div class="flash-error">
	    <?php echo Yii::app()->user->getFlash('error') ?>
	</div>
<?}
if($sections){?>
<h3>Записанные секции (Нужно скопировать в Exel и отправить в программу)</h3>
<table border="1" cellpadding="0" cellspacing="0">
<tr style="border: 1px solid black;">
    <td  style="border: 1px solid black;">Категория</td>
	<td  style="border: 1px solid black;">Страна</td>
	<td  style="border: 1px solid black;">Название</td>
	<td  style="border: 1px solid black;">Металл</td>
	<td  style="border: 1px solid black;">Год от</td>
	<td  style="border: 1px solid black;">Год до</td>
</tr>
<?
foreach ($sections as $section){?>
	<tr>
	    <td  style="border: 1px solid black;"><?=Shopcoins::$sections[$section->materialtype]?></td>
		<td  style="border: 1px solid black;"><?=$section->group->name?></td>
		<td  style="border: 1px solid black;"><?=$section->nominal->name?></td>
		<td  style="border: 1px solid black;"><?=$section->metal->name?></td>
		<td  style="border: 1px solid black;"><?=$section->year_from?></td>
		<td  style="border: 1px solid black;"><?=$section->year_to?></td>
	</tr>
<?}?>
</table>

<?}

if($avitoitems){?>
<h3>Записанные Объекты</h3>
<table border="1" cellpadding="0" cellspacing="0">
<tr style="border: 1px solid black;">
    <td  style="border: 1px solid black;">Id</td>
    <td  style="border: 1px solid black;">Sid</td>
    <td  style="border: 1px solid black;">Image</td>
    <td  style="border: 1px solid black;">Категория</td>
	<td  style="border: 1px solid black;">Страна</td>
	<td  style="border: 1px solid black;">Название</td>
	<td  style="border: 1px solid black;">Металл</td>
	<td  style="border: 1px solid black;">Год</td>
</tr>
<?
foreach ($avitoitems as $avitoitem){?>
	<tr>
	    <td  style="border: 1px solid black;"><?=$avitoitem->id?></td>
        <td  style="border: 1px solid black;"><?=$avitoitem->sid?></td>
        <td  style="border: 1px solid black;"><img src='http://www.numizmatik.ru/shopcoins/images/<?=$avitoitem->avitoshop->shop->image_small?>'></td>
	    <td  style="border: 1px solid black;"><?=Shopcoins::$sections[$avitoitem->avitoshop->shop->materialtype]?></td>	    
		<td  style="border: 1px solid black;"><?=$avitoitem->avitoshop->shop->group->name?></td>
		<td  style="border: 1px solid black;"><a target="_blank" href="http://www.numizmatik.ru/shopcoins/?module=shopcoins&task=show&catalog=<?=$avitoitem->avitoshop->shopcoins?>"><?=$avitoitem->avitoshop->shop->name?></a></td>
		<td  style="border: 1px solid black;"><?=$avitoitem->avitoshop->shop->metal->name?></td>
		<td  style="border: 1px solid black;"><?=$avitoitem->avitoshop->shop->year?></td>
	</tr>
<?}?>
</table>
<?}