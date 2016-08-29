<?php
$this->breadcrumbs=array(
	'Seo тексты оценки',
);
$this->menu=array(
	array('label'=>'Создать', 'url'=>array('create')),
);
?>
<h1>Seo тексты оценки</h1>
<a href="/nwadmin/index.php/seotext/create">Создать</a>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
	   'id',
	   'alias',
		array(
			'name'=>'title',
			'type'=>'raw',
			'value'=>'CHtml::link(CHtml::encode($data->title), "#")'
		),
		
		'keywords',
		'description',
		//'text',
		array(
			'name'=>'active',
			'value'=>'Seotext::$statuses[$data->active]',
			'filter' => CHtml::activeDropDownList($model, 'active',Seotext::$statuses,array('empty'=>'Все')),
		),
		array(
			'name'=>'create_time',
			'type'=>'datetime',
			'value'=>'date("d.m.Y H:i",$data->dateinsert)',
			'filter'=>false,
		),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); 

?>
