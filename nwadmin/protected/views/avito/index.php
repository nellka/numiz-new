<?php
$this->breadcrumbs=array(
	'Секции Авито',
);
$this->menu=array(
	array('label'=>'Создать', 'url'=>array('create')),
);
?>
<h1>Секции Авито </h1>
<?php 
echo $this->renderPartial('createsection', array('modelcreate'=>$modelcreate)); ?>

<?php $form=$this->beginWidget('CActiveForm',array(
	'id'=>'sender-form',
	'enableAjaxValidation'=>false,
	'action'=>Yii::app()->createUrl('//avito/savesections')
)); ?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'ajaxVar' => true,
	'id'=>'avito-grid',
	'columns'=>array(
		array('class'=>'CCheckBoxColumn','selectableRows'=>100),
	 	array(
			'name'=>'sid',
			'type'=>'raw',
			'value'=>'$data->sid',
			'filter'=>false
		),
	    array(
			'name'=>'materialtype',
			'type'=>'raw',
			'value'=>'Shopcoins::$sections[$data->materialtype]',
			'filter'=>CHtml::activeDropDownList($model, "group_id",Avitosection::model()->getGroupList(),array('empty'=>'Все'))
		),	    
	    array(
			'name'=>'group_id',
			'type'=>'raw',
			'value'=>'($data->group_id)?$data->group->name:""',
			'filter'=>CHtml::activeDropDownList($model, "group_id",Avitosection::model()->getGroupList(),array('empty'=>'Все'))
		),
		array(
			'name'=>'nominal_id',
			'type'=>'raw',
			'value'=>'($data->nominal_id)?$data->nominal->name:""',
			'filter'=>CHtml::activeDropDownList($model, "nominal_id",Avitosection::model()->getNominalList(),array('empty'=>'Все'))
		),
		array(
			'name'=>'metal_id',
			'type'=>'raw',
			'value'=>'($data->metal_id)?$data->metal->name:""',
			'filter'=>CHtml::activeDropDownList($model, "metal",Avitosection::model()->getMetalList(),array('empty'=>'Все'))
		),
	    'year_from',
	    'year_to',
	    'priority'/*
		array(
			'name'=>'title',
			'type'=>'raw',
			'value'=>'CHtml::link(CHtml::encode($data->title), $data->url)'
		),
		array(
			'name'=>'materialtype',
			'value'=>'Shopcoins::$sections[$data->materialtype]',
			//'filter'=>Lookup::items('PostStatus'),
		),
		
		array(
			'name'=>'nominal_id',
			'type'=>'raw',
			'value'=>'($data->nominal_id)?$data->nominal->name:""'
		),
		array(
			'name'=>'active',
			'value'=>'Shopcoinsseotext::$statuses[$data->active]',
			//'filter'=>Lookup::items('PostStatus'),
		),
		array(
			'name'=>'create_time',
			'type'=>'datetime',
			'value'=>'date("d.m.Y H:i",$data->dateinsert)',
			'filter'=>false,
		)*/,
		array(
			'class'=>'CButtonColumn',
		),
	),
)); 
echo CHtml::SubmitButton('Сохранить',array('id'=>'save','name'=>'save')); ?>

<?php $this->endWidget(); ?>
