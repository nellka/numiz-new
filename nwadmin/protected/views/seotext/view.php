<?php
$this->breadcrumbs=array(
    'seo для оценки'=>array('seotext/index'),
	$model->title,
);
$this->pageTitle=$model->title;

$data = $model;
?>

<div class="post">
	<div class="title">
		<?php echo CHtml::link(CHtml::encode($data->title), $data->url); ?>
		<br>
	</div>	
	
	<div class="author">
	    <b>Alias:</b> <?=$data->alias; ?><br>
		<b>Добавлено:</b> <?=date('F j, Y',$data->dateinsert); ?><br>
		<b>keywords:</b> <?=$data->keywords?><br>
		<b>description:</b> <?$data->description?><br>
		<b>Статус:</b> <?Seotext::$statuses[$data->active]?><br>
	</div>
	
	<div class="content">
	<b>Текст:</b><br>
	<?php			
		echo $data->text;			
	?>
	</div>	
</div>
