<?php
$this->breadcrumbs=array(
    'seo'=>array('post/index'),
	$model->title,
);
$this->pageTitle=$model->title;
$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Редактировать', 'url'=>array('update' , 'id'=>$model->id)),
);

$data = $model;
?>

<div class="post">
	<div class="title">
		<?php echo CHtml::link(CHtml::encode($data->title), $data->url); ?>
		<br>
	</div>	
	<div class="author">
		<b>Добавлено:</b> <?=date('F j, Y',$data->dateinsert); ?><br>
		<b>Раздел:</b> <?=Shopcoins::$sections[$data->materialtype]?><br>
		<b>Страна/Группа:</b> <?if($data->group_id){ echo $data->group->name;}?><br>
		<b>Номинал:</b> <?if($data->nominal_id){ echo $data->nominal->name;}?><br>
	</div>
	
	<div class="content">
	<b>Текст:</b><br>
	<?php			
		echo $data->text;			
	?>
	</div>	
</div>
